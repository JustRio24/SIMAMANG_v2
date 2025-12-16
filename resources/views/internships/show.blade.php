@extends('layouts.app')

@section('title', 'Detail Pengajuan Magang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Detail Pengajuan Magang</h1>
        <p class="text-muted mb-0">ID Pengajuan: <strong>#{{ $internship->id }}</strong></p>
    </div>
    <div>
        <a href="{{ route('internships.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        @if($internship->letter_number && $internship->status === 'surat_terbit')
            <a href="{{ route('pdf.letter.download', $internship) }}" class="btn btn-success">
                <i class="bi bi-download"></i> Download Surat
            </a>
        @endif
    </div>
</div>

<!-- Status Banner -->
<div class="alert alert-{{ $internship->status_color }} d-flex align-items-center mb-4">
    <i class="bi bi-{{ $internship->status === 'selesai' ? 'check-circle' : 'info-circle' }} fs-4 me-3"></i>
    <div>
        <h5 class="mb-1">Status: {{ $internship->status_label }}</h5>
        <p class="mb-0 small">Diupdate {{ $internship->updated_at->diffForHumans() }}</p>
    </div>
</div>

@if($internship->status === 'revisi' && $internship->revision_note)
<div class="alert alert-warning">
    <h6><i class="bi bi-exclamation-triangle"></i> Catatan Revisi:</h6>
    <p class="mb-0">{{ $internship->revision_note }}</p>
    @if(auth()->user()->isMahasiswa())
        <a href="{{ route('internships.edit', $internship) }}" class="btn btn-warning btn-sm mt-2">
            <i class="bi bi-pencil"></i> Perbarui Pengajuan
        </a>
    @endif
</div>
@endif

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Company Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-building"></i> Informasi Perusahaan</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Nama Perusahaan:</th>
                        <td><strong>{{ $internship->company_name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Alamat:</th>
                        <td>{{ $internship->company_address }}</td>
                    </tr>
                    <tr>
                        <th>Kota:</th>
                        <td>{{ $internship->company_city }}</td>
                    </tr>
                    @if($internship->company_phone)
                    <tr>
                        <th>Telepon:</th>
                        <td>{{ $internship->company_phone }}</td>
                    </tr>
                    @endif
                    @if($internship->company_email)
                    <tr>
                        <th>Email:</th>
                        <td>{{ $internship->company_email }}</td>
                    </tr>
                    @endif
                </table>
                
                @if($internship->latitude && $internship->longitude)
                <div class="mt-3">
                    <h6>Lokasi di Peta:</h6>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://maps.google.com/maps?q={{ $internship->latitude }},{{ $internship->longitude }}&hl=id&z=14&output=embed" 
                                frameborder="0" style="border:0; border-radius: 8px;"></iframe>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Internship Period -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-range"></i> Periode Magang</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-calendar-check text-success fs-3"></i>
                            <p class="mb-1 mt-2 text-muted small">Tanggal Mulai</p>
                            <h5 class="mb-0">{{ $internship->start_date->format('d M Y') }}</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-hourglass-split text-primary fs-3"></i>
                            <p class="mb-1 mt-2 text-muted small">Durasi</p>
                            <h5 class="mb-0">{{ $internship->duration_months }} Bulan</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-calendar-x text-danger fs-3"></i>
                            <p class="mb-1 mt-2 text-muted small">Tanggal Selesai</p>
                            <h5 class="mb-0">{{ $internship->end_date->format('d M Y') }}</h5>
                        </div>
                    </div>
                </div>
                
                @if($internship->internship_description)
                <div class="mt-3">
                    <h6>Deskripsi Magang:</h6>
                    <p class="text-muted">{{ $internship->internship_description }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Documents -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-files"></i> Dokumen</h5>
                @if(auth()->user()->isMahasiswa() && $internship->status === 'surat_terbit')
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadResponseModal">
                        <i class="bi bi-upload"></i> Upload Surat Balasan
                    </button>
                @endif
            </div>
            <div class="card-body">
                @foreach($internship->documents as $doc)
                <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-2">
                    <div>
                        <h6 class="mb-1">
                            <i class="bi bi-file-earmark-pdf text-danger"></i>
                            {{ $doc->file_name }}
                        </h6>
                        <small class="text-muted">
                            Jenis: {{ ucfirst($doc->type) }} • 
                            Diupload {{ $doc->created_at->diffForHumans() }} • 
                            {{ number_format($doc->file_size / 1024, 2) }} KB
                        </small>
                    </div>
                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Student Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person"></i> Data Mahasiswa</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $internship->student->name }}</strong></p>
                <p class="mb-1 text-muted small">NIM: {{ $internship->student->nim }}</p>
                <p class="mb-1 text-muted small">Jurusan: {{ $internship->student->jurusan }}</p>
                <p class="mb-0 text-muted small">Prodi: {{ $internship->student->prodi }}</p>
            </div>
        </div>
        
        <!-- Approval Actions -->
        @if(!auth()->user()->isMahasiswa())
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-check2-square"></i> Tindakan</h5>
            </div>
            <div class="card-body">
                @if(auth()->user()->role === 'admin_jurusan' && $internship->status === 'diajukan')
                    <form action="{{ route('internships.verify', $internship) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="verify">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Verifikasi
                        </button>
                    </form>
                    <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#reviseModal">
                        <i class="bi bi-arrow-clockwise"></i> Minta Revisi
                    </button>
                @elseif(auth()->user()->isPejabat())
                    @php
                        $canApprove = [
                            'kaprodi' => 'diverifikasi_jurusan',
                            'kajur' => 'disetujui_kaprodi',
                            'kpa' => 'disetujui_akademik',
                            'wadir1' => 'diproses_kpa',
                        ];
                    @endphp
                    
                    @if(isset($canApprove[auth()->user()->role]) && $internship->status === $canApprove[auth()->user()->role])
                        <form action="{{ route('internships.approve', $internship) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-check-circle"></i> Setujui
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>
                    @endif
                    
                    @if(auth()->user()->role === 'kpa' && $internship->status === 'disetujui_akademik')
                        <form action="{{ route('internships.generate-letter', $internship) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-file-text"></i> Generate Surat
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
        @endif
        
        <!-- Approval Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Timeline Persetujuan</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($internship->approvals as $approval)
                    <div class="timeline-item">
                        <div>
                            <strong class="text-{{ $approval->action === 'approve' ? 'success' : 'danger' }}">
                                <i class="bi bi-{{ $approval->action === 'approve' ? 'check-circle' : 'x-circle' }}"></i>
                                {{ ucfirst($approval->role) }}
                            </strong>
                            <p class="mb-1 small">{{ $approval->approver->name }}</p>
                            @if($approval->note)
                                <p class="text-muted small mb-1">{{ $approval->note }}</p>
                            @endif
                            <small class="text-muted">
                                {{ $approval->approved_at->format('d M Y H:i') }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Response -->
<div class="modal fade" id="uploadResponseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('internships.upload-response', $internship) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Surat Balasan Perusahaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">File Surat Balasan (PDF)</label>
                        <input type="file" name="response_letter" class="form-control" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Revise -->
<div class="modal fade" id="reviseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('internships.verify', $internship) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="revise">
                <div class="modal-header">
                    <h5 class="modal-title">Minta Revisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Catatan Revisi</label>
                        <textarea name="note" class="form-control" rows="4" required placeholder="Jelaskan apa yang perlu direvisi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('internships.approve', $internship) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Pengajuan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> Tindakan ini akan menolak pengajuan magang.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="note" class="form-control" rows="4" required placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection