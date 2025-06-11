@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="fw-bold">Dashboard Pentadbir</h5>
        </div>
    </div>

    <div class="row">
        <!-- Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tempoh Penilaian Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $activePeriods->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                SKT Menunggu Pengesahan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $submittedSkts }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Penilaian Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $completedEvaluations }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pengguna Sistem</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalUsers }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tempoh Penilaian Aktif</h6>
                </div>
                <div class="card-body">
                    @if($activePeriods->count() > 0)
                        <div class="list-group">
                            @foreach($activePeriods as $period)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $period->tahun }} - {{ $period->jenis === 'skt' ? 'SKT' : 'Penilaian' }}</h6>
                                        <small>{{ $period->active_period ? ucfirst($period->active_period) : 'Tidak Aktif' }}</small>
                                    </div>
                                    <small class="text-muted">
                                        @if($period->jenis === 'skt')
                                            Awal: {{ $period->tarikh_mula_awal->format('d/m/Y') }} - {{ $period->tarikh_tamat_awal->format('d/m/Y') }}<br>
                                            Akhir: {{ $period->tarikh_mula_akhir->format('d/m/Y') }} - {{ $period->tarikh_tamat_akhir->format('d/m/Y') }}
                                        @else
                                            {{ $period->tarikh_mula_penilaian->format('d/m/Y') }} - {{ $period->tarikh_tamat_penilaian->format('d/m/Y') }}
                                        @endif
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tiada tempoh penilaian aktif.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aktiviti Terkini</h6>
                </div>
                <div class="card-body">
                    @if(count($recentActivities) > 0)
                        <div class="list-group">
                            @foreach($recentActivities as $activity)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $activity->description }}</h6>
                                        <small>{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    <small class="text-muted">Oleh: {{ $activity->causer->name ?? 'Sistem' }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tiada aktiviti terkini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection