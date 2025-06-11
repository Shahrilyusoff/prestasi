<div class="section-content">
    <h4 class="mb-4">BAHAGIAN II - KEGIATAN DAN SUMBANGAN DI LUAR TUGAS RASMI/LATIHAN</h4>
    
    <form action="{{ route('evaluations.update-section', $evaluation) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="section" value="II">
        
        <div class="mb-4">
            <h5>1. KEGIATAN DAN SUMBANGAN DI LUAR TUGAS RASMI</h5>
            <p class="text-muted">Senaraikan kegiatan dan sumbangan di luar tugas rasmi seperti sukan/pertubuhan/sumbangan kreatif di peringkat Komuniti/Jabatan/Daerah/Negeri/Negara/Antarabangsa yang berfaedah kepada Organisasi/Komuniti/Negara pada tahun yang dinilai.</p>
            
            <table class="table table-bordered" id="kegiatan-table">
                <thead>
                    <tr>
                        <th width="50%">Kegiatan/Aktiviti/Sumbangan</th>
                        <th>Peringkat (Nyatakan jawatan atau pencapaian)</th>
                        <th width="50px">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($evaluation->kegiatan_sumbangan ?? [['kegiatan' => '', 'peringkat' => '']]) as $index => $kegiatan)
                    <tr>
                        <td>
                            <input type="text" name="kegiatan_sumbangan[{{ $index }}][kegiatan]" 
                                   class="form-control" value="{{ $kegiatan['kegiatan'] ?? '' }}" required>
                        </td>
                        <td>
                            <input type="text" name="kegiatan_sumbangan[{{ $index }}][peringkat]" 
                                   class="form-control" value="{{ $kegiatan['peringkat'] ?? '' }}" required>
                        </td>
                        <td class="text-center">
                            @if($index === 0)
                                <button type="button" class="btn btn-sm btn-success add-row" data-table="kegiatan-table">
                                    <i class="fas fa-plus"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                    <i class="fas fa-minus"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mb-4">
            <h5>2. LATIHAN</h5>
            <p class="text-muted">Senaraikan program latihan (seminar, kursus, bengkel dan lain-lain) yang dihadiri dalam tahun yang dinilai.</p>
            
            <div class="mb-3">
                <h6>i) Latihan Dihadiri</h6>
                <table class="table table-bordered" id="latihan-table">
                    <thead>
                        <tr>
                            <th>Nama Latihan (Nyatakan sijil jika ada)</th>
                            <th width="20%">Tarikh/Tempoh</th>
                            <th width="20%">Tempat</th>
                            <th width="50px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($evaluation->latihan_dihadiri ?? [['nama' => '', 'tarikh' => '', 'tempat' => '']]) as $index => $latihan)
                        <tr>
                            <td>
                                <input type="text" name="latihan_dihadiri[{{ $index }}][nama]" 
                                       class="form-control" value="{{ $latihan['nama'] ?? '' }}" required>
                            </td>
                            <td>
                                <input type="text" name="latihan_dihadiri[{{ $index }}][tarikh]" 
                                       class="form-control" value="{{ $latihan['tarikh'] ?? '' }}" required>
                            </td>
                            <td>
                                <input type="text" name="latihan_dihadiri[{{ $index }}][tempat]" 
                                       class="form-control" value="{{ $latihan['tempat'] ?? '' }}" required>
                            </td>
                            <td class="text-center">
                                @if($index === 0)
                                    <button type="button" class="btn btn-sm btn-success add-row" data-table="latihan-table">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mb-3">
                <h6>ii) Latihan Diperlukan</h6>
                <table class="table table-bordered" id="latihan-diperlukan-table">
                    <thead>
                        <tr>
                            <th>Nama/Bidang Latihan</th>
                            <th>Sebab Diperlukan</th>
                            <th width="50px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($evaluation->latihan_diperlukan ?? [['nama' => '', 'sebab' => '']]) as $index => $latihan)
                        <tr>
                            <td>
                                <input type="text" name="latihan_diperlukan[{{ $index }}][nama]" 
                                       class="form-control" value="{{ $latihan['nama'] ?? '' }}" required>
                            </td>
                            <td>
                                <input type="text" name="latihan_diperlukan[{{ $index }}][sebab]" 
                                       class="form-control" value="{{ $latihan['sebab'] ?? '' }}" required>
                            </td>
                            <td class="text-center">
                                @if($index === 0)
                                    <button type="button" class="btn btn-sm btn-success add-row" data-table="latihan-diperlukan-table">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="pengesahan" required>
            <label class="form-check-label" for="pengesahan">
                Saya mengesahkan bahawa semua kenyataan di atas adalah benar
            </label>
        </div>
        
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Simpan Bahagian II</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add row functionality
    document.querySelectorAll('.add-row').forEach(button => {
        button.addEventListener('click', function() {
            const tableId = this.getAttribute('data-table');
            const table = document.getElementById(tableId);
            const tbody = table.querySelector('tbody');
            const lastRow = tbody.lastElementChild;
            const newRow = lastRow.cloneNode(true);
            
            // Clear input values
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';
            });
            
            // Change plus to minus button
            const addButton = newRow.querySelector('.add-row');
            if (addButton) {
                addButton.classList.remove('btn-success', 'add-row');
                addButton.classList.add('btn-danger', 'remove-row');
                addButton.innerHTML = '<i class="fas fa-minus"></i>';
                
                // Add remove event
                addButton.addEventListener('click', function() {
                    this.closest('tr').remove();
                });
            }
            
            tbody.appendChild(newRow);
        });
    });
    
    // Remove row functionality
    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('tr').remove();
        });
    });
});
</script>