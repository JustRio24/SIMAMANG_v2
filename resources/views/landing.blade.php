<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAMANG - Sistem Manajemen Magang POLSRI</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo_mamang.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #00A19C;
            --primary-dark: #007a76;
            --primary-light: #e6f6f5;
            --accent-color: #FFD166;
            --text-dark: #2D3436;
            --text-muted: #636E72;
            --bg-light: #F9FBFD;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background-color: var(--bg-light);
            overflow-x: hidden;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand img {
            height: 50px;
            width: auto;
        }
        
        .navbar-custom .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 10px;
            position: relative;
        }
        
        .navbar-custom .nav-link:hover, .navbar-custom .nav-link.active {
            color: var(--primary-color) !important;
        }

        .navbar-custom .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transition: width 0.3s;
        }

        .navbar-custom .nav-link:hover::after {
            width: 100%;
        }

        .btn-nav-login {
            background-color: var(--primary-color);
            color: white !important;
            border-radius: 50px;
            padding: 8px 25px !important;
            transition: transform 0.2s;
        }

        .btn-nav-login:hover {
            transform: translateY(-2px);
            background-color: var(--primary-dark);
            color: white !important;
        }
        
        /* Hero Section */
        .hero {
            padding: 100px 0 80px;
            background: linear-gradient(135deg, #f8fcfc 0%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0,161,156,0.1) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero p {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .hero-img {
            width: 100%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        /* Buttons */
        .btn-custom {
            padding: 12px 35px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary-custom {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            box-shadow: 0 10px 20px rgba(0, 161, 156, 0.2);
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(0, 161, 156, 0.3);
            color: white;
        }
        
        .btn-outline-custom {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline-custom:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            color: var(--primary-dark);
        }
        
        /* Sections General */
        .section {
            padding: 100px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 70px;
            position: relative;
        }
        
        .section-title span {
            color: var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 10px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Features */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: var(--primary-light);
            z-index: -1;
            transition: height 0.4s ease;
            border-radius: 20px;
        }

        .feature-card:hover::before {
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: transparent;
        }
        
        .feature-icon-wrapper {
            width: 70px;
            height: 70px;
            background: var(--primary-light);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            color: var(--primary-color);
            font-size: 2rem;
            transition: all 0.3s;
        }

        .feature-card:hover .feature-icon-wrapper {
            background: var(--primary-color);
            color: white;
            transform: rotateY(360deg);
        }
        
        .feature-card h3 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        /* Comparison Table */
        .comparison-section {
            background: #ffffff;
            position: relative;
        }

        .table-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #eee;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
        }

        .comparison-table th {
            padding: 25px;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .comparison-table th.feature-col {
            text-align: left;
            width: 30%;
            background: #fcfcfc;
        }

        .comparison-table th.conventional-col {
            width: 35%;
            color: var(--text-muted);
            background: #fcfcfc;
        }

        .comparison-table th.simamang-col {
            width: 35%;
            background: var(--primary-color);
            color: white;
            position: relative;
        }

        .comparison-table td {
            padding: 20px 25px;
            border-bottom: 1px solid #f0f0f0;
            text-align: center;
            color: var(--text-muted);
        }

        .comparison-table td:first-child {
            text-align: left;
            font-weight: 500;
            color: var(--text-dark);
        }

        .comparison-table td.simamang-cell {
            background: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
        }

        .comparison-table tr:last-child td {
            border-bottom: none;
        }

        /* Stats Counter */
        .stats-section {
            background: var(--primary-dark);
            color: white;
            padding: 60px 0;
            margin-top: -50px;
            margin-bottom: 50px;
            border-radius: 20px;
            position: relative;
            z-index: 10;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-item p {
            opacity: 0.8;
            margin: 0;
        }

        /* Advantages */
        .advantage-card {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 15px;
            transition: all 0.3s;
        }

        .advantage-card:hover {
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .adv-icon {
            width: 50px;
            height: 50px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .advantage-img {
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .advantage-img:hover {
            transform: scale(1.02);
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            padding: 80px 0;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta-bg-circle {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        /* Footer */
        .footer {
            background-color: #1e2022;
            color: #b0b8bc;
            padding: 70px 0 30px;
        }

        .footer h5 {
            color: white;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #b0b8bc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .social-links a {
            display: inline-flex;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            color: white;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .hero { padding: 120px 0 60px; text-align: center; }
            .hero-img { margin-top: 50px; max-width: 80%; }
            .hero h1 { font-size: 2.5rem; }
            .stats-section { text-align: center; margin-top: 0; }
            .stat-item { margin-bottom: 30px; }
            .comparison-table { font-size: 0.9rem; }
            .comparison-table th, .comparison-table td { padding: 15px 10px; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('storage/images/logo.png') }}" style="transform: scale(2.5); transform-origin: center;" alt="SIMAMANG" onerror="this.src='https://placehold.co/150x50?text=SIMAMANG&font=poppins'">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#comparison">Perbandingan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#advantages">Keuntungan</a></li>
                    <li class="nav-item ms-lg-3">
                        @auth
                            <a class="btn btn-nav-login" href="{{ route('dashboard') }}">Dashboard <i class="bi bi-speedometer2 ms-1"></i></a>
                        @else
                            <a class="nav-link d-inline-block me-2" href="{{ route('login') }}">Masuk</a>
                            <a class="btn btn-nav-login" href="{{ route('register') }}">Daftar Sekarang</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content" data-aos="fade-right">
                    <span class="badge bg-light text-primary mb-3 px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-stars me-1"></i> Official Platform POLSRI</span>
                    <h1>Revolusi Digital<br>Manajemen Magang</h1>
                    <p>Tinggalkan tumpukan kertas. SIMAMANG menyederhanakan proses pengajuan, monitoring, hingga pelaporan magang dalam satu platform terintegrasi, cepat, dan transparan.</p>
                    <div class="d-flex flex-wrap gap-3 justify-content-lg-start justify-content-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-custom btn-primary-custom">
                                Buka Dashboard <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-custom btn-primary-custom">
                                Daftar Akun <i class="bi bi-person-plus"></i>
                            </a>
                            <a href="#features" class="btn-custom btn-outline-custom">
                                Pelajari Fitur
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-delay="200">
                    <img src="{{ asset('storage/images/intership.png') }}" alt="Digital Internship" class="hero-img">
                </div>
            </div>
        </div>
    </section>

    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="stats-section shadow-lg" data-aos="fade-up" data-aos-offset="-50">
                    <div class="row text-center">
                        <div class="col-md-4 mb-4 mb-md-0 stat-item border-end border-light border-opacity-25">
                            <h3><i class="bi bi-people"></i> 1000+</h3>
                            <p>Mahasiswa Aktif</p>
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0 stat-item border-end border-light border-opacity-25">
                            <h3><i class="bi bi-building"></i> 50+</h3>
                            <p>Mitra Industri</p>
                        </div>
                        <div class="col-md-4 stat-item">
                            <h3><i class="bi bi-file-earmark-check"></i> 5000+</h3>
                            <p>Dokumen Terproses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <section class="section" id="features">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <span>Fitur Unggulan</span>
                <h2>Ekosistem Magang Cerdas</h2>
                <p>Kami merancang fitur yang menjawab kebutuhan mahasiswa, dosen, dan administrasi.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <h3>Paperless Submission</h3>
                        <p class="text-muted">Ajukan proposal dan laporan magang sepenuhnya online. Hemat kertas, ramah lingkungan, dan data tersimpan abadi di cloud.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h3>Smart Workflow</h3>
                        <p class="text-muted">Alur persetujuan otomatis dari Kaprodi hingga Kajur. Status dokumen terpantau real-time tanpa perlu bertanya manual.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h3>E-Signature & QR</h3>
                        <p class="text-muted">Validasi dokumen menggunakan Tanda Tangan Digital dan QR Code yang aman dan terverifikasi oleh sistem.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-robot"></i>
                        </div>
                        <h3>AI Assistant</h3>
                        <p class="text-muted">Bingung soal prosedur? Chatbot MAMANG siap menjawab pertanyaan Anda 24/7 dengan respon instan dan akurat.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <h3>Live Reporting</h3>
                        <p class="text-muted">Dashboard analitik lengkap untuk Prodi memantau persebaran tempat magang dan progress mahasiswa secara visual.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-bell"></i>
                        </div>
                        <h3>Instant Notification</h3>
                        <p class="text-muted">Terima notifikasi via WhatsApp/Email setiap kali ada update status pada pengajuan Anda. Tidak ada lagi info terlewat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section comparison-section" id="comparison">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <span>Perbandingan</span>
                <h2>Transformasi Proses</h2>
                <p>Lihat bagaimana SIMAMANG mengubah cara lama menjadi lebih efisien.</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="zoom-in">
                    <div class="table-wrapper">
                        <table class="comparison-table">
                            <thead>
                                <tr>
                                    <th class="feature-col">Fitur / Proses</th>
                                    <th class="conventional-col">Cara Konvensional</th>
                                    <th class="simamang-col">
                                        <i class="bi bi-star-fill text-warning me-2"></i>SIMAMANG
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Pengajuan Berkas</td>
                                    <td>Fisik (Print out), Antar manual</td>
                                    <td class="simamang-cell">Upload Digital (PDF)</td>
                                </tr>
                                <tr>
                                    <td>Durasi Approval</td>
                                    <td>1 - 2 Minggu (Tergantung Pejabat)</td>
                                    <td class="simamang-cell">Hitungan Jam (Notifikasi Realtime)</td>
                                </tr>
                                <tr>
                                    <td>Penyimpanan Data</td>
                                    <td>Lemari Arsip (Rawan Hilang)</td>
                                    <td class="simamang-cell">Cloud Database (Aman & Terpusat)</td>
                                </tr>
                                <tr>
                                    <td>Validasi</td>
                                    <td>Tanda Tangan Basah</td>
                                    <td class="simamang-cell">QR Code & Digital Signature</td>
                                </tr>
                                <tr>
                                    <td>Monitoring Status</td>
                                    <td>Tanya Admin/Dosen berulang kali</td>
                                    <td class="simamang-cell">Cek Dashboard kapan saja</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="advantages" style="background-color: #fbfbfb;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                    <img src="{{ asset('storage/images/adv.jpg') }}" alt="Keuntungan Sistem" class="advantage-img">
                </div>
                <div class="col-lg-7 ps-lg-5">
                    <div class="section-title text-start mb-4">
                        <span class="text-start">Keuntungan</span>
                        <h2 class="text-start">Solusi Menyeluruh untuk Civitas Akademika</h2>
                    </div>

                    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4" id="mhs-tab" data-bs-toggle="pill" data-bs-target="#pills-mhs" type="button" role="tab">Mahasiswa</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4" id="kampus-tab" data-bs-toggle="pill" data-bs-target="#pills-kampus" type="button" role="tab">Prodi & Admin</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-mhs" role="tabpanel">
                            <div class="advantage-card" data-aos="fade-up" data-aos-delay="100">
                                <div class="adv-icon"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <h5>Hemat Waktu & Biaya</h5>
                                    <p class="text-muted mb-0">Tidak perlu bolak-balik kampus hanya untuk minta tanda tangan atau menyerahkan berkas.</p>
                                </div>
                            </div>
                            <div class="advantage-card" data-aos="fade-up" data-aos-delay="200">
                                <div class="adv-icon"><i class="bi bi-phone"></i></div>
                                <div>
                                    <h5>Akses Dari Mana Saja</h5>
                                    <p class="text-muted mb-0">Platform berbasis web yang responsif, bisa diakses lewat HP, Tablet, atau Laptop.</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-kampus" role="tabpanel">
                            <div class="advantage-card">
                                <div class="adv-icon"><i class="bi bi-folder-check"></i></div>
                                <div>
                                    <h5>Arsip Terorganisir</h5>
                                    <p class="text-muted mb-0">Semua dokumen mahasiswa tersimpan rapi berdasarkan tahun akademik dan prodi.</p>
                                </div>
                            </div>
                            <div class="advantage-card">
                                <div class="adv-icon"><i class="bi bi-shield-lock"></i></div>
                                <div>
                                    <h5>Keamanan Data</h5>
                                    <p class="text-muted mb-0">Mencegah pemalsuan dokumen magang dengan sistem verifikasi berlapis.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-bg-circle" style="width: 300px; height: 300px; top: -100px; left: -50px;"></div>
        <div class="cta-bg-circle" style="width: 200px; height: 200px; bottom: -50px; right: 50px;"></div>
        
        <div class="container position-relative" style="z-index: 2;" data-aos="zoom-in">
            <h2 class="mb-3">Siap Memulai Magang Anda?</h2>
            <p class="mb-4 opacity-75">Bergabunglah dengan ribuan mahasiswa POLSRI yang telah merasakan kemudahan administrasi digital.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg rounded-pill px-5 text-primary fw-bold">
                    Masuk ke Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5 text-primary fw-bold me-2 mb-2">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-bold mb-2">
                    Masuk Akun
                </a>
            @endauth
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <img src="{{ asset('storage/images/logo_mamang.png') }}" alt="SIMAMANG" style="height: 40px; margin-bottom: 20px; filter: brightness(0) invert(1);" onerror="this.style.display='none'">
                    <h5 class="text-white d-inline-block ms-2">SIMAMANG</h5>
                    <p class="small">Sistem Informasi Manajemen Magang Politeknik Negeri Sriwijaya. Solusi digital untuk masa depan pendidikan vokasi.</p>
                    <div class="social-links mt-3">
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h5>Navigasi</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#comparison">Perbandingan</a></li>
                        <li><a href="#">Panduan</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h5>Tautan</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="https://polsri.ac.id">Website POLSRI</a></li>
                        <li><a href="#">CDC POLSRI</a></li>
                        <li><a href="#">E-Learning</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled footer-links">
                        <li><i class="bi bi-geo-alt me-2"></i> Jl. Srijaya Negara Bukit Besar, Palembang</li>
                        <li><i class="bi bi-envelope me-2"></i> helpdesk@polsri.ac.id</li>
                        <li><i class="bi bi-telephone me-2"></i> (0711) 353414</li>
                    </ul>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 30px 0;">
            <div class="text-center small">
                <p>&copy; 2025 SIMAMANG POLSRI. Developed with <i class="bi bi-heart-fill text-danger"></i> by RIO 5MID.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS Animation
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar-custom').style.padding = '10px 0';
                document.querySelector('.navbar-custom').style.boxShadow = '0 5px 20px rgba(0,0,0,0.1)';
            } else {
                document.querySelector('.navbar-custom').style.padding = '15px 0';
                document.querySelector('.navbar-custom').style.boxShadow = 'none';
            }
        });
    </script>
</body>
</html>