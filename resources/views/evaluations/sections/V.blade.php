@php use Illuminate\Support\Str; @endphp

<div class="section-content">
    <h4 class="mb-4">
        BAHAGIAN V - KUALITI PERIBADI 
        (Wajaran {{ $evaluation->pyd->pydGroup->nama_kumpulan === 'Pegawai Kumpulan Pengurusan dan Professional' ? '20%' : '25%' }})
    </h4>
    
    <form action="{{ route('evaluations.update-scores', $evaluation) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="section" value="V">
        
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="60%">Kriteria</th>
                    <th width="20%">PPP</th>
                    <th width="20%">PPK</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluation->scores->where('criteria.bahagian', 'V') as $score)
                    @php $kriteria = strtoupper($score->criteria->kriteria); @endphp
                    <tr>
                        <td>
                            <strong>{{ $score->criteria->kriteria }}</strong>
                            <p class="mb-0 text-muted small">
                                @if(Str::contains($kriteria, 'MEMIMPIN'))
                                    Mempunyai wawasan, komitmen, kebolehan membuat keputusan, menggerak dan memberi dorongan kepada pegawai ke arah pencapaian objektif organisasi
                                @elseif(Str::contains($kriteria, 'MENGELOLA'))
                                    Keupayaan dan kebolehan menggembleng segala sumber dalam kawalannya seperti kewangan, tenaga manusia, peralatan dan maklumat bagi merancang, mengatur, membahagi dan mengendalikan sesuatu tugas untuk mencapai objektif organisasi
                                @elseif(Str::contains($kriteria, 'DISIPLIN'))
                                    Mempunyai daya kawal diri dari segi mental dan fizikal termasuk mematuhi peraturan, menepati masa, menunaikan janji dan bersifat sabar
                                @elseif(Str::contains($kriteria, 'PROAKTIF') || Str::contains($kriteria, 'INOVATIF'))
                                    Kebolehan menjangka kemungkinan, mencipta dan mengeluarkan idea baru serta membuat pembaharuan bagi mempertingkatkan kualiti dan produktiviti organisasi
                                @elseif(Str::contains($kriteria, 'JALINAN') || Str::contains($kriteria, 'KERJASAMA'))
                                    Kebolehan pegawai dalam mewujudkan suasana kerjasama yang harmoni dan mesra serta boleh menyesuaikan diri dalam semua keadaan
                                @endif
                            </p>
                        </td>
                        <td>
                            @if($editableSections['V'])
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
                            @if($editableSections['V'] && auth()->user()->isPPK())
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
                    <td>
                        {{ $evaluation->calculateTotalScore()['ppp'] * ($evaluation->pyd->pydGroup->nama_kumpulan === 'Pegawai Kumpulan Pengurusan dan Professional' ? 20 : 25) / 100 }}
                        /{{ $evaluation->pyd->pydGroup->nama_kumpulan === 'Pegawai Kumpulan Pengurusan dan Professional' ? 20 : 25 }}
                    </td>
                    <td>
                        {{ $evaluation->calculateTotalScore()['ppk'] * ($evaluation->pyd->pydGroup->nama_kumpulan === 'Pegawai Kumpulan Pengurusan dan Professional' ? 20 : 25) / 100 }}
                        /{{ $evaluation->pyd->pydGroup->nama_kumpulan === 'Pegawai Kumpulan Pengurusan dan Professional' ? 20 : 25 }}
                    </td>
                </tr>
            </tbody>
        </table>
        
        @if($editableSections['V'])
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
        </div>
        @endif
    </form>
</div>
