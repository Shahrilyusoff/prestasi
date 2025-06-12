<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_period_id',
        'pyd_id',
        'ppp_id',
        'ppk_id',
        'kegiatan_sumbangan',
        'latihan_dihadiri',
        'latihan_diperlukan',
        'tempoh_penilaian_ppp_mula',
        'tempoh_penilaian_ppp_tamat',
        'ulasan_keseluruhan_ppp',
        'kemajuan_kerjaya_ppp',
        'tempoh_penilaian_ppk_mula',
        'tempoh_penilaian_ppk_tamat',
        'ulasan_keseluruhan_ppk',
        'status',
        'reopened_at',
        'reopened_by'
    ];

    protected $casts = [
        'kegiatan_sumbangan' => 'array',
        'latihan_dihadiri' => 'array',
        'latihan_diperlukan' => 'array',
        'reopened_at' => 'datetime',
    ];

    public function evaluationPeriod()
    {
        return $this->belongsTo(EvaluationPeriod::class);
    }

    public function pyd()
    {
        return $this->belongsTo(User::class, 'pyd_id');
    }

    public function ppp()
    {
        return $this->belongsTo(User::class, 'ppp_id');
    }

    public function ppk()
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }

    public function skt()
    {
        return $this->hasOne(Skt::class, 'evaluation_period_id', 'evaluation_period_id')
            ->where('pyd_id', $this->pyd_id);
    }

    public function reopenedBy()
    {
        return $this->belongsTo(User::class, 'reopened_by');
    }

    public function calculateTotalScore()
    {
        $totalPPP = 0;
        $totalPPK = 0;

        foreach ($this->scores as $score) {
            $totalPPP += $score->markah_ppp * $score->criteria->wajaran / 100;
            $totalPPK += $score->markah_ppk * $score->criteria->wajaran / 100;
        }

        // Add Bahagian VI marks (5% weight)
        $bahagianVI = $this->scores->where('criteria.bahagian', 'VI')->first();
        if ($bahagianVI) {
            $totalPPP += $bahagianVI->markah_ppp * 5 / 100;
            $totalPPK += $bahagianVI->markah_ppk * 5 / 100;
        }

        return [
            'ppp' => $totalPPP,
            'ppk' => $totalPPK,
            'purata' => ($totalPPP + $totalPPK) / 2
        ];
    }

    public function getEditableSections($user)
    {
        $sections = [
            'I' => false,
            'II' => false,
            'III' => false,
            'IV' => false,
            'V' => false,
            'VI' => false,
            'VII' => false,
            'VIII' => false,
            'IX' => false,
        ];

        if ($user->isPYD() && in_array($this->status, ['draf_pyd', 'reopened'])) {
            $sections['II'] = true;
        }

        if ($user->isPPP() && in_array($this->status, ['draf_ppp', 'reopened'])) {
            $sections['III'] = true;
            $sections['IV'] = true;
            $sections['V'] = true;
            $sections['VI'] = true;
            $sections['VIII'] = true;
        }

        if ($user->isPPK() && in_array($this->status, ['draf_ppk', 'reopened'])) {
            $sections['III'] = true;
            $sections['IV'] = true;
            $sections['V'] = true;
            $sections['VI'] = true;
            $sections['IX'] = true;
        }

        return $sections;
    }

    public function getNextStep($currentStep, $user)
    {
        $steps = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'];
        $currentIndex = array_search($currentStep, $steps);
        
        if ($currentIndex !== false && isset($steps[$currentIndex + 1])) {
            return $steps[$currentIndex + 1];
        }
        
        return null;
    }

    public function getPreviousStep($currentStep)
    {
        $steps = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'];
        $currentIndex = array_search($currentStep, $steps);
        
        if ($currentIndex !== false && $currentIndex > 0) {
            return $steps[$currentIndex - 1];
        }
        
        return null;
    }

    public function canSubmit($user)
    {
        if ($user->isPYD() && in_array($this->status, ['draf_pyd', 'reopened'])) {
            return true;
        }

        if ($user->isPPP() && in_array($this->status, ['draf_ppp', 'reopened'])) {
            return true;
        }

        if ($user->isPPK() && in_array($this->status, ['draf_ppk', 'reopened'])) {
            return true;
        }

        return false;
    }

    public function reopen($userId)
    {
        $this->update([
            'status' => 'reopened',
            'reopened_at' => now(),
            'reopened_by' => $userId
        ]);
    }
}