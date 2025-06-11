<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Penilaian Prestasi') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="topbar navbar navbar-expand navbar-dark bg-primary">
            <div class="container-fluid">
                <!-- Toggle sidebar button -->
                <button class="sidebar-toggle btn btn-link text-white me-3" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Brand/logo -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/logoikma.png') }}" alt="IkMa Logo" class="topbar-logo me-2">
                    <span class="d-none d-md-inline">
                        <span class="fw-bold">SISTEM PENILAIAN PRESTASI</span>
                        <small class="d-block">KAKITANGAN KONTRAK</small>
                    </span>
                </a>

                <!-- Right side items -->
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger badge-notification">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Notifikasi</h6>
                            <a class="dropdown-item" href="#">Anda mempunyai 3 penilaian baru</a>
                            <a class="dropdown-item" href="#">1 SKT memerlukan pengesahan</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Lihat semua notifikasi</a>
                        </div>
                    </li>

                    <!-- User dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i> Profil
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Tetapan
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Log Keluar
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        @auth
        <div class="sidebar">
            <div class="sidebar-header">
                <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                <small class="text-muted">{{ ucfirst(Auth::user()->peranan) }}</small>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                
                @can('admin')
                <li class="menu-header">Pentadbiran</li>
                <li>
                    <a href="{{ route('evaluation-periods.index') }}">
                        <i class="fas fa-calendar-alt"></i> Tempoh Penilaian
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> Pengguna
                    </a>
                </li>
                @endcan
                
                <li class="menu-header">Penilaian</li>
                <li>
                    <a href="{{ route('skt.index') }}">
                        <i class="fas fa-tasks"></i> SKT
                    </a>
                </li>
                <li>
                    <a href="{{ route('evaluations.index') }}">
                        <i class="fas fa-clipboard-check"></i> Penilaian Prestasi
                    </a>
                </li>
                
                @can('admin')
                <li>
                    <a href="{{ route('reports.index') }}">
                        <i class="fas fa-file-pdf"></i> Laporan
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        @endauth

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>