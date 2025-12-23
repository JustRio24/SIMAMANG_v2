<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SIMAMANG</title>
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
            background-color: #fff;
            overflow-x: hidden;
        }

        /* Split Layout Styles */
        .register-container {
            min-height: 100vh;
        }

        .left-panel {
            background: white;
            padding: 2rem 3rem;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel {
            background: linear-gradient(135deg, var(--primary-light) 0%, #dffbf9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed; /* Fixed position agar gambar tetap diam saat form di-scroll */
            right: 0;
            top: 0;
            height: 100%;
            width: 50%; /* Mengambil setengah layar */
            z-index: 1;
        }

        /* Decorative Circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            opacity: 0.1;
        }
        .circle-1 { width: 400px; height: 400px; top: -100px; right: -100px; }
        .circle-2 { width: 250px; height: 250px; bottom: 50px; left: 50px; }

        /* Form Styling */
        .logo-img {
            height: 50px;
            width: auto;
            margin-bottom: 20px;
        }

        .header-text h2 {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .header-text p {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .form-select ~ label {
            color: var(--primary-color);
        }

        .form-control:focus, .form-select:focus {
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
            width: 65%;
            max-width: 600px;
            position: relative;
            z-index: 2;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Section Divider */
        .form-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
            margin-top: 0.5rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        @media (max-width: 991px) {
            .right-panel { display: none; }
            .left-panel { padding: 2rem 1.5rem; }
            .register-container {
                display: block; /* Reset flex styling for mobile */
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <div class="row g-0 register-container">
            
            <div class="col-lg-6 col-md-12 left-panel">
                <div style="max-width: 550px; margin: 0 auto; width: 100%;">
                    <div class="text-center text-lg-start">
                        <img src="{{ asset('storage/images/logo_mamang.png') }}" alt="SIMAMANG" class="logo-img" onerror="this.src='https://placehold.co/150x50?text=SIMAMANG'">
                    </div>
                    
                    <div class="header-text text-center text-lg-start">
                        <h2>Buat Akun Baru</h2>
                        <p>Lengkapi data diri Anda untuk memulai magang di POLSRI.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                            <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="form-section-title">Data Diri</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                                    <label for="name">Nama Lengkap</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM" value="{{ old('nim') }}" required>
                                    <label for="nim">NIM</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                            <label for="email">Alamat Email</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="08xxx" value="{{ old('phone') }}" required>
                            <label for="phone">Nomor WhatsApp/Telepon</label>
                        </div>

                        <div class="form-section-title mt-4">Data Akademik</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="jurusan" name="jurusan" required>
                                        <option value="" selected disabled>Pilih Jurusan</option>
                                        <option value="Teknik Sipil">Teknik Sipil</option>
                                        <option value="Teknik Elektro">Teknik Elektro</option>
                                        <option value="Teknik Mesin">Teknik Mesin</option>
                                        <option value="Akuntansi">Akuntansi</option>
                                        <option value="Administrasi Bisnis">Administrasi Bisnis</option>
                                        <option value="Manajemen Informatika">Manajemen Informatika</option>
                                    </select>
                                    <label for="jurusan">Jurusan</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="prodi" name="prodi" placeholder="Prodi" value="{{ old('prodi') }}" required>
                                    <label for="prodi">Program Studi</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-title mt-4">Keamanan Akun</div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <label for="password">Kata Sandi</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi" required>
                                    <label for="password_confirmation">Ulangi Kata Sandi</label>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-custom w-100 text-white mb-4">
                            Daftar Sekarang <i class="bi bi-person-plus-fill ms-2"></i>
                        </button>
                        
                        <div class="text-center pb-3">
                            <p class="text-muted mb-0">Sudah memiliki akun? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">Masuk disini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-flex right-panel">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
                
                <div class="text-center" style="position: relative; z-index: 2;">
                    <img src="https://img.freepik.com/free-vector/internship-job-concept-illustration_114360-6766.jpg?w=740&t=st=1708768000~exp=1708768600~hmac=abcdef" alt="Register Illustration" class="illustration-img mb-4" style="border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1);">
                    
                    <div class="mt-4 px-5">
                        <h3 class="fw-bold text-dark">Mulai Perjalanan Karirmu</h3>
                        <p class="text-muted mt-2">Bergabung dengan ribuan mahasiswa lainnya untuk<br>pengalaman magang yang terstruktur dan profesional.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>