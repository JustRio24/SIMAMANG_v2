<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar Magang</title>
    <style>
        @page {
        size: legal portrait;
        margin: 2cm;
    }


        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        /* ===== HEADER ===== */
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            min-height: 90px;
        }

        .header img.logo-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
        }

        .header img.logo-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
        }

        .header h2 {
            margin: 0;
            font-size: 18pt;
        }

        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }

        /* ===== META SURAT ===== */
        .letter-number {
            margin: 20px 0 10px 0;
        }

        .letter-number table {
            width: 100%;
            font-size: 12pt;
        }

        .letter-number td:first-child {
            width: 120px;
        }

        /* ===== ISI ===== */
        .content {
            text-align: justify;
            margin-top: 0px;
        }

        table.info {
            width: 100%;
            margin: 15px 0;
        }

        table.info td {
            padding: 4px 0;
            vertical-align: top;
        }

        table.info td:first-child {
            width: 160px;
        }

        /* ===== TANDA TANGAN ===== */
        .signature-section {
            margin-top: -5px;
            width: 260px;
            float: right;
            text-align: center;
        }

        .qr-code {
            margin: 10px 0;
        }

        /* ===== FOOTER ===== */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 6px;
        }
    </style>
</head>
<body>
    @php
    $logoAppPath   = public_path('storage/images/logo_mamang.png');
    $logoPolsriPath = public_path('storage/images/logo_polsri.png');

    $logoAppBase64 = file_exists($logoAppPath)
        ? base64_encode(file_get_contents($logoAppPath))
        : null;

    $logoPolsriBase64 = file_exists($logoPolsriPath)
        ? base64_encode(file_get_contents($logoPolsriPath))
        : null;
    @endphp


    <!-- ===== HEADER ===== -->
    <div class="header">
        @if($logoAppBase64)
        <img
            src="data:image/png;base64,{{ $logoAppBase64 }}"
            class="logo-right"
            alt="Logo Aplikasi"
        >
    @endif

    @if($logoPolsriBase64)
        <img
            src="data:image/png;base64,{{ $logoPolsriBase64 }}"
            class="logo-left"
            alt="Logo POLSRI"
        >
    @endif
        <h2>POLITEKNIK NEGERI SRIWIJAYA</h2>
        <p><strong>DIREKTORAT AKADEMIK DAN KEMAHASISWAAN</strong></p>
        <p>Jl. Srijaya Negara Bukit Besar Palembang 30139</p>
        <p>Telp: (0711) 353414 | Email: info@polsri.ac.id</p>
    </div>

    <!-- ===== NOMOR SURAT ===== -->
    <div class="letter-number">
        <table>
            <tr>
                <td>Nomor</td>
                <td>: {{ $internship->letter_number }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>: 1 (satu) berkas</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>: <strong>Permohonan Izin Magang</strong></td>
            </tr>
        </table>
    </div>

    <!-- ===== TUJUAN ===== -->
    <p>
        Kepada Yth.<br>
        <strong>Pimpinan {{ $internship->company_name }}</strong><br>
        {{ $internship->company_address }}<br>
        {{ $internship->company_city }}
    </p>

    <!-- ===== ISI SURAT ===== -->
    <div class="content">
        <p>Dengan hormat,</p>

        <p>
            Dalam rangka pelaksanaan program magang mahasiswa sebagai bagian dari kurikulum pendidikan
            di Politeknik Negeri Sriwijaya, dengan ini kami mengajukan permohonan izin magang bagi mahasiswa kami:
        </p>

        <table class="info">
            <tr><td>Nama</td><td>: {{ $internship->student->name }}</td></tr>
            <tr><td>NIM</td><td>: {{ $internship->student->nim }}</td></tr>
            <tr><td>Jurusan</td><td>: {{ $internship->student->jurusan }}</td></tr>
            <tr><td>Program Studi</td><td>: {{ $internship->student->prodi }}</td></tr>
            <tr>
                <td>Periode Magang</td>
                <td>: {{ $internship->start_date->translatedFormat('d F Y') }} s/d {{ $internship->end_date->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>
                    : {{ $internship->duration_months }}
                    ({{ \Illuminate\Support\Str::of($internship->duration_months)
                        ->replaceMatches('/1/', 'satu')
                        ->replaceMatches('/2/', 'dua')
                        ->replaceMatches('/3/', 'tiga')
                        ->replaceMatches('/4/', 'empat')
                        ->replaceMatches('/5/', 'lima') }}) bulan
                </td>
            </tr>
        </table>

        <p>
            Kegiatan magang ini bertujuan untuk memberikan pengalaman praktis kepada mahasiswa dalam dunia kerja
            yang sesungguhnya, sehingga dapat meningkatkan kompetensi dan keterampilan yang sesuai dengan bidang studinya.
        </p>

        <p>
            Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.
        </p>
    </div>

    <!-- ===== TANDA TANGAN ===== -->
    <div class="signature-section">
        <p>Palembang, {{ $generated_at->translatedFormat('d F Y') }}</p>
        <p style="margin-top:-10px;"><strong>Wakil Direktur I</strong></p>

        @php
            $wadir1Approval = $internship->approvals->where('role', 'wadir1')->first();
        @endphp

    @if($wadir1Approval && $qrBase64)
    <div class="qr-code" style="position:relative; width:120px; margin:auto;  margin-bottom:-40px ;padding-top:15px;">

        <img
            src="data:image/svg+xml;base64,{{ $qrBase64 }}"
            width="120"
            height="120"
            alt="QR Verifikasi Dokumen"
        >

        <div style="
            position:absolute;
            top:22px;
            left:0;
            right:0;
            background:rgba(255,255,255,0.8);
            font-size:7pt;
            padding:2px;
            text-align:center;
        ">
            Telah disetujui secara elektronik<br>
            {{ $wadir1Approval->approved_at->translatedFormat('d/m/Y H:i:s') }}
        </div>

    </div>
    @endif




        <p style="margin-top:40px; border-top:1px solid #000; padding-top:-15px;">
            <strong>{{ $wadir1Approval->approver->name ?? '[Menunggu Persetujuan]' }}</strong><br>
            NIP. {{ $wadir1Approval->approver->nim ?? '-' }}
        </p>
    </div>

        <!-- ===== FOOTER ===== -->
        <div class="footer">
            Dokumen ini dibuat secara elektronik melalui SIMAMANG (Sistem Manajemen Magang) POLSRI<br>
            Verifikasi dokumen dapat dilakukan dengan memindai QR Code di atas
        </div>

</body>
</html>
