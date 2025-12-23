<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar Jurusan</title>
    <style>
        @page { size: a4 portrait; margin: 2cm; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
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

        .letter-number { margin: 20px 0 10px 0; }
        .letter-number table { width: 100%; }

        .content { text-align: justify; }

        table.info {
            width: 100%;
            margin: 15px 0;
        }

        table.info td {
            padding: 4px 0;
            vertical-align: top;
        }

        table.info td:first-child { width: 160px; }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            width: 45%;
            text-align: center;
        }

        .qr-code {
            margin: 10px auto;
            position: relative;
            width: 120px;
        }

        .footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            text-align: center;
            font-size: 9pt;
            border-top: 1px solid #ccc;
            padding-top: 6px;
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

<p style="text-align:right;">Palembang, {{ $generated_at->translatedFormat('d F Y') }}</p>

<div class="letter-number">
    <table>
        <tr>
            <td>Nomor</td>
            <td>: {{ $internship->id }}/{{ $generated_at->month }}/MEMO/{{ $generated_at->year }}</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>: Permohonan Surat Pengantar Magang</td>
        </tr>
    </table>
</div>

<p>
    Kepada Yth.<br>
    <strong>Wakil Direktur I</strong><br>
    Politeknik Negeri Sriwijaya<br>
    di Palembang
</p>

<div class="content">
    <p>Dengan hormat,</p>

    <p>
        Bersama ini kami sampaikan bahwa mahasiswa berikut telah memenuhi
        persyaratan untuk melaksanakan kegiatan magang:
    </p>

    <table class="info">
        <tr><td>Nama</td><td>: {{ $internship->student->name }}</td></tr>
        <tr><td>NIM</td><td>: {{ $internship->student->nim }}</td></tr>
        <tr><td>Program Studi</td><td>: {{ $internship->student->prodi }}</td></tr>
        <tr><td>Instansi</td><td>: {{ $internship->company_name }}</td></tr>
        <tr><td>Periode</td>
            <td>: {{ $internship->start_date->translatedFormat('d F Y') }}
                s/d {{ $internship->end_date->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <p>Demikian disampaikan, atas perhatian Bapak kami ucapkan terima kasih.</p>
</div>

<table width="100%" style="margin-top:-20px; text-align:center;">
    <tr>
        <!-- KAPRODI -->
        <td width="65%">
            {{-- <p><strong>Mengetahui</strong><br>
               <strong>Ketua Program Studi</strong></p>

            @if(isset($qrKaprodi))
                <img src="data:image/svg+xml;base64,{{ $qrKaprodi }}" width="120"><br>
            @endif

            <p style="margin-top:5px;">
                <strong>{{ $signatures['kaprodi']['name'] ?? '' }}</strong><br>
                NIP. {{ $signatures['kaprodi']['nip'] ?? '-' }}
            </p> --}}
        </td>

        <!-- KAJUR -->
        <td width="35%">
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

<div class="footer">
    Dokumen ini dibuat secara elektronik melalui SIMAMANG<br>
    Verifikasi dapat dilakukan melalui QR Code
</div>

</body>
</html>
