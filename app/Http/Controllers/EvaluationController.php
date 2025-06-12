<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationScore;
use App\Models\EvaluationCriteria;
use App\Models\EvaluationPeriod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $query = Evaluation::with(['pyd', 'ppp', 'ppk', 'evaluationPeriod'])
            ->whereHas('evaluationPeriod', function($q) use ($year) {
                $q->where('tahun', $year);
            });
        
        $user = Auth::user();
        
        if ($user->isPYD()) {
            $query->where('pyd_id', $user->id);
        } elseif ($user->isPPP()) {
            $query->where('ppp_id', $user->id);
        } elseif ($user->isPPK()) {
            $query->where('ppk_id', $user->id);
        }
        
        $evaluations = $query->paginate(10);
        
        $availableYears = EvaluationPeriod::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');
        
        return view('evaluations.index', compact('evaluations', 'availableYears', 'year'));
    }

    public function create()
    {
        $evaluationPeriods = EvaluationPeriod::where('jenis', 'penilaian')
            ->orderBy('tahun', 'desc')
            ->get();

        // Get all evaluation period IDs
        $periodIds = $evaluationPeriods->pluck('id')->toArray();

        // Get PYD IDs already assigned in any evaluation for these periods
        $assignedPydIds = Evaluation::whereIn('evaluation_period_id', $periodIds)
            ->pluck('pyd_id')
            ->toArray();

        // Get PYDs excluding those already assigned
        $pyds = User::where('peranan', 'pyd')
            ->whereNotIn('id', $assignedPydIds)
            ->get();

        $ppps = User::where('peranan', 'ppp')->get();
        $ppks = User::where('peranan', 'ppk')->get();

        return view('evaluations.create', compact('evaluationPeriods', 'pyds', 'ppps', 'ppks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'evaluation_period_id' => 'required|exists:evaluation_periods,id',
            'pyd_ids' => 'required|array',
            'pyd_ids.*' => 'exists:users,id',
            'ppp_ids' => 'required|array',
            'ppp_ids.*' => 'exists:users,id',
            'ppk_ids' => 'required|array',
            'ppk_ids.*' => 'exists:users,id',
        ]);
        
        // Check for duplicate PYD assignments
        if (count($request->pyd_ids) !== count(array_unique($request->pyd_ids))) {
            return back()->withErrors(['pyd_ids' => 'PYD tidak boleh diulang'])->withInput();
        }
        
        foreach ($request->pyd_ids as $index => $pydId) {
            $evaluation = Evaluation::create([
                'evaluation_period_id' => $request->evaluation_period_id,
                'pyd_id' => $pydId,
                'ppp_id' => $request->ppp_ids[$index],
                'ppk_id' => $request->ppk_ids[$index],
                'status' => 'draf_pyd',
            ]);
            
            // Find PYD group for criteria selection
            $pydGroup = User::find($pydId)->pydGroup->nama_kumpulan ?? null;
            
            // Get criteria matching PYD group or no group
            $criteria = EvaluationCriteria::where('kumpulan_pyd', $pydGroup)
                ->orWhereNull('kumpulan_pyd')
                ->get();
            
            // Create empty scores for each criterion
            foreach ($criteria as $criterion) {
                EvaluationScore::create([
                    'evaluation_id' => $evaluation->id,
                    'criteria_id' => $criterion->id,
                ]);
            }
        }
        
        return redirect()->route('evaluations.index')
            ->with('success', 'Penilaian prestasi berjaya dicipta.');
    }

    public function edit(Evaluation $evaluation)
    {
        $periods = EvaluationPeriod::all();
        $pyds = User::where('peranan', 'pyd')->get();
        $ppps = User::where('peranan', 'ppp')->get();
        $ppks = User::where('peranan', 'ppk')->get();
        
        return view('evaluations.edit', compact('evaluation', 'periods', 'pyds', 'ppps', 'ppks'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'evaluation_period_id' => 'required|exists:evaluation_periods,id',
            'pyd_id' => 'required|exists:users,id',
            'ppp_id' => 'required|exists:users,id',
            'ppk_id' => 'required|exists:users,id',
        ]);

        $evaluation->update([
            'evaluation_period_id' => $request->evaluation_period_id,
            'pyd_id' => $request->pyd_id,
            'ppp_id' => $request->ppp_id,
            'ppk_id' => $request->ppk_id,
        ]);

        return redirect()->route('evaluations.index')
            ->with('success', 'Penilaian berjaya dikemaskini.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();
        return redirect()->route('evaluations.index')
            ->with('success', 'Penilaian berjaya dipadam.');
    }

    public function show(Evaluation $evaluation, $step = 'I')
    {
        $user = Auth::user();
        
        // Verify user has access to this evaluation
        if (!$user->isSuperAdmin() && !$user->isAdmin() && 
            $user->id !== $evaluation->pyd_id && 
            $user->id !== $evaluation->ppp_id && 
            $user->id !== $evaluation->ppk_id) {
            abort(403);
        }
        
        // Validate step parameter
        $validSteps = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'];
        if (!in_array($step, $validSteps)) {
            $step = 'I';
        }
        
        $editableSections = $evaluation->getEditableSections($user);
        $currentStep = $step;
        $nextStep = $evaluation->getNextStep($currentStep, $user);
        $previousStep = $evaluation->getPreviousStep($currentStep);
        
        // Determine last editable step for submit button
        $lastEditableStep = null;
        if ($user->isPYD()) {
            $lastEditableStep = 'II';
        } elseif ($user->isPPP()) {
            $lastEditableStep = 'VIII';
        } elseif ($user->isPPK()) {
            $lastEditableStep = 'IX';
        }
        
        return view('evaluations.show', compact(
            'evaluation',
            'editableSections',
            'currentStep',
            'nextStep',
            'previousStep',
            'lastEditableStep'
        ));
    }

    public function updateSection(Request $request, Evaluation $evaluation)
    {
        $section = $request->input('section');
        $user = Auth::user();
        $editableSections = $evaluation->getEditableSections($user);
        
        if (!($editableSections[$section] ?? false)) {
            abort(403, 'Anda tidak dibenarkan mengemaskini bahagian ini.');
        }
        
        switch ($section) {
            case 'II':
                $validated = $request->validate([
                    'kegiatan_sumbangan' => 'required|array',
                    'kegiatan_sumbangan.*.kegiatan' => 'required|string',
                    'kegiatan_sumbangan.*.peringkat' => 'required|string',
                    'latihan_dihadiri' => 'required|array',
                    'latihan_dihadiri.*.nama' => 'required|string',
                    'latihan_dihadiri.*.tarikh' => 'required|string',
                    'latihan_dihadiri.*.tempat' => 'required|string',
                    'latihan_diperlukan' => 'required|array',
                    'latihan_diperlukan.*.nama' => 'required|string',
                    'latihan_diperlukan.*.sebab' => 'required|string',
                ]);
                
                $evaluation->update([
                    'kegiatan_sumbangan' => $validated['kegiatan_sumbangan'],
                    'latihan_dihadiri' => $validated['latihan_dihadiri'],
                    'latihan_diperlukan' => $validated['latihan_diperlukan'],
                ]);
                break;
                
            case 'VIII':
                $validated = $request->validate([
                    'tempoh_penilaian_ppp_tahun' => 'required|digits:4',
                    'tempoh_penilaian_ppp_bulan' => 'required|numeric|min:1',
                    'ulasan_keseluruhan_ppp' => 'required|string',
                    'kemajuan_kerjaya_ppp' => 'required|string',
                ]);
                
                $evaluation->update([
                    'tempoh_penilaian_ppp_mula' => now()->setYear($validated['tempoh_penilaian_ppp_tahun'])->startOfYear(),
                    'tempoh_penilaian_ppp_tamat' => now()->setYear($validated['tempoh_penilaian_ppp_tahun'])->startOfYear()->addMonths($validated['tempoh_penilaian_ppp_bulan']),
                    'ulasan_keseluruhan_ppp' => $validated['ulasan_keseluruhan_ppp'],
                    'kemajuan_kerjaya_ppp' => $validated['kemajuan_kerjaya_ppp'],
                ]);
                break;
                
            case 'IX':
                $validated = $request->validate([
                    'tempoh_penilaian_ppk_tahun' => 'required|digits:4',
                    'tempoh_penilaian_ppk_bulan' => 'required|numeric|min:1',
                    'ulasan_keseluruhan_ppk' => 'required|string',
                ]);
                
                $evaluation->update([
                    'tempoh_penilaian_ppk_mula' => now()->setYear($validated['tempoh_penilaian_ppk_tahun'])->startOfYear(),
                    'tempoh_penilaian_ppk_tamat' => now()->setYear($validated['tempoh_penilaian_ppk_tahun'])->startOfYear()->addMonths($validated['tempoh_penilaian_ppk_bulan']),
                    'ulasan_keseluruhan_ppk' => $validated['ulasan_keseluruhan_ppk'],
                ]);
                break;
        }
        
        return redirect()->route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => $section])
            ->with('success', 'Bahagian ' . $section . ' berjaya dikemaskini.');
    }

    public function updateScores(Request $request, Evaluation $evaluation)
    {
        $section = $request->input('section');
        $user = Auth::user();
        $editableSections = $evaluation->getEditableSections($user);
        
        if (!($editableSections[$section] ?? false)) {
            abort(403, 'Anda tidak dibenarkan mengemaskini bahagian ini.');
        }
        
        if ($user->isPPP()) {
            $validated = $request->validate([
                'markah_ppp' => 'required|array',
                'markah_ppp.*' => 'required|integer|min:1|max:10',
            ]);
            
            foreach ($validated['markah_ppp'] as $scoreId => $markah) {
                EvaluationScore::where('id', $scoreId)
                    ->where('evaluation_id', $evaluation->id)
                    ->update(['markah_ppp' => $markah]);
            }
        } elseif ($user->isPPK()) {
            $validated = $request->validate([
                'markah_ppk' => 'required|array',
                'markah_ppk.*' => 'required|integer|min:1|max:10',
            ]);
            
            foreach ($validated['markah_ppk'] as $scoreId => $markah) {
                EvaluationScore::where('id', $scoreId)
                    ->where('evaluation_id', $evaluation->id)
                    ->update(['markah_ppk' => $markah]);
            }
        }
        
        return redirect()->route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => $section])
            ->with('success', 'Markah Bahagian ' . $section . ' berjaya dikemaskini.');
    }

    public function submitPYD(Request $request, Evaluation $evaluation)
    {
        if (!$evaluation->canSubmit(Auth::user()) || !Auth::user()->isPYD()) {
            abort(403);
        }

        // Validate all required fields in Bahagian II
        $request->validate([
            'kegiatan_sumbangan' => 'required|array|min:1',
            'kegiatan_sumbangan.*.kegiatan' => 'required|string',
            'kegiatan_sumbangan.*.peringkat' => 'required|string',
            'latihan_dihadiri' => 'required|array|min:1',
            'latihan_dihadiri.*.nama' => 'required|string',
            'latihan_dihadiri.*.tarikh' => 'required|string',
            'latihan_dihadiri.*.tempat' => 'required|string',
            'latihan_diperlukan' => 'required|array|min:1',
            'latihan_diperlukan.*.nama' => 'required|string',
            'latihan_diperlukan.*.sebab' => 'required|string',
        ]);

        // Convert the arrays to proper format
        $kegiatan_sumbangan = [];
        foreach ($request->kegiatan_sumbangan as $item) {
            $kegiatan_sumbangan[] = [
                'kegiatan' => $item['kegiatan'],
                'peringkat' => $item['peringkat']
            ];
        }

        $latihan_dihadiri = [];
        foreach ($request->latihan_dihadiri as $item) {
            $latihan_dihadiri[] = [
                'nama' => $item['nama'],
                'tarikh' => $item['tarikh'],
                'tempat' => $item['tempat']
            ];
        }

        $latihan_diperlukan = [];
        foreach ($request->latihan_diperlukan as $item) {
            $latihan_diperlukan[] = [
                'nama' => $item['nama'],
                'sebab' => $item['sebab']
            ];
        }

        $evaluation->update([
            'status' => 'draf_ppp',
            'kegiatan_sumbangan' => $kegiatan_sumbangan,
            'latihan_dihadiri' => $latihan_dihadiri,
            'latihan_diperlukan' => $latihan_diperlukan,
        ]);

        return redirect()->route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => 'III'])
            ->with('success', 'Penilaian berjaya diserahkan untuk penilaian PPP.');
    }

    public function submitPPP(Request $request, Evaluation $evaluation)
    {
        if (!$evaluation->canSubmit(Auth::user()) || !Auth::user()->isPPP()) {
            abort(403);
        }
        
        // Validate all required fields
        $request->validate([
            'markah_ppp' => 'required|array',
            'markah_ppp.*' => 'required|integer|min:1|max:10',
            'tempoh_penilaian_ppp_tahun' => 'required|digits:4',
            'tempoh_penilaian_ppp_bulan' => 'required|numeric|min:1',
            'ulasan_keseluruhan_ppp' => 'required|string',
            'kemajuan_kerjaya_ppp' => 'required|string',
        ]);
        
        // Update scores
        foreach ($request->markah_ppp as $scoreId => $markah) {
            EvaluationScore::where('id', $scoreId)
                ->where('evaluation_id', $evaluation->id)
                ->update(['markah_ppp' => $markah]);
        }
        
        $evaluation->update([
            'status' => 'draf_ppk',
            'tempoh_penilaian_ppp_mula' => now()->setYear($request->tempoh_penilaian_ppp_tahun)->startOfYear(),
            'tempoh_penilaian_ppp_tamat' => now()->setYear($request->tempoh_penilaian_ppp_tahun)->startOfYear()->addMonths($request->tempoh_penilaian_ppp_bulan),
            'ulasan_keseluruhan_ppp' => $request->ulasan_keseluruhan_ppp,
            'kemajuan_kerjaya_ppp' => $request->kemajuan_kerjaya_ppp,
        ]);
        
        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Penilaian berjaya diserahkan untuk penilaian PPK.');
    }

    public function submitPPK(Request $request, Evaluation $evaluation)
    {
        if (!$evaluation->canSubmit(Auth::user()) || !Auth::user()->isPPK()) {
            abort(403);
        }
        
        // Validate all required fields
        $request->validate([
            'markah_ppk' => 'required|array',
            'markah_ppk.*' => 'required|integer|min:1|max:10',
            'tempoh_penilaian_ppk_tahun' => 'required|digits:4',
            'tempoh_penilaian_ppk_bulan' => 'required|numeric|min:1',
            'ulasan_keseluruhan_ppk' => 'required|string',
        ]);
        
        // Update scores
        foreach ($request->markah_ppk as $scoreId => $markah) {
            EvaluationScore::where('id', $scoreId)
                ->where('evaluation_id', $evaluation->id)
                ->update(['markah_ppk' => $markah]);
        }
        
        $evaluation->update([
            'status' => 'selesai',
            'tempoh_penilaian_ppk_mula' => now()->setYear($request->tempoh_penilaian_ppk_tahun)->startOfYear(),
            'tempoh_penilaian_ppk_tamat' => now()->setYear($request->tempoh_penilaian_ppk_tahun)->startOfYear()->addMonths($request->tempoh_penilaian_ppk_bulan),
            'ulasan_keseluruhan_ppk' => $request->ulasan_keseluruhan_ppk,
        ]);
        
        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Penilaian berjaya diselesaikan.');
    }

    public function reopen(Request $request, Evaluation $evaluation)
    {
        $this->authorize('reopen', $evaluation);
        
        $evaluation->reopen(Auth::id());
        
        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Penilaian telah dibuka semula untuk pengemaskinian.');
    }

    public function reassign(Request $request, Evaluation $evaluation)
    {
        $this->authorize('reassign', $evaluation);
        
        $validated = $request->validate([
            'ppp_id' => 'required|exists:users,id',
            'ppk_id' => 'required|exists:users,id',
        ]);
        
        $evaluation->update($validated);
        
        return redirect()->route('evaluations.show', $evaluation)
            ->with('success', 'Penilai berjaya dikemaskini.');
    }
}