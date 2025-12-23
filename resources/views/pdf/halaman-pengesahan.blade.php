<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Halaman Pengesahan Proposal Magang</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
        }

        .header {
            position: relative;
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            min-height: 90px;
        }

        .header img.logo-left {
            position: absolute;
            top: 0; left: 0; width: 80px;
        }

        .header img.logo-right {
            position: absolute;
            top: 0; right: 0; width: 80px;
        }

        .header h2 { margin: 0; font-size: 16pt; font-weight: bold; }
        .header p { margin: 2px 0; font-size: 10pt; }

        /* ===== HEADER / JUDUL ===== */
        .title-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .title-section h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .title-section p {
            margin: 4px 0;
        }

        /* ===== ISI ===== */
        .content {
            margin-top: 20px;
            text-align: justify;
        }

        table.info {
            width: 100%;
            margin: 25px 0;
            border-collapse: collapse;
        }

        table.info td {
            padding: 8px;
            border: 1px solid #000;
        }

        table.info td:first-child {
            width: 35%;
            font-weight: bold;
        }

        /* ===== TANDA TANGAN ===== */
        .signature-section {
            margin-top: 60px;
        }

        .signature-table {
            width: 100%;
            text-align: center;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
        }

        .signature-name {
            margin-top: 5px;
            border-top: 1px solid #000;
            padding-top: 5px;
            display: inline-block;
            min-width: 220px;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 50px;
            font-size: 9pt;
            text-align: center;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    @php
    $logoPolsriPath = public_path('storage/images/logo_polsri.png');
    $logoAppPath    = public_path('storage/images/logo_mamang.png');

    $logoPolsri = file_exists($logoPolsriPath) ? base64_encode(file_get_contents($logoPolsriPath)) : null;
    $logoApp    = file_exists($logoAppPath) ? base64_encode(file_get_contents($logoAppPath)) : null;
@endphp

<div class="header">
    @if($logoPolsri)
        <img src="data:image/png;base64,{{ $logoPolsri }}" class="logo-left">
    @endif
    @if($logoApp)
        <img src="data:image/png;base64,{{ $logoApp }}" class="logo-right">
    @endif

    <h2>POLITEKNIK NEGERI SRIWIJAYA</h2>
    <p><strong>JURUSAN MANAJEMEN INFORMATIKA</strong></p>
    <p>Jalan Sungai Sahang Bukit Besar Palembang 30139</p>
    <p>Telp: (0711) 353420 | Email: mi@polsri.ac.id</p>
</div>

    <!-- ===== ISI ===== -->
    <div class="content">
        <p>
            Proposal magang/kerja praktik ini telah diperiksa dan disetujui sebagai
            salah satu persyaratan untuk melaksanakan kegiatan magang/kerja praktik
            bagi mahasiswa:
        </p>

        <table class="info">
            <tr>
                <td>Nama Mahasiswa</td>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>{{ $student->nim }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>D-IV Manajemen Informatika</td>
            </tr>
            <tr>
                <td>Judul Magang</td>
                <td>{{ $internship->internship_description ?? 'Magang di ' . $internship->company_name }}</td>
            </tr>
            <tr>
                <td>Perusahaan / Instansi</td>
                <td>{{ $internship->company_name }}</td>
            </tr>
            <tr>
                <td>Periode Magang</td>
                <td>
                    {{ $internship->start_date->translatedFormat('d F Y') }}
                    s/d
                    {{ $internship->end_date->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>{{ $internship->duration_months }} bulan</td>
            </tr>
        </table>

        <p>
            Dengan demikian, proposal magang/kerja praktik ini dinyatakan
            <strong>disetujui</strong> dan dapat digunakan sebagai dasar pelaksanaan
            kegiatan magang/kerja praktik.
        </p>
    </div>

    <table width="100%" style="margin-top:-20px; text-align:center;">
        <tr>
            <!-- KAPRODI -->
            <td width="50%">
                <p><strong>Mengetahui</strong><br>
                   <strong>Ketua Program Studi</strong></p>
    
                @if(isset($qrKaprodi))
                    <img src="data:image/svg+xml;base64,{{ $qrKaprodi }}" width="120"><br>
                @endif
    
                <p style="margin-top:5px;">
                    <strong>{{ $signatures['kaprodi']['name'] ?? '' }}</strong><br>
                    NIP. {{ $signatures['kaprodi']['nip'] ?? '-' }}
                </p>
            </td>
    
            <!-- KAJUR -->
            <td width="50%">
                <p><strong>Menyetujui</strong><br>
                   <strong>Ketua Jurusan</strong></p>
    
                @if(isset($qrKajur))
                    <img src="data:image/svg+xml;base64,{{ $qrKajur }}" width="100"><br>
                @endif
    
                <p style="margin-top:5px;">
                    <strong>{{ $signatures['kajur']['name'] ?? '' }}</strong><br>
                    NIP. {{ $signatures['kajur']['nip'] ?? '-' }}
                </p>
            </td>
        </tr>
    </table>

    <!-- ===== FOOTER ===== -->
    {{-- <div class="footer">
        Dokumen ini dibuat dan ditandatangani secara elektronik melalui<br>
        <strong>SIMAMANG (Sistem Manajemen Magang) POLSRI</strong><br>
        Keabsahan dokumen dapat diverifikasi melalui QR Code tanda tangan di atas
    </div> --}}

</body>
</html>
