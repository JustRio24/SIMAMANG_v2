@extends('layouts.app')

@section('title', 'Kelola Template')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Kelola Template Dokumen</h1>
        <p class="text-muted mb-0">Upload dan kelola template dokumen untuk mahasiswa</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadTemplateModal">
        <i class="bi bi-upload"></i> Upload Template
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-files"></i> Daftar Template</h5>
    </div>
    <div class="card-body">
        @if($templates->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama Template</th>
                        <th>File</th>
                        <th>Ukuran</th>
                        <th>Diupload</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                    <tr>
                        <td>
                            <strong>{{ $template->name }}</strong>
                            @if($template->description)
                                <br><small class="text-muted">{{ Str::limit($template->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $iconClass = match($template->file_type) {
                                    'pdf' => 'bi-file-earmark-pdf text-danger',
                                    'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                    'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                    default => 'bi-file-earmark text-secondary'
                                };
                            @endphp
                            <i class="bi {{ $iconClass }}"></i>
                            {{ Str::limit($template->file_name, 30) }}
                        </td>
                        <td>{{ number_format($template->file_size / 1024, 2) }} KB</td>
                        <td>
                            {{ $template->created_at->format('d M Y') }}<br>
                            <small class="text-muted">oleh {{ $template->uploader->name }}</small>
                        </td>
                        <td>
                            @if($template->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('templates.download', $template) }}" class="btn btn-outline-primary" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                <form action="{{ route('templates.toggle', $template) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-{{ $template->is_active ? 'warning' : 'success' }}" title="{{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-{{ $template->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus template ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
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
            <i class="bi bi-folder-x display-4 text-muted"></i>
            <h5 class="mt-3 text-muted">Belum ada template</h5>
            <p class="text-muted">Klik tombol "Upload Template" untuk menambah template baru.</p>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="uploadTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-upload"></i> Upload Template Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Template <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Template Surat Pengantar">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat tentang template ini"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Template <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                        <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX. Maks 10MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
