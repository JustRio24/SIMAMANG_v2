@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Magang')

@push('styles')
<style>
    :root {
        --primary-teal: #00A19C;
        --soft-teal: #e6fffa;
        --text-dark: #2d3748;
        --text-gray: #718096;
    }

    .page-header {
        background: white;
        padding: 2rem;
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
        overflow: hidden;
    }

    /* Table Styling */
    .table-modern {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .table-modern thead th {
        background-color: #f8fafc;
        color: var(--text-gray);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #edf2f7;
        padding: 1rem 1.5rem;
    }

    .table-modern tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #edf2f7;
    }

    .table-modern tbody tr:hover {
        background-color: #fafdff;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .table-modern td {
        padding: 1.25rem 1.5rem;
        color: var(--text-dark);
        font-size: 0.95rem;
    }

    /* Company Avatar Placeholder */
    .company-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, #00A19C 0%, #4facfe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    /* Soft Status Badge */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .bg-success { background-color: #def7ec !important; color: #03543f; }
    .bg-warning { background-color: #fef3c7 !important; color: #92400e; }
    .bg-danger { background-color: #fde8e8 !important; color: #9b1c1c; }
    .bg-info { background-color: #e1effe !important; color: #1e429f; }
    .bg-secondary { background-color: #f3f4f6 !important; color: #374151; }

    /* Action Buttons */
    .btn-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .btn-icon:hover {
        transform: translateY(-2px);
    }
    
    .btn-icon-view { background: #ebf8ff; color: #3182ce; }
    .btn-icon-view:hover { background: #3182ce; color: white; }
    
    .btn-icon-edit { background: #fffaf0; color: #dd6b20; }
    .btn-icon-edit:hover { background: #dd6b20; color: white; }

    /* Empty State */
    .empty-state-container {
        padding: 5rem 2rem;
        text-align: center;
    }
    
    .empty-icon {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1 text-dark">Pengajuan Magang</h1>
                <p class="text-muted mb-0">Kelola dan pantau status pengajuan magang Anda.</p>
            </div>
            @if(auth()->user()->isMahasiswa())
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('internships.create') }}" class="btn btn-primary px-4 py-2" style="background: var(--primary-teal); border: none; border-radius: 10px;">
                        <i class="bi bi-plus-lg me-2"></i>Buat Pengajuan
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="custom-card">
            @if($applications->isEmpty())
                <div class="empty-state-container">
                    <div class="empty-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Belum Ada Pengajuan</h5>
                    @if(auth()->user()->isMahasiswa())
                        <p class="text-muted mb-4">Anda belum membuat pengajuan magang apapun. Yuk, mulai sekarang!</p>
                        <a href="{{ route('internships.create') }}" class="btn btn-outline-primary rounded-pill px-4">
                            Buat Pengajuan Pertama
                        </a>
                    @else
                        <p class="text-muted">Belum ada data pengajuan yang tersedia.</p>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Perusahaan</th>
                                @if(!auth()->user()->isMahasiswa())
                                    <th>Mahasiswa</th>
                                @endif
                                <th>Periode & Lokasi</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $app)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="company-avatar">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $app->company_name }}</div>
                                            <small class="text-muted">ID: #{{ $app->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                
                                @if(!auth()->user()->isMahasiswa())
                                    <td>
                                        <div class="fw-bold text-dark">{{ $app->student->name }}</div>
                                        <small class="text-primary">{{ $app->student->nim }}</small>
                                    </td>
                                @endif
                                
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-medium">
                                            <i class="bi bi-calendar2-range me-1 text-muted"></i>
                                            {{ $app->start_date->format('d M') }} - {{ $app->end_date->format('d M Y') }}
                                        </span>
                                        <small class="text-muted mt-1">
                                            <i class="bi bi-geo-alt me-1"></i> {{ $app->company_city }} 
                                            <span class="badge bg-light text-dark border ms-1">{{ $app->duration_months }} Bulan</span>
                                        </small>
                                    </div>
                                </td>
                                
                                <td>
                                    {{-- Menggunakan class status-badge + class warna dari backend atau mapping --}}
                                    <span class="status-badge bg-{{ $app->status_color }}">
                                        @if($app->status == 'approved') <i class="bi bi-check-circle-fill me-1"></i>
                                        @elseif($app->status == 'rejected') <i class="bi bi-x-circle-fill me-1"></i>
                                        @elseif($app->status == 'pending') <i class="bi bi-clock-fill me-1"></i>
                                        @endif
                                        {{ $app->status_label }}
                                    </span>
                                </td>
                                
                                <td>
                                    <span class="text-muted small">
                                        {{ $app->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                
                                <td class="text-end">
                                    <a href="{{ route('internships.show', $app) }}" class="btn-icon btn-icon-view" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    
                                    @if(auth()->user()->isMahasiswa() && in_array($app->status, ['diajukan', 'revisi', 'pending']))
                                        <a href="{{ route('internships.edit', $app) }}" class="btn-icon btn-icon-edit ms-1" title="Edit Pengajuan">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-3 border-top">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection