@extends('layouts.app')

@section('title', 'Kelola Template')

@push('styles')
<style>
    :root {
        --primary-color: #00A19C;
        --primary-light: #e6f6f5;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    /* Header Styling */
    .page-header {
        background: white;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        margin-bottom: 1.5rem;
    }

    /* Custom Table Styling */
    .table-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .table thead th {
        background-color: #f8fafc;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f1f5f9;
        padding: 1rem 1.5rem;
    }

    .table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        color: var(--text-dark);
        border-bottom: 1px solid #f1f5f9;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background-color: #fafbfc;
    }

    /* File Icon Styling */
    .file-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 12px;
    }
    .icon-pdf { background: #fee2e2; color: #ef4444; }
    .icon-word { background: #e0f2fe; color: #0ea5e9; }
    .icon-excel { background: #dcfce7; color: #22c55e; }
    .icon-default { background: #f1f5f9; color: #94a3b8; }

    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
        border: none;
    }
    .btn-action-view { background: #e0f2fe; color: #0284c7; }
    .btn-action-view:hover { background: #0284c7; color: white; }
    
    .btn-action-toggle { background: #f3f4f6; color: #4b5563; }
    .btn-action-toggle:hover { background: #4b5563; color: white; }
    .btn-action-toggle.active { background: #dcfce7; color: #16a34a; }
    
    .btn-action-delete { background: #fee2e2; color: #dc2626; }
    .btn-action-delete:hover { background: #dc2626; color: white; }

    /* Modal Styling */
    .custom-modal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .custom-modal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem;
    }
    .custom-modal .modal-body {
        padding: 1.5rem;
    }
    .custom-modal .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }
    .form-control {
        border-radius: 8px;
        padding: 0.625rem 1rem;
        border-color: #cbd5e1;
    }
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px var(--primary-light);
    }
</style>
@endpush

@section('content')

<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1 text-dark">Kelola Template</h1>
        <p class="text-muted mb-0">Atur dan perbarui dokumen template untuk mahasiswa</p>
    </div>
    <button type="button" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal" style="background-color: var(--primary-color); border: none;">
        <i class="bi bi-plus-lg me-2"></i>Upload Baru
    </button>
</div>

<div class="card table-card">
    <div class="card-body p-0">
        @if($templates->count() > 0)
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="35%">Informasi Template</th>
                        <th width="20%">File Dokumen</th>
                        <th width="20%">Upload Info</th>
                        <th width="10%">Status</th>
                        <th width="15%" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                    <tr>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark">{{ $template->name }}</span>
                                @if($template->description)
                                    <small class="text-muted mt-1">{{ Str::limit($template->description, 50) }}</small>
                                @else
                                    <small class="text-muted fst-italic mt-1">Tidak ada deskripsi</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $style = match($template->file_type) {
                                    'pdf' => ['class' => 'icon-pdf', 'icon' => 'bi-file-earmark-pdf-fill'],
                                    'doc', 'docx' => ['class' => 'icon-word', 'icon' => 'bi-file-earmark-word-fill'],
                                    'xls', 'xlsx' => ['class' => 'icon-excel', 'icon' => 'bi-file-earmark-excel-fill'],
                                    default => ['class' => 'icon-default', 'icon' => 'bi-file-earmark-fill']
                                };
                            @endphp
                            <div class="d-flex align-items-center">
                                <div class="file-icon-box {{ $style['class'] }}">
                                    <i class="bi {{ $style['icon'] }}"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="small fw-bold text-dark">{{ strtoupper($template->file_type) }}</span>
                                    <span class="small text-muted">{{ number_format($template->file_size / 1024, 0) }} KB</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="small fw-semibold">{{ $template->uploader->name }}</span>
                                <span class="small text-muted">{{ $template->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </td>
                        <td>
                            @if($template->is_active)
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                    <i class="bi bi-slash-circle me-1"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('templates.download', $template) }}" class="btn-action btn-action-view" title="Download File">
                                    <i class="bi bi-download"></i>
                                </a>

                                <form action="{{ route('templates.toggle', $template) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn-action btn-action-toggle {{ $template->is_active ? 'active' : '' }}" 
                                            title="{{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-{{ $template->is_active ? 'toggle-on' : 'toggle-off' }} fs-5"></i>
                                    </button>
                                </form>

                                <form action="{{ route('templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-action-delete" title="Hapus Template">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-folder2-open text-muted display-6"></i>
            </div>
            <h5 class="fw-bold text-dark">Belum ada template</h5>
            <p class="text-muted mb-3">Silakan upload template dokumen pertama Anda.</p>
            <button type="button" class="btn btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
                <i class="bi bi-upload me-2"></i> Upload Sekarang
            </button>
        </div>
        @endif
    </div>
</div>

<div class="modal fade custom-modal" id="uploadTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Upload Template Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Informasi Dokumen</label>
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" required placeholder="Nama Template (Contoh: Surat Pengantar)">
                        </div>
                        <div class="mb-3">
                            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat (Opsional)"></textarea>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label fw-semibold small text-uppercase text-muted">File Dokumen</label>
                        <div class="border-2 border-dashed border-secondary border-opacity-25 rounded-3 p-4 text-center bg-light">
                            <i class="bi bi-cloud-arrow-up display-6 text-primary mb-2"></i>
                            <div class="mt-2">
                                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                                <div class="form-text mt-2">
                                    Mendukung format: PDF, Word, Excel. Maksimal 10MB.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light text-muted fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold" style="background-color: var(--primary-color); border: none;">
                        Simpan Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection