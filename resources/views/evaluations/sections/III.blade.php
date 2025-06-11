@php use Illuminate\Support\Str; @endphp

<div class="section-content">
    <h4 class="mb-4">BAHAGIAN III - PENGHASILAN KERJA (Wajaran 50%)</h4>
    
    <p class="text-muted mb-4">Pegawai Penilai dikehendaki memberikan penilaian berdasarkan pencapaian kerja sebenar PYD berbanding dengan SKT yang ditetapkan. Penilaian hendaklah berasaskan kepada penjelasan setiap kriteria yang dinyatakan di bawah dengan menggunakan skala 1 hingga 10:</p>
    
    <form action="{{ route('evaluations.update-scores', $evaluation) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="section" value="III">
        
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="60%">Kriteria (Dinilai berasaskan SKT)</th>
                    <th width="20%">PPP</th>
                    <th width="20%">PPK</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluation->scores->where('criteria.bahagian', 'III') as $score)
                @php
                    $kriteria = strtoupper($score->criteria->kriteria);
                @endphp
                <tr>
                    <td>
                        <strong>{{ $score->criteria->kriteria }}</strong>
                        <p class="mb-0 text-muted small">
                            @if(Str::contains($kriteria, 'KUANTITI'))
                                Kuantiti hasil kerja seperti jumlah, bilangan, kadar, kekerapan dan sebagainya berbanding dengan sasaran kuantiti kerja yang diterapkan
                            @elseif(Str::contains($kriteria, 'KUALITI'))
                                1. Dinilai dari segi kesempurnaan, teratur dan kemas.<br>
                                2. Dinilai dari segi usaha dan inisiatif untuk mencapai kesempurnaan hasil kerja
                            @elseif(Str::contains($kriteria, 'KETEPATAN MASA'))
                                Kebolehan menghasilkan kerja atau melaksanakan tugas dalam tempoh masa yang ditetapkan
                            @elseif(Str::contains($kriteria, 'KEBERKESANAN'))
                                Dinilai dari segi memenuhi kehendak 'stake-holder' atau pelanggan
                            @endif
                        </p>
                    </td>
                    <td>
                        @if($editableSections['III'])
                            <select class="form-select" name="markah_ppp[{{ $score->id }}]" required>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $score->markah_ppp == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        @else
                            {{ $score->markah_ppp ?? '-' }}
                        @endif
                    </td>
                    <td>
                        @if($editableSections['III'] && auth()->user()->isPPK())
                            <select class="form-select" name="markah_ppk[{{ $score->id }}]" required>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $score->markah_ppk == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        @else
                            {{ $score->markah_ppk ?? '-' }}
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td><strong>Jumlah markah mengikut wajaran</strong></td>
                    <td>{{ $evaluation->calculateTotalScore()['ppp'] * 50 / 100 }} /50</td>
                    <td>{{ $evaluation->calculateTotalScore()['ppk'] * 50 / 100 }} /50</td>
                </tr>
            </tbody>
        </table>
        
        @if($editableSections['III'])
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
        </div>
        @endif
    </form>
</div>
