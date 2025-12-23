@extends('layouts.app')

@php
    // Logic Preserved
    $suratPengantar = $generatedDocuments
        ? $generatedDocuments->firstWhere('document_type', 'surat_pengantar_jurusan')
        : null;

    $pengesahan = $generatedDocuments
        ? $generatedDocuments->firstWhere('document_type', 'halaman_pengesahan_proposal')
        : null;

    $semuaSudahUpload =
        ($suratPengantar && $suratPengantar->status === 'uploaded') &&
        ($pengesahan && $pengesahan->status === 'uploaded');
    
    $suratResmiInstansi = $internship->documents
        ? $internship->documents->firstWhere('type', 'surat_pengantar_resmi_instansi')
        : null;
    $suratResmiSudahUpload = $suratResmiInstansi !== null;
@endphp

@section('title', 'Detail Pengajuan Magang')

@push('styles')
<style>
    :root {
        --primary-teal: #00A19C;
        --soft-teal: #e6fffa;
        --text-dark: #2d3748;
        --text-gray: #718096;
    }

    /* Layout & Cards */
    .page-header {
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        margin-bottom: 2rem;
        border-left: 5px solid var(--primary-teal);
    }

    .custom-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: none;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header-clean {
        background: white;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #edf2f7;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Timeline Styling */
    .timeline-wrapper {
        position: relative;
        padding-left: 1rem;
    }
    .timeline-item {
        position: relative;
        padding-left: 2rem;
        padding-bottom: 1.5rem;
        border-left: 2px solid #e2e8f0;
    }
    .timeline-item:last-child {
        border-left: 2px solid transparent;
        padding-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: -9px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: white;
        border: 2px solid var(--primary-teal);
    }
    .timeline-marker.danger { border-color: #e53e3e; }
    .timeline-marker.success { border-color: #38a169; background: #38a169; }

    /* Stat/Info Boxes */
    .info-box {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        height: 100%;
        border: 1px solid #edf2f7;
    }
    
    /* File Item */
    .file-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
        background: white;
    }
    .file-item:hover {
        border-color: var(--primary-teal);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .file-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff5f5;
        color: #e53e3e;
        border-radius: 8px;
        font-size: 1.25rem;
        margin-right: 1rem;
    }

    /* Labels */
    .info-label {
        font-size: 0.8rem;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
        display: block;
    }
    .info-value {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 1rem;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex align-items-center mb-1">
                    <a href="{{ route('internships.index') }}" class="text-muted me-2 text-decoration-none">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h1 class="h4 fw-bold mb-0 text-dark">Detail Pengajuan #{{ $internship->id }}</h1>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="badge rounded-pill bg-{{ $internship->status_color }} px-3 py-2">
                        {{ $internship->status_label }}
                    </span>
                    <span class="text-muted small">Updated {{ $internship->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            
            <div class="mt-3 mt-md-0 d-flex gap-2">
                @if($internship->letter_number && $internship->status === 'surat_terbit' && !$suratResmiSudahUpload)
                    <a href="{{ route('pdf.letter.download', $internship) }}" class="btn btn-success shadow-sm">
                        <i class="bi bi-file-earmark-check me-1"></i> Download Surat Resmi
                    </a>
                @endif

                @if(auth()->user()->isMahasiswa() && $internship->status === 'disetujui_akademik' && $generatedDocuments && $generatedDocuments->count() > 0 && !$semuaSudahUpload)
                    <div class="btn-group shadow-sm">
                        @if($suratPengantar && $suratPengantar->status !== 'uploaded')
                            <a href="{{ route('internships.document.download', [$internship, $suratPengantar]) }}" class="btn btn-outline-success bg-white">
                                <i class="bi bi-download me-1"></i> Surat Pengantar
                            </a>
                        @endif
                        @if($pengesahan && $pengesahan->status !== 'uploaded')
                            <a href="{{ route('internships.document.download', [$internship, $pengesahan]) }}" class="btn btn-outline-success bg-white">
                                <i class="bi bi-download me-1"></i> Pengesahan
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($internship->status === 'revisi' && $internship->revision_note)
<div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4 d-flex">
    <div class="fs-1 me-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i></div>
    <div>
        <h6 class="fw-bold mb-1">Perlu Revisi</h6>
        <p class="mb-2">{{ $internship->revision_note }}</p>
        @if(auth()->user()->isMahasiswa())
            <a href="{{ route('internships.edit', $internship) }}" class="btn btn-warning btn-sm text-white fw-bold">
                <i class="bi bi-pencil-square me-1"></i> Perbarui Data
            </a>
        @endif
    </div>
</div>
@endif

@if($internship->status === 'disetujui_akademik' && auth()->user()->isMahasiswa() && !$semuaSudahUpload)
<div class="alert alert-info border-0 shadow-sm rounded-3 mb-4 d-flex bg-soft-teal" style="background-color: #e1f5fe; color: #0277bd;">
    <div class="fs-1 me-3"><i class="bi bi-info-circle-fill"></i></div>
    <div class="w-100">
        <h6 class="fw-bold mb-2">Langkah Selanjutnya</h6>
        <ol class="ps-3 mb-0 small">
            <li>Download <strong>Surat Pengantar</strong> & <strong>Pengesahan</strong> dari tombol di kanan atas.</li>
            <li>Tanda tangani dokumen tersebut.</li>
            <li>Upload kembali melalui tombol <strong>"Upload Dokumen Tertandatangani"</strong> di bagian Dokumen di bawah.</li>
        </ol>
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        
        <div class="custom-card">
            <div class="card-header-clean">
                <span><i class="bi bi-building me-2 text-primary"></i>Informasi Perusahaan & Periode</span>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-md-12 mb-3">
                        <span class="info-label">Nama Perusahaan</span>
                        <div class="fs-5 fw-bold text-dark">{{ $internship->company_name }}</div>
                        <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i> {{ $internship->company_city }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="info-label">Alamat Lengkap</span>
                        <div class="info-value">{{ $internship->company_address }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <span class="info-label">Kontak</span>
                                <div class="info-value">
                                    @if($internship->company_phone) <i class="bi bi-telephone me-1 text-muted"></i> {{ $internship->company_phone }} @endif
                                </div>
                                <div class="info-value">
                                    @if($internship->company_email) <i class="bi bi-envelope me-1 text-muted"></i> {{ $internship->company_email }} @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="bi bi-calendar-check fs-4 text-success mb-2 d-block"></i>
                            <span class="info-label">Mulai</span>
                            <div class="info-value">{{ $internship->start_date->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="bi bi-hourglass-split fs-4 text-primary mb-2 d-block"></i>
                            <span class="info-label">Durasi</span>
                            <div class="info-value">{{ $internship->duration_months }} Bulan</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <i class="bi bi-calendar-x fs-4 text-danger mb-2 d-block"></i>
                            <span class="info-label">Selesai</span>
                            <div class="info-value">{{ $internship->end_date->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
                
                @if($internship->internship_description)
                <div class="mt-4 p-3 bg-light rounded-3">
                    <span class="info-label mb-2">Rencana Kegiatan / Deskripsi</span>
                    <p class="mb-0 text-muted small">{{ $internship->internship_description }}</p>
                </div>
                @endif
                
                @if($internship->latitude && $internship->longitude)
                <div class="mt-4">
                    <div class="ratio ratio-21x9 rounded-3 overflow-hidden border">
                         <iframe src="https://maps.google.com/maps?q={{ $internship->latitude }},{{ $internship->longitude }}&hl=id&z=14&output=embed" style="border:0;"></iframe>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="custom-card">
            <div class="card-header-clean">
                <span><i class="bi bi-folder2-open me-2 text-warning"></i>Berkas Dokumen</span>
                <div>
                    {{-- Logic Tombol Upload Mahasiswa --}}
                    @if(auth()->user()->isMahasiswa())
                        @if($internship->status === 'disetujui_akademik' && $generatedDocuments && $generatedDocuments->count() > 0 && !$semuaSudahUpload)
                             <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#uploadDocumentsModal">
                                <i class="bi bi-upload me-1"></i> Upload Ttd
                            </button>
                        @endif

                        @if($internship->status === 'surat_terbit')
                            @if(!$suratResmiSudahUpload)
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#uploadOfficialLetterModal">
                                    <i class="bi bi-upload me-1"></i> Upload Surat Instansi
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#uploadResponseModal">
                                    <i class="bi bi-upload me-1"></i> Upload Balasan
                                </button>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                @if($internship->documents->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-file-earmark-x fs-1 opacity-25"></i>
                        <p class="mt-2 small">Belum ada dokumen yang diunggah.</p>
                    </div>
                @else
                    @foreach($internship->documents as $doc)
                    <div class="file-item">
                        <div class="file-icon">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark">{{ $doc->file_name }}</div>
                            <div class="text-muted small text-uppercase" style="font-size: 0.7rem;">
                                {{ str_replace('_', ' ', $doc->type) }} • {{ number_format($doc->file_size / 1024, 0) }} KB
                            </div>
                            <div class="text-muted small" style="font-size: 0.7rem;">
                                <i class="bi bi-clock"></i> {{ $doc->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-light text-primary btn-sm rounded-pill px-3">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
        <div class="custom-card text-center p-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px; font-size: 1.5rem; font-weight: bold; color: var(--primary-teal);">
                {{ substr($internship->student->name, 0, 2) }}
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ $internship->student->name }}</h5>
            <p class="text-muted mb-3">{{ $internship->student->nim }}</p>
            <hr class="my-3">
            <div class="text-start">
                <small class="text-muted d-block mb-1">Jurusan</small>
                <div class="fw-bold text-dark mb-2">{{ $internship->student->jurusan }}</div>
                <small class="text-muted d-block mb-1">Program Studi</small>
                <div class="fw-bold text-dark">{{ $internship->student->prodi }}</div>
            </div>
        </div>

        @if(!auth()->user()->isMahasiswa())
        <div class="custom-card">
            <div class="card-header-clean bg-light">
                <span><i class="bi bi-sliders me-2"></i>Aksi Persetujuan</span>
            </div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    {{-- Admin Jurusan Verify --}}
                    @if(auth()->user()->role === 'admin_jurusan' && $internship->status === 'diajukan')
                        <form action="{{ route('internships.verify', $internship) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="verify">
                            <button class="btn btn-success w-100 fw-bold py-2"><i class="bi bi-check-lg me-1"></i> Verifikasi</button>
                        </form>
                        <button class="btn btn-warning w-100 fw-bold py-2 text-white" data-bs-toggle="modal" data-bs-target="#reviseModal">
                            <i class="bi bi-pencil me-1"></i> Minta Revisi
                        </button>
                    
                    {{-- Officials Approve --}}
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
                                <button class="btn btn-success w-100 fw-bold py-2 mb-2"><i class="bi bi-check-lg me-1"></i> Setujui Pengajuan</button>
                            </form>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button class="btn btn-warning w-100 text-white" data-bs-toggle="modal" data-bs-target="#pejabatReviseModal">
                                        <i class="bi bi-arrow-counterclockwise"></i> Revisi
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->role === 'kpa' && $internship->status === 'disetujui_akademik')
                            <form action="{{ route('internships.generate-letter', $internship) }}" method="POST" target="_blank" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-printer me-1"></i> Generate Surat
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="text-center text-muted small py-2">
                            Tidak ada aksi yang diperlukan saat ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="custom-card">
            <div class="card-header-clean">
                <span><i class="bi bi-clock-history me-2"></i>Riwayat Status</span>
            </div>
            <div class="card-body p-4">
                <div class="timeline-wrapper">
                    @foreach($internship->approvals as $approval)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $approval->action === 'reject' ? 'danger' : ($approval->action === 'approve' ? 'success' : '') }}"></div>
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                            {{ ucfirst($approval->role) }}
                        </div>
                        <div class="small text-muted mb-1">
                            {{ $approval->approver->name }}
                        </div>
                        <div class="badge bg-light text-dark border mb-1">
                            {{ ucfirst($approval->action) }}
                        </div>
                        @if($approval->note)
                            <div class="bg-light p-2 rounded small text-muted fst-italic mt-1">
                                "{{ $approval->note }}"
                            </div>
                        @endif
                        <div class="text-muted mt-1" style="font-size: 0.7rem;">
                            {{ $approval->approved_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                    @endforeach
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">Dibuat</div>
                        <div class="small text-muted">{{ $internship->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="uploadDocumentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('internships.documents.upload', $internship) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Upload Dokumen Tertandatangani</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        Pastikan dokumen sudah ditandatangani sebelum diupload. Format PDF maks 10MB.
                    </div>
                    
                    @if($suratPengantar)
                        <input type="hidden" name="documents[surat_pengantar][id]" value="{{ $suratPengantar->id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Surat Pengantar Jurusan</label>
                            <input type="file" name="documents[surat_pengantar][file]" class="form-control" accept="application/pdf" required>
                        </div>
                    @endif

                    @if($pengesahan)
                        <input type="hidden" name="documents[pengesahan][id]" value="{{ $pengesahan->id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Pengesahan Proposal</label>
                            <input type="file" name="documents[pengesahan][file]" class="form-control" accept="application/pdf" required>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadOfficialLetterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('internships.upload-response', $internship) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Upload Surat Pengantar Resmi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Silakan upload surat pengantar resmi dari instansi yang diterbitkan oleh Wadir 1.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File PDF</label>
                        <input type="file" name="official_letter" class="form-control" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadResponseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('internships.upload-response', $internship) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Upload Balasan Perusahaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Silakan upload surat balasan penerimaan dari perusahaan.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File PDF</label>
                        <input type="file" name="response_letter" class="form-control" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="reviseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('internships.verify', $internship) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="revise">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-warning"><i class="bi bi-exclamation-circle me-2"></i>Minta Revisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Catatan Revisi</label>
                        <textarea name="note" class="form-control bg-light" rows="4" required placeholder="Jelaskan bagian mana yang perlu diperbaiki mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4">Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('internships.approve', $internship) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-header border-bottom-0 bg-danger text-white rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-x-circle me-2"></i>Tolak Pengajuan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger fw-medium mt-3">Apakah Anda yakin ingin menolak pengajuan ini?</p>
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="note" class="form-control" rows="3" required placeholder="Berikan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pejabatReviseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('internships.revise', $internship) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-warning"><i class="bi bi-arrow-counterclockwise me-2"></i>Kembalikan (Revisi)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border small text-muted">
                        Pengajuan akan dikembalikan ke mahasiswa.
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan <span class="text-danger">*</span></label>
                        <textarea name="note" class="form-control" rows="4" required placeholder="Instruksi perbaikan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4">Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection