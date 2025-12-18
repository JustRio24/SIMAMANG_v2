<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMAMANG') - Sistem Manajemen Magang POLSRI</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo_mamang.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #00A19C;
            --primary-dark: #008B87;
            --primary-light: #4DC4C0;
            --secondary-color: #00D4CE;
            --success-color: #00A19C;
            --danger-color: #dc2626;
            --warning-color: #d97706;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            padding-top: 76px;
        }
        
        .navbar-custom {
            background: linear-gradient(360deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-height: 76px;
        }
        
        .navbar-brand img {
            height: 50px;
            width: auto;
        }
        
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            background-color: rgba(255,255,255,0.15);
            color: white !important;
        }
        
        .navbar-custom .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 12px;
            margin-top: 0.5rem;
        }
        
        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 76px);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 2px solid #f1f5f9;
            padding: 1.25rem;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,161,156,0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--primary-color);
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -1.7rem;
            top: 12px;
            width: 2px;
            height: calc(100% - 12px);
            background-color: #e2e8f0;
        }
        
        .timeline-item:last-child::after {
            display: none;
        }
        
        .chatbot-float {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,161,156,0.4);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .chatbot-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,161,156,0.5);
        }
        
        .clock-widget {
            background: rgba(255,255,255,0.15);
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(255,255,255,0.1);
                padding: 1rem;
                border-radius: 12px;
                margin-top: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img style="; transform-origin: center; margin-left: -5px;" src="{{ asset('storage/images/logo_mamang.png') }}" alt="SIMAMANG Logo">
            </a> 
            <div class="d-flex flex-column left-0">
                <p class="navbar-text text-white fw-bold mb-0">SIMAMANG</p>
                <p class="navbar-text text-white mb-0" style="font-size: 0.9rem; opacity: 0.9; margin-top: -18px;">
                    Sistem Manajemen Magang POLSRI
                </p>
            </div>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border-color: rgba(255,255,255,0.3);">
                <i class="bi bi-list text-white" style="font-size: 1.5rem;"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    
                    @if(auth()->user()->isMahasiswa())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('internships.*') ? 'active' : '' }}" href="{{ route('internships.index') }}">
                                <i class="bi bi-file-earmark-text"></i> Pengajuan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('templates.*') ? 'active' : '' }}" href="{{ route('templates.index') }}">
                                <i class="bi bi-file-earmark-arrow-down"></i> Template
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('internships.*') ? 'active' : '' }}" href="{{ route('internships.index') }}">
                                <i class="bi bi-folder"></i> Daftar Pengajuan
                            </a>
                        </li>
                        @if(auth()->user()->role === 'admin_jurusan')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('templates.*') ? 'active' : '' }}" href="{{ route('templates.index') }}">
                                <i class="bi bi-files"></i> Kelola Template
                            </a>
                        </li>
                        @endif
                    @endif
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    {{-- <!-- Real-time Clock -->
                    <div class="clock-widget">
                        <i class="bi bi-clock"></i>
                        <span id="realTimeClock">--:--:--</span>
                        <small id="realTimeDate" style="opacity: 0.9;">--/--/----</small>
                    </div>
                     --}}
                    <!-- User Info -->
                    <div class="dropdown">
                        <button class="btn user-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            {{-- <div class="text-start d-none d-lg-block">
                                <div style="font-size: 0.95rem;">{{ auth()->user()->name }}</div>
                                <small style="opacity: 0.8;">{{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}</small>
                            </div> --}}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text">
                                    <strong>{{ auth()->user()->name }}</strong><br>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- Chatbot Float Button -->
    <a href="{{ route('chatbot.index') }}" class="chatbot-float" title="Chat dengan MAMANG">
        <i class="bi bi-robot"></i>
    </a>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Real-time Clock
        function updateClock() {
            const now = new Date();
            
            // Format time (WIB)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds} WIB`;
            
            // Format date
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const dayName = days[now.getDay()];
            const date = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const dateString = `${dayName}, ${date} ${month} ${year}`;
            
            document.getElementById('realTimeClock').textContent = timeString;
            document.getElementById('realTimeDate').textContent = dateString;
        }
        
        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
    </script>
    @stack('scripts')
</body>
</html>