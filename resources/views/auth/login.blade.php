<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAMANG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
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
        .logo {
            font-size: 3rem;
            color: #1e40af;
            margin-bottom: 1rem;
        }
        .shortcut-btn {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <i class="bi bi-briefcase logo"></i>
        <h2 class="fw-bold">SIMAMANG</h2>
        <p class="text-muted">Sistem Manajemen Magang POLSRI</p>
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
            <input type="email" id="email" name="email" class="form-control"
                   value="{{ old('email') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" id="password" name="password"
                   class="form-control" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </button>
    </form>

    <!-- ================= SHORTCUT LOGIN ================= -->
    <hr>

    <div class="mb-3">
        <p class="fw-semibold mb-2 text-center">⚡ Login Cepat (Demo)</p>

        <div class="d-grid gap-2">
            <button class="btn btn-outline-secondary shortcut-btn"
                onclick="fillLogin('admin.ts@polsri.ac.id')">Admin Jurusan</button>

            <button class="btn btn-outline-secondary shortcut-btn"
                onclick="fillLogin('sekjur@polsri.ac.id')">Sekretaris Jurusan</button>

            <button class="btn btn-outline-secondary shortcut-btn"
                onclick="fillLogin('kajur@polsri.ac.id')">Ketua Jurusan</button>

            <button class="btn btn-outline-secondary shortcut-btn"
                onclick="fillLogin('kpa@polsri.ac.id')">KPA</button>

            <button class="btn btn-outline-secondary shortcut-btn"
                onclick="fillLogin('wadir1@polsri.ac.id')">Wakil Direktur 1</button>

            <button class="btn btn-outline-secondary shortcut-btn"
                onclick="fillLogin('mahasiswa1@mhs.polsri.ac.id')">Mahasiswa</button>
        </div>

        <small class="text-muted d-block mt-2 text-center">
            Password default: <strong>password123</strong>
        </small>
    </div>
    <!-- ================================================== -->

    <div class="text-center mt-3">
        <p class="mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    </div>
</div>

<script>
    function fillLogin(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password123';
    }
</script>

</body>
</html>
