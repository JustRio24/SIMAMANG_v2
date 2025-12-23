<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMAMANG') - Sistem Manajemen Magang POLSRI</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo_mamang.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #00A19C;
            --primary-dark: #007a76;
            --primary-light: #e6f6f5; /* Warna background untuk item aktif */
            --text-dark: #2c3e50;
            --text-muted: #95a5a6;
            --bg-body: #f8f9fa;
            --navbar-height: 80px;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            padding-top: var(--navbar-height); /* Space for fixed navbar */
            color: var(--text-dark);
        }
        
        /* NAVBAR STYLING */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            height: var(--navbar-height);
            border-bottom: 3px solid var(--primary-color); /* Identity Line */
        }
        
        .navbar-brand img {
            height: 45px;
            width: auto;
            transition: transform 0.3s;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .brand-text {
            line-height: 1.2;
        }
        
        /* Navigation Links */
        .nav-link {
            color: #555 !important;
            font-weight: 500;
            padding: 0.6rem 1.2rem !important;
            margin: 0 0.2rem;
            border-radius: 50px; /* Pill shape */
            transition: all 0.3s ease;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(0, 161, 156, 0.05);
        }
        
        /* Active State Design */
        .nav-link.active {
            background-color: var(--primary-light);
            color: var(--primary-dark) !important;
            font-weight: 600;
        }

        .nav-link.active i {
            color: var(--primary-color);
        }
        
        /* User Dropdown */
        .user-info-btn {
            background: transparent;
            border: 1px solid #eee;
            padding: 5px 15px 5px 5px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .user-info-btn:hover {
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-color: var(--primary-light);
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            margin-top: 15px;
            padding: 1rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 8px 12px;
        }

        .dropdown-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        /* MAIN CONTENT */
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - var(--navbar-height));
        }

        /* ALERTS CUSTOMIZATION */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
        }
        
        .alert-success { background-color: #d1e7dd; color: #0f5132; }
        .alert-danger { background-color: #f8d7da; color: #842029; }
        .alert-warning { background-color: #fff3cd; color: #664d03; }

        /* FLOATING CHATBOT */
        .chatbot-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.6rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0, 161, 156, 0.4);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 1000;
        }
        
        .chatbot-float:hover {
            transform: scale(1.1) rotate(10deg);
        }

        /* Pulse Animation for Chatbot */
        .chatbot-float::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: var(--primary-color);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.7;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.7; }
            70% { transform: scale(1.5); opacity: 0; }
            100% { transform: scale(1); opacity: 0; }
        }
        
        @media (max-width: 991px) {
            .navbar-collapse {
                background: white;
                padding: 1rem;
                border-radius: 0 0 15px 15px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.05);
                border-top: 1px solid #eee;
            }
            .nav-link { margin-bottom: 5px; }
            .user-info-btn { border: none; padding-left: 0; }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <img src="{{ asset('storage/images/logo_mamang.png') }}" alt="Logo" onerror="this.src='https://placehold.co/50x50?text=SM'">
                <div class="d-flex flex-column brand-text">
                    <span class="fw-bold" style="color: var(--primary-color); font-size: 1.2rem;">SIMAMANG</span>
                    <span class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Sistem Manajemen Magang</span>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                            <i class="bi bi-newspaper"></i> Berita
                        </a>
                    </li>

                    @if(auth()->user()->isMahasiswa())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('internships.*') ? 'active' : '' }}" href="{{ route('internships.index') }}">
                                <i class="bi bi-send"></i> Pengajuan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('templates.*') ? 'active' : '' }}" href="{{ route('templates.index') }}">
                                <i class="bi bi-file-earmark-text"></i> Template
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('internships.*') ? 'active' : '' }}" href="{{ route('internships.index') }}">
                                <i class="bi bi-inbox"></i> Data Pengajuan
                            </a>
                        </li>
                        @if(auth()->user()->role === 'admin_jurusan')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('templates.*') ? 'active' : '' }}" href="{{ route('templates.index') }}">
                                <i class="bi bi-gear"></i> Kelola Template
                            </a>
                        </li>
                        @endif
                    @endif
                </ul>
                
                <div class="d-flex align-items-end gap-3">
                    <div class="dropdown">
                        <button class="user-info-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            {{-- <div class="d-none d-lg-block text-start me-2">
                                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1;">{{ Str::limit(auth()->user()->name, 15) }}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}</small>
                            </div> --}}
                            <i class="bi bi-chevron-down text-muted" style="font-size: 0.8rem;"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end animate slideIn">
                            <li class="px-3 py-2 border-bottom mb-2">
                                <span class="d-block fw-bold">{{ auth()->user()->name }}</span>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </li>
                            {{-- <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear me-2"></i> Pengaturan
                                </a>
                            </li> --}}
                            {{-- <li><hr class="dropdown-divider"></li> --}}
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="main-content">
        <div class="container px-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <a href="{{ route('chatbot.index') }}" class="chatbot-float" title="Bantuan MAMANG">
        <i class="bi bi-robot" style=" transform: scale(0.8);"></i>
    </a>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>