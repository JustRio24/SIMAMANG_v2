@extends('layouts.app')

@section('title', 'Template Dokumen')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Template Dokumen</h1>
        <p class="text-muted mb-0">Download template dokumen yang diperlukan untuk pengajuan magang</p>
    </div>
</div>

<div class="row">
    @forelse($templates as $template)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0">
                        @php
                            $iconClass = match($template->file_type) {
                                'pdf' => 'bi-file-earmark-pdf text-danger',
                                'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                default => 'bi-file-earmark text-secondary'
                            };
                        @endphp
                        <i class="bi {{ $iconClass }} fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-1">{{ $template->name }}</h5>
                        @if($template->description)
                            <p class="card-text text-muted small">{{ $template->description }}</p>
                        @endif
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="bi bi-file-earmark"></i> {{ strtoupper($template->file_type) }} &bull;
                                {{ number_format($template->file_size / 1024, 2) }} KB
                            </small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('templates.download', $template) }}" class="btn btn-primary w-100">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-folder-x display-4 text-muted"></i>
                <h5 class="mt-3 text-muted">Belum ada template</h5>
                <p class="text-muted">Template dokumen akan muncul di sini setelah diupload oleh admin.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
