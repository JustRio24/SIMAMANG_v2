<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAMANG</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo_mamang.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #00A19C;
            --secondary-color: #00D4CE;
        }
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 450px;
            width: 100%;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-container img {
            height: 80px;
            width: auto;
            margin-bottom: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #008B87, var(--primary-color));
            transform: translateY(-2px);
        }

        .quick-login-card {
            background: white;
            border-radius: 10px;
            padding: 0.6rem;
            width: 140px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        }

        .quick-login-card button {
            font-size: 0.72rem;
            padding: 4px 6px;
            line-height: 1.2;
        }


    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center gap-3">
        
    <div class="login-card">
        <div class="logo-container">
            <img style="transform: scale(3.5); transform-origin: center; margin-bottom: -20px;" src="{{ asset('storage/images/logo.png') }}" alt="SIMAMANG Logo">
        </div>
        
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>
            
            <div class="text-center">
                <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary-color);">Daftar di sini</a></p>
            </div>
        </form>
    </div>

    <!-- QUICK LOGIN CARD -->
    <div class="quick-login-card">
        <h6 class="fw-bold mb-2 text-center">Quick Login</h6>

        <button class="btn btn-sm btn-outline-primary w-100 mb-1"
            onclick="fillLogin('admin.ts@polsri.ac.id')">
            Admin Jurusan
        </button>

        <button class="btn btn-sm btn-outline-primary w-100 mb-1"
            onclick="fillLogin('kaprodi@polsri.ac.id')">
            Ketua Prodi
        </button>

        <button class="btn btn-sm btn-outline-primary w-100 mb-1"
            onclick="fillLogin('kajur@polsri.ac.id')">
            Ketua Jurusan
        </button>

        <button class="btn btn-sm btn-outline-primary w-100 mb-1"
            onclick="fillLogin('kpa@polsri.ac.id')">
            KPA
        </button>

        <button class="btn btn-sm btn-outline-primary w-100 mb-1"
            onclick="fillLogin('wadir1@polsri.ac.id')">
            Wadir 1
        </button>

        <hr class="my-2">

        <button class="btn btn-sm btn-outline-success w-100"
            onclick="fillLogin('mahasiswa1@mhs.polsri.ac.id')">
            Mahasiswa 1
        </button>
        <button class="btn btn-sm btn-outline-success w-100"
            onclick="fillLogin('mahasiswa2@mhs.polsri.ac.id')">
            Mahasiswa 2
        </button>
        <button class="btn btn-sm btn-outline-success w-100"
            onclick="fillLogin('mahasiswa3@mhs.polsri.ac.id')">
            Mahasiswa 3
        </button>
        <button class="btn btn-sm btn-outline-success w-100"
            onclick="fillLogin('mahasiswa4@mhs.polsri.ac.id')">
            Mahasiswa 4
        </button>
        <button class="btn btn-sm btn-outline-success w-100"
            onclick="fillLogin('mahasiswa5@mhs.polsri.ac.id')">
            Mahasiswa 5
        </button>
    </div>

    </div>

    <script>
        function fillLogin(email) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = 'password123';
        }
    </script>
</body>
</html>