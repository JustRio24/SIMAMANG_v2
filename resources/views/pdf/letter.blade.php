<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar Magang</title>
    <style>
        @page {
            margin: 2cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }
        
        .header h2 {
            margin: 5px 0;
            font-size: 18pt;
        }
        
        .header p {
            margin: 3px 0;
            font-size: 10pt;
        }
        
        .letter-number {
            margin: 20px 0;
        }
        
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        
        .content p {
            margin-bottom: 15px;
        }
        
        table.info {
            width: 100%;
            margin: 15px 0;
        }
        
        table.info td {
            padding: 5px;
            vertical-align: top;
        }
        
        table.info td:first-child {
            width: 150px;
        }
        
        .signature-section {
            margin-top: 50px;
            float: right;
            width: 250px;
            text-align: center;
        }
        
        .qr-code {
            margin: 10px 0;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>POLITEKNIK NEGERI SRIWIJAYA</h2>
        <p><strong>DIREKTORAT AKADEMIK DAN KEMAHASISWAAN</strong></p>
        <p>Jl. Srijaya Negara Bukit Besar Palembang 30139</p>
        <p>Telp: (0711) 353414 | Email: info@polsri.ac.id</p>
    </div>
    
    <div class="letter-number">
        <table style="width: 100%;">
            <tr>
                <td style="width: 100px;">Nomor</td>
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
    
    <div style="margin: 20px 0;">
        <p>Kepada Yth.<br>
        <strong>Pimpinan {{ $internship->company_name }}</strong><br>
        {{ $internship->company_address }}<br>
        {{ $internship->company_city }}</p>
    </div>
    
    <div class="content">
        <p>Dengan hormat,</p>
        
        <p>Dalam rangka pelaksanaan program magang mahasiswa sebagai bagian dari kurikulum pendidikan di Politeknik Negeri Sriwijaya, dengan ini kami mengajukan permohonan izin magang bagi mahasiswa kami:</p>
        
        <table class="info">
            <tr>
                <td>Nama</td>
                <td>: {{ $internship->student->name }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>: {{ $internship->student->nim }}</td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>: {{ $internship->student->jurusan }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>: {{ $internship->student->prodi }}</td>
            </tr>
            <tr>
                <td>Periode Magang</td>
                <td>: {{ $internship->start_date->format('d F Y') }} s/d {{ $internship->end_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>: {{ $internship->duration_months }} ({{ $internship->duration_months == 1 ? 'satu' : ($internship->duration_months == 2 ? 'dua' : 'tiga') }}) bulan</td>
            </tr>
        </table>
        
        <p>Kegiatan magang ini bertujuan untuk memberikan pengalaman praktis kepada mahasiswa dalam dunia kerja yang sesungguhnya, sehingga dapat meningkatkan kompetensi dan keterampilan yang sesuai dengan bidang studinya.</p>
        
        <p>Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>
    
    <div class="signature-section">
        <p>Palembang, {{ $generated_at->format('d F Y') }}</p>
        <p><strong>Wakil Direktur I</strong><br>
        <strong>Bidang Akademik dan Kemahasiswaan</strong></p>
        
        @php
            $wadir1Approval = $internship->approvals->where('role', 'wadir1')->first();
        @endphp
        
        @if($wadir1Approval && $wadir1Approval->qr_code_path)
            <div class="qr-code">
                <img src="{{ storage_path('app/public/' . $wadir1Approval->qr_code_path) }}" 
                     alt="QR Code" style="width: 100px; height: 100px;">
                <p style="font-size: 8pt; margin: 5px 0;">
                    Disetujui secara elektronik pada:<br>
                    {{ $wadir1Approval->approved_at->format('d/m/Y H:i:s') }}
                </p>
            </div>
        @endif
        
        <p style="margin-top: 60px; border-top: 1px solid #000; display: inline-block; padding-top: 5px;">
            @if($wadir1Approval)
                <strong>{{ $wadir1Approval->approver->name }}</strong><br>
                NIP. {{ $wadir1Approval->approver->nim ?? '-' }}
            @else
                <strong>[Menunggu Persetujuan]</strong>
            @endif
        </p>
    </div>
    
    <div class="footer">
        <p>Dokumen ini dibuat secara elektronik melalui SIMAMANG (Sistem Manajemen Magang) POLSRI</p>
        <p>Verifikasi dokumen dapat dilakukan dengan memindai QR Code di atas</p>
    </div>
</body>
</html>