<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMAMANG') - Sistem Manajemen Magang POLSRI</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #7c3aed;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #d97706;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            padding-top: 70px;
        }
        
        .navbar-main {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.4rem;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s;
            margin: 0 0.15rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white !important;
            background-color: rgba(255,255,255,0.15);
        }
        
        .nav-link i {
            margin-right: 0.4rem;
        }
        
        .main-content {
            padding: 1.5rem;
            min-height: calc(100vh - 70px);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 2px solid #f1f5f9;
            padding: 1.25rem;
            font-weight: 600;
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: all 0.3s;
            z-index: 1000;
            animation: pulse 2s infinite;
        }
        
        .chatbot-float:hover {
            transform: scale(1.1);
            color: white;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(30, 64, 175, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(30, 64, 175, 0); }
            100% { box-shadow: 0 0 0 0 rgba(30, 64, 175, 0); }
        }
        
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .btn {
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        
        @media (max-width: 991.98px) {
            .main-content {
                padding: 1rem;
            }
            
            .navbar-collapse {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
                padding: 1rem;
                border-radius: 0 0 12px 12px;
                margin-top: 0.5rem;
            }
            
            .nav-link {
                padding: 0.75rem 1rem !important;
                margin: 0.25rem 0;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding-top: 60px;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .chatbot-float {
                width: 50px;
                height: 50px;
                bottom: 1rem;
                right: 1rem;
                font-size: 1.25rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-main fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-briefcase-fill"></i> SIMAMANG
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    
                    @if(auth()->user()->isMahasiswa())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('internships.index') ? 'active' : '' }}" href="{{ route('internships.index') }}">
                                <i class="bi bi-file-earmark-text"></i> Pengajuan Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('internships.create') ? 'active' : '' }}" href="{{ route('internships.create') }}">
                                <i class="bi bi-plus-circle"></i> Ajukan Magang
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('internships.*') ? 'active' : '' }}" href="{{ route('internships.index') }}">
                                <i class="bi bi-folder"></i> Daftar Pengajuan
                            </a>
                        </li>
                    @endif
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('chatbot.*') ? 'active' : '' }}" href="{{ route('chatbot.index') }}">
                            <i class="bi bi-chat-dots"></i> MAMANG Chat
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text">
                                    <small class="text-muted">Role:</small><br>
                                    <strong>{{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}</strong>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="main-content">
        <div class="container-fluid">
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
    
    <a href="{{ route('chatbot.index') }}" class="chatbot-float" title="Chat dengan MAMANG">
        <i class="bi bi-robot"></i>
    </a>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
