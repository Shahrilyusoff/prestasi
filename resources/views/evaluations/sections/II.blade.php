@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Penilaian Prestasi</h2>
            <p class="text-muted">PYD: {{ $evaluation->pyd->name }} | Tempoh: {{ $evaluation->evaluationPeriod->tahun }}</p>
        </div>
    </div>

    <!-- Stepper Navigation -->
    @include('evaluations.stepper')

    <!-- Current Section Content -->
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">BAHAGIAN II - KEGIATAN DAN SUMBANGAN DI LUAR TUGAS RASMI/LATIHAN</h4>
            
            <form id="evaluation-form" action="{{ route('evaluations.submit-pyd', $evaluation) }}" method="POST">
                @csrf
                
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
                            @forelse(($evaluation->kegiatan_sumbangan ?? [['kegiatan' => '', 'peringkat' => '']]) as $index => $kegiatan)
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
                            @empty
                            <tr>
                                <td>
                                    <input type="text" name="kegiatan_sumbangan[0][kegiatan]" class="form-control" required>
                                </td>
                                <td>
                                    <input type="text" name="kegiatan_sumbangan[0][peringkat]" class="form-control" required>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-success add-row" data-table="kegiatan-table">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforelse
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
                                @forelse(($evaluation->latihan_dihadiri ?? [['nama' => '', 'tarikh' => '', 'tempat' => '']]) as $index => $latihan)
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
                                @empty
                                <tr>
                                    <td>
                                        <input type="text" name="latihan_dihadiri[0][nama]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="latihan_dihadiri[0][tarikh]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="latihan_dihadiri[0][tempat]" class="form-control" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-success add-row" data-table="latihan-table">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
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
                                @forelse(($evaluation->latihan_diperlukan ?? [['nama' => '', 'sebab' => '']]) as $index => $latihan)
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
                                @empty
                                <tr>
                                    <td>
                                        <input type="text" name="latihan_diperlukan[0][nama]" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="latihan_diperlukan[0][sebab]" class="form-control" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-success add-row" data-table="latihan-diperlukan-table">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
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
                    <button type="submit" class="btn btn-success">
                        Hantar Penilaian <i class="fas fa-check ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => 'I']) }}" 
           class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Sebelumnya
        </a>
        
        @if($nextStep)
        <a href="{{ route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => $nextStep]) }}" 
           class="btn btn-primary">
            Seterusnya <i class="fas fa-arrow-right ms-1"></i>
        </a>
        @endif
    </div>
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
            
            // Update index in name attributes
            const newIndex = tbody.querySelectorAll('tr').length;
            newRow.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${newIndex}]`));
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
            const row = this.closest('tr');
            if (row && row.parentElement.querySelectorAll('tr').length > 1) {
                row.remove();
            }
        });
    });

    // Form submission handling
    const form = document.getElementById('evaluation-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validate at least one row in each table
            const kegiatanRows = document.querySelectorAll('#kegiatan-table tbody tr').length;
            const latihanRows = document.querySelectorAll('#latihan-table tbody tr').length;
            const diperlukanRows = document.querySelectorAll('#latihan-diperlukan-table tbody tr').length;
            
            if (kegiatanRows === 0 || latihanRows === 0 || diperlukanRows === 0) {
                e.preventDefault();
                alert('Sila isi sekurang-kurangnya satu baris untuk setiap bahagian.');
                return false;
            }
            
            // Validate checkbox
            const pengesahan = document.getElementById('pengesahan');
            if (!pengesahan.checked) {
                e.preventDefault();
                alert('Sila sahkan bahawa semua kenyataan adalah benar.');
                return false;
            }
            
            return true;
        });
    }
});
</script>
@endsection