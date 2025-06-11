<div class="section-content">
    <h4 class="mb-4">BAHAGIAN VI - KEGIATAN DAN SUMBANGAN DI LUAR TUGAS RASMI (Wajaran 5%)</h4>
    
    <p class="text-muted mb-4">Berasaskan maklumat di Bahagian II perenggan 1, Pegawai Penilai dikehendaki memberi penilaian dengan menggunakan skala 1 hingga 10. Tiada sebarang markah boleh diberikan (kosong) jika PYD tidak mencatat kegiatan atau sumbangannya.</p>
    
    <form action="{{ route('evaluations.update-scores', $evaluation) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="section" value="VI">
        
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="60%">Peringkat</th>
                    <th width="20%">PPP</th>
                    <th width="20%">PPK</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $score = $evaluation->scores->where('criteria.bahagian', 'VI')->first();
                @endphp
                <tr>
                    <td>
                        <strong>Kegiatan dan Sumbangan di Luar Tugas Rasmi</strong>
                        <p class="mb-0 text-muted small">
                            Berdasarkan aktiviti yang disenaraikan di Bahagian II
                        </p>
                    </td>
                    <td>
                        @if($editableSections['VI'])
                            <select class="form-select" name="markah_ppp[{{ $score->id }}]" required>
                                <option value="">Pilih Markah</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $score->markah_ppp == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        @else
                            {{ $score->markah_ppp ?? '-' }}
                        @endif
                    </td>
                    <td>
                        @if($editableSections['VI'] && auth()->user()->isPPK())
                            <select class="form-select" name="markah_ppk[{{ $score->id }}]" required>
                                <option value="">Pilih Markah</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $score->markah_ppk == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        @else
                            {{ $score->markah_ppk ?? '-' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Jumlah markah mengikut wajaran</strong></td>
                    <td>{{ $score->markah_ppp ? ($score->markah_ppp * 5 / 100) : 0 }} /5</td>
                    <td>{{ $score->markah_ppk ? ($score->markah_ppk * 5 / 100) : 0 }} /5</td>
                </tr>
            </tbody>
        </table>
        
        @if($editableSections['VI'])
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
        </div>
        @endif
    </form>
</div>