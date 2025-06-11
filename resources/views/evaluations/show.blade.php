@extends('layouts.app')
@php
    use App\Models\User;
@endphp
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="fw-bold">Penilaian Prestasi</h5>
            <p class="text-muted">PYD: {{ $evaluation->pyd->name }} | Tempoh: {{ $evaluation->evaluationPeriod->tahun }}</p>
        </div>
    </div>

    <!-- Stepper Navigation -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="stepper-wrapper">
                @foreach(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'] as $step)
                    <a href="{{ route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => $step]) }}" 
                    class="stepper-item {{ $currentStep === $step ? 'active' : '' }}">
                        <div class="stepper-icon">
                            {{ $step }}
                            @if($editableSections[$step] ?? false)
                                <span class="stepper-edit-indicator">🔴</span>
                            @endif
                        </div>
                        <div class="stepper-label">Bahagian {{ $step }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Current Section Content -->
    <div class="card">
        <div class="card-body">
            @include("evaluations.sections.{$currentStep}")
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-between mt-4">
        @if($previousStep)
            <a href="{{ route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => $previousStep]) }}" 
            class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Sebelumnya
            </a>
        @else
            <span></span>
        @endif

        @if($evaluation->canSubmit(auth()->user()) && $currentStep === $lastEditableStep)
            <form action="{{ route('evaluations.submit-' . strtolower(auth()->user()->peranan), $evaluation) }}" 
                method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    Hantar Penilaian <i class="fas fa-check ms-1"></i>
                </button>
            </form>
        @endif

        @if($nextStep)
            <a href="{{ route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => $nextStep]) }}" 
            class="btn btn-primary">
                Seterusnya <i class="fas fa-arrow-right ms-1"></i>
            </a>
        @else
            <span></span>
        @endif
    </div>

    <!-- Admin Controls -->
    @can('admin')
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Kawalan Pentadbir</h5>
        </div>
        <div class="card-body">
            <div class="d-flex gap-2">
                @if($evaluation->status === 'selesai')
                    <form action="{{ route('evaluations.reopen', $evaluation) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Buka semula penilaian ini?')">
                            <i class="fas fa-undo me-1"></i> Buka Semula
                        </button>
                    </form>
                @endif

                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#reassignModal">
                    <i class="fas fa-user-edit me-1"></i> Tukar Penilai
                </button>
            </div>
        </div>
    </div>

    <!-- Reassign Modal -->
    <div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('evaluations.reassign', $evaluation) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="reassignModalLabel">Tukar Penilai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="ppp_id" class="form-label">PPP Baru</label>
                            <select class="form-select" id="ppp_id" name="ppp_id" required>
                                @foreach(User::where('peranan', 'ppp')->get() as $user)
                                    <option value="{{ $user->id }}" {{ $evaluation->ppp_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ppk_id" class="form-label">PPK Baru</label>
                            <select class="form-select" id="ppk_id" name="ppk_id" required>
                                @foreach(User::where('peranan', 'ppk')->get() as $user)
                                    <option value="{{ $user->id }}" {{ $evaluation->ppk_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
</div>

<style>
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        overflow-x: auto;
    }
    .stepper-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        text-decoration: none;
        color: #6c757d;
    }
    .stepper-item.active {
        color: #3490dc;
        font-weight: bold;
    }
    .stepper-item::before {
        position: absolute;
        content: "";
        border-bottom: 2px solid #dee2e6;
        width: 100%;
        top: 15px;
        left: -50%;
        z-index: 2;
    }
    .stepper-item:first-child::before {
        content: none;
    }
    .stepper-icon {
        position: relative;
        z-index: 5;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #dee2e6;
        margin-bottom: 6px;
    }
    .stepper-item.active .stepper-icon {
        border-color: #3490dc;
        background-color: #e7f2ff;
    }
    .stepper-edit-indicator {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 12px;
    }
    .stepper-label {
        font-size: 14px;
        text-align: center;
    }
</style>
@endsection