<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIMAMANG</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo_mamang.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #00A19C;
            --primary-dark: #007a76;
            --primary-light: #e6f6f5;
            --text-dark: #2D3436;
            --text-muted: #636E72;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            overflow: hidden; /* Mencegah scroll pada layout split */
            background-color: #fff;
        }

        /* Split Layout Styles */
        .login-container {
            height: 100vh;
        }

        .left-panel {
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 4rem;
            position: relative;
            z-index: 2;
        }

        .right-panel {
            background: linear-gradient(135deg, var(--primary-light) 0%, #dffbf9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative Circles in Right Panel */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            opacity: 0.1;
        }
        .circle-1 { width: 300px; height: 300px; top: -50px; right: -50px; }
        .circle-2 { width: 200px; height: 200px; bottom: 50px; left: 50px; }

        /* Form Styling */
        .logo-img {
            height: 60px;
            width: auto;
            margin-bottom: 30px;
        }

        .welcome-text h2 {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .welcome-text p {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--primary-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 161, 156, 0.25);
        }

        .btn-primary-custom {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 161, 156, 0.3);
            background: linear-gradient(45deg, var(--primary-dark), var(--primary-color));
        }

        .illustration-img {
            width: 55%;
            max-width: 600px;
            position: relative;
            z-index: 2;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Quick Login Floating Panel */
        .quick-login-trigger {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
        }

        .quick-login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 15px;
            padding: 15px;
            width: 250px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(0.9);
            opacity: 0;
            pointer-events: none;
            position: absolute;
            bottom: 50px;
            left: 0;
            transform-origin: bottom left;
        }

        .quick-login-trigger:hover .quick-login-card,
        .quick-login-card:hover {
            transform: scale(1);
            opacity: 1;
            pointer-events: all;
        }

        .btn-trigger-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--text-dark);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-trigger-icon:hover {
            transform: rotate(90deg);
        }

        .ql-btn {
            font-size: 0.8rem;
            text-align: left;
            margin-bottom: 5px;
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .ql-btn i { font-size: 0.9rem; }

        @media (max-width: 991px) {
            .right-panel { display: none; }
            .left-panel { padding: 2rem; }
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 login-container">
            <div class="col-lg-5 col-md-12 left-panel">
                <div style="max-width: 450px; margin: 0 auto; width: 100%;">
                    <div class="text-center text-lg-start">
                        <img src="{{ asset('storage/images/logo_mamang.png') }}" alt="SIMAMANG" class="logo-img" onerror="this.src='https://placehold.co/150x50?text=SIMAMANG'">
                    </div>
                    
                    <div class="welcome-text text-center text-lg-start">
                        <h2>Selamat Datang!</h2>
                        <p>Silakan masuk untuk mengakses akun Anda.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                            <label for="email">Alamat Email</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Kata Sandi</label>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label text-muted" style="font-size: 0.9rem;" for="remember">Ingat Saya</label>
                            </div>
                            <a href="#" class="text-decoration-none" style="color: var(--primary-color); font-size: 0.9rem; font-weight: 500;">Lupa Password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom w-100 text-white mb-4">
                            Masuk Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                        
                        <div class="text-center">
                            <p class="text-muted mb-0">Belum memiliki akun? 
                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">Daftar disini</a>
                            </p>
                        </div>
                    </form>
                </div>
                
                <div class="mt-5 text-center text-muted" style="font-size: 0.8rem;">
                    &copy; 2025 SIMAMANG POLSRI. All rights reserved.
                </div>
            </div>

            <div class="col-lg-7 d-none d-lg-flex right-panel">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
                
                <div class="text-center" style="position: relative; z-index: 2;">
                    <img src="{{ asset('storage/images/login.jpg') }}" alt="Login Illustration" class="illustration-img mb-4" style="border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1);">
                    
                    <div class="mt-4 px-5">
                        <h3 class="fw-bold text-dark">Sistem Manajemen Magang Terintegrasi</h3>
                        <p class="text-muted mt-2">Kelola pengajuan, laporan, dan penilaian magang dengan mudah,<br>cepat, dan transparan dalam satu pintu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="quick-login-trigger">
        <div class="btn-trigger-icon" title="Quick Login (Dev)">
            <i class="bi bi-terminal"></i>
        </div>
        
        <div class="quick-login-card">
            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Access</h6>
            
            <div style="max-height: 300px; overflow-y: auto;">
                <small class="text-uppercase text-muted fw-bold d-block mb-2" style="font-size: 0.65rem;">Administrasi</small>
                <button class="btn btn-sm btn-outline-dark w-100 ql-btn mb-1" onclick="fillLogin('admin.ts@polsri.ac.id')">
                    Admin Jurusan <i class="bi bi-arrow-right-short"></i>
                </button>
                <button class="btn btn-sm btn-outline-dark w-100 ql-btn mb-1" onclick="fillLogin('kaprodi@polsri.ac.id')">
                    Kaprodi <i class="bi bi-arrow-right-short"></i>
                </button>
                <button class="btn btn-sm btn-outline-dark w-100 ql-btn mb-1" onclick="fillLogin('kajur@polsri.ac.id')">
                    Kajur <i class="bi bi-arrow-right-short"></i>
                </button>
                <button class="btn btn-sm btn-outline-dark w-100 ql-btn mb-1" onclick="fillLogin('kpa@polsri.ac.id')">
                    KPA <i class="bi bi-arrow-right-short"></i>
                </button>
                <button class="btn btn-sm btn-outline-dark w-100 ql-btn mb-1" onclick="fillLogin('wadir1@polsri.ac.id')">
                    Wadir 1 <i class="bi bi-arrow-right-short"></i>
                </button>

                <small class="text-uppercase text-muted fw-bold d-block mb-2 mt-3" style="font-size: 0.65rem;">Mahasiswa</small>
                <button class="btn btn-sm btn-outline-primary w-100 ql-btn mb-1" onclick="fillLogin('mahasiswa1@mhs.polsri.ac.id')">
                    Mahasiswa 1 <i class="bi bi-person"></i>
                </button>
                <button class="btn btn-sm btn-outline-primary w-100 ql-btn mb-1" onclick="fillLogin('mahasiswa2@mhs.polsri.ac.id')">
                    Mahasiswa 2 <i class="bi bi-person"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillLogin(email) {
            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');
            
            // Fill values
            emailInput.value = email;
            passInput.value = 'password123';
            
            // Add visual feedback (flash effect)
            emailInput.style.backgroundColor = '#e6f6f5';
            passInput.style.backgroundColor = '#e6f6f5';
            
            setTimeout(() => {
                emailInput.style.backgroundColor = '';
                passInput.style.backgroundColor = '';
                emailInput.focus();
            }, 300);
        }
    </script>
</body>
</html>