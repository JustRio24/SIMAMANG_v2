@extends('layouts.app')

@section('title', 'Template Dokumen')

@push('styles')
<style>
    :root {
        --primary-teal: #00A19C;
        --card-hover-transform: translateY(-5px);
    }

    /* Page Header */
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border-left: 5px solid var(--primary-color);
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Card Styling */
    .doc-card {
        border: none;
        border-radius: 16px;
        background: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        height: 100%;
        overflow: hidden;
        position: relative;
    }

    .doc-card:hover {
        transform: var(--card-hover-transform);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }

    /* Icon Wrapper Styling */
    .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1.25rem;
    }

    /* File Type Colors */
    .type-pdf { background-color: #fee2e2; color: #dc2626; }
    .type-word { background-color: #e0f2fe; color: #0284c7; }
    .type-excel { background-color: #dcfce7; color: #16a34a; }
    .type-default { background-color: #f1f5f9; color: #64748b; }

    /* Button Styling */
    .btn-download {
        background-color: #f8fafc;
        color: var(--primary-teal);
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-download:hover {
        background-color: var(--primary-teal);
        color: white;
        border-color: var(--primary-teal);
    }

    /* Empty State */
    .empty-state-icon {
        font-size: 5rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-header">
            <div>
                <h1 class="h3 fw-bold mb-1 text-dark">Template Dokumen</h1></h1>
                <p class="text-muted mb-0">Silakan unduh template dokumen resmi di bawah ini untuk keperluan administrasi magang Anda.</p>
            </div>
            <div class="d-none d-md-block text-primary opacity-25">
                <i class="bi bi-folder" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($templates as $template)
        @php
            // Determine styles based on extension
            $typeConfig = match(strtolower($template->file_type)) {
                'pdf' => ['class' => 'type-pdf', 'icon' => 'bi-file-earmark-pdf-fill'],
                'doc', 'docx' => ['class' => 'type-word', 'icon' => 'bi-file-earmark-word-fill'],
                'xls', 'xlsx' => ['class' => 'type-excel', 'icon' => 'bi-file-earmark-excel-fill'],
                default => ['class' => 'type-default', 'icon' => 'bi-file-earmark-fill']
            };
        @endphp

        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="doc-card p-4 d-flex flex-column h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="icon-wrapper {{ $typeConfig['class'] }}">
                        <i class="bi {{ $typeConfig['icon'] }}"></i>
                    </div>
                    <span class="badge bg-light text-secondary border fw-normal rounded-pill px-3">
                        {{ strtoupper($template->file_type) }}
                    </span>
                </div>

                <div class="flex-grow-1">
                    <h5 class="fw-bold text-dark mb-2">{{ $template->name }}</h5>
                    <p class="text-muted small mb-3">
                        {{ $template->description ?? 'Tidak ada deskripsi tambahan untuk dokumen ini.' }}
                    </p>
                </div>

                <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-2">
                    <span class="text-muted small fw-medium">
                        <i class="bi bi-hdd me-1"></i> {{ number_format($template->file_size / 1024, 0) }} KB
                    </span>
                    <a href="{{ route('templates.download', $template) }}" class="btn btn-download btn-sm stretched-link">
                        <i class="bi bi-download me-1"></i> Unduh
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 bg-white shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <i class="bi bi-folder2-open empty-state-icon"></i>
                    <h4 class="fw-bold text-dark mt-3">Belum Ada Template</h4>
                    <p class="text-muted">Saat ini belum ada dokumen template yang diunggah oleh admin.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@endsection