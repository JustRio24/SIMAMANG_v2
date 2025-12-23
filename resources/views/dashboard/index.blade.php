@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #00A19C 0%, #4facfe 100%);
        --card-bg: #ffffff;
        --body-bg: #f3f6f9;
    }

    body {
        background-color: var(--body-bg);
    }

    /* Hero Section */
    .hero-card {
        background: var(--primary-gradient);
        border-radius: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 161, 156, 0.2);
        border: none;
    }

    .hero-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .hero-card::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* Stats Cards */
    .stat-card {
        border: none;
        border-radius: 16px;
        background: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .stat-icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.05;
        transform: rotate(-15deg);
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Action Card (Mahasiswa) */
    .action-card {
        background: #fff;
        border: 1px dashed #00A19C;
        border-radius: 16px;
        background-color: rgba(0, 161, 156, 0.02);
    }

    /* Modern Table */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    
    .table-modern thead th {
        border: none;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #8898aa;
        padding-left: 1.5rem;
    }

    .table-modern tbody tr {
        background-color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        transition: transform 0.2s;
    }

    .table-modern tbody tr:hover {
        transform: scale(1.005);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .table-modern td {
        border: none;
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
    }

    .table-modern td:first-child { border-radius: 12px 0 0 12px; }
    .table-modern td:last-child { border-radius: 0 12px 12px 0; }

    /* Timeline */
    .timeline-modern {
        position: relative;
        padding-left: 30px;
    }

    .timeline-modern::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 5px;
        bottom: 0;
        width: 2px;
        background: #eff2f7;
    }

    .timeline-point {
        position: absolute;
        left: -30px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        border: 4px solid #e0e0e0;
        z-index: 1;
    }

    .timeline-item:hover .timeline-point {
        border-color: #00A19C;
    }
</style>
@endpush

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="hero-card p-4 p-lg-5">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <span class="badge bg-white text-primary bg-opacity-25 backdrop-blur mb-2 px-3 py-2 rounded-pill">
                        <i class="bi bi-stars me-1"></i> Dashboard Panel
                    </span>
                    <h1 class="display-5 fw-bold mb-2">Halo, {{ explode(' ', $user->name)[0] }}! 👋</h1>
                    <p class="lead opacity-75 mb-4">Selamat datang kembali di sistem informasi magang.</p>
                    
                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center">
                            <div class=" bg-opacity-20 p-2 rounded-circle me-3">
                                <i class="bi bi-clock-fill fs-1"></i>
                            </div>
                            <div>
                                <small class="d-block opacity-75">Waktu Sekarang</small>
                                <span id="palembangClock" class="fw-bold fs-5 font-monospace">--:--:--</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($weather)
                <div class="col-lg-5 text-lg-end">
                    <div class="d-inline-block text-start bg-white bg-opacity-10 backdrop-blur p-4 rounded-4 border border-white border-opacity-25">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-cloud-sun fs-1"></i>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $weather['temp'] }}°C</h2>
                                <p class="mb-0 opacity-75">{{ $weather['city'] }}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-top border-white border-opacity-25 d-flex justify-content-between gap-4">
                            <small>{{ $weather['condition'] }}</small>
                            <small><i class="bi bi-droplet me-1"></i>{{ $weather['humidity'] }}%</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4 g-4">
    @php
        $statConfig = [
            'total' => ['color' => 'primary', 'icon' => 'folder-fill', 'bg' => '#e8f4ff', 'text' => '#0d6efd'],
            'pending' => ['color' => 'warning', 'icon' => 'clock-history', 'bg' => '#fff8e1', 'text' => '#ffc107'],
            'approved' => ['color' => 'success', 'icon' => 'check-circle-fill', 'bg' => '#e6f8ed', 'text' => '#198754'],
            'rejected' => ['color' => 'danger', 'icon' => 'x-circle-fill', 'bg' => '#fee2e2', 'text' => '#dc3545'],
        ];
    @endphp

    @foreach($stats as $key => $value)
        @php $config = $statConfig[$key] ?? $statConfig['total']; @endphp
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 2;">
                    <div>
                        <p class="text-muted text-uppercase small fw-bold mb-2">{{ str_replace('_', ' ', $key) }}</p>
                        <h2 class="fw-bold mb-0 text-dark">{{ $value }}</h2>
                    </div>
                    <div class="icon-circle" style="background-color: {{ $config['bg'] }}; color: {{ $config['text'] }};">
                        <i class="bi bi-{{ $config['icon'] }}"></i>
                    </div>
                </div>
                <i class="bi bi-{{ $config['icon'] }} stat-icon-bg text-{{ $config['color'] }}"></i>
            </div>
        </div>
    @endforeach
</div>

@if($user->isMahasiswa())
    @php $pendingAcademic = $applications->firstWhere('status', 'disetujui_akademik'); @endphp
    @if($pendingAcademic)
    <div class="row mb-4">
        <div class="col-12">
            <div class="action-card p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="d-flex gap-3">
                        <div class="d-none d-md-block">
                            <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle">
                                <i class="bi bi-file-earmark-arrow-up fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Tindakan Diperlukan: Upload Dokumen</h5>
                            <p class="text-muted mb-0">Pengajuan ke <strong>{{ $pendingAcademic->company_name }}</strong> disetujui. Segera lengkapi administrasi.</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('internships.show', $pendingAcademic) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            Proses Sekarang <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark m-0">Pengajuan Terbaru</h5>
            <a href="{{ route('internships.index') }}" class="text-decoration-none small fw-bold">Lihat Semua</a>
        </div>
        
        @if($applications->isEmpty())
            <div class="card border-0 rounded-4 p-5 text-center shadow-sm">
                <img src="{{ asset('storage/images/empty.jpg') }}" alt="Empty" style="width: 120px; opacity: 0.5;" class="mb-3 mx-auto">
                <h6 class="text-muted">Belum ada data pengajuan</h6>
                @if($user->isMahasiswa())
                    <div class="mt-3">
                        <a href="{{ route('internships.create') }}" class="btn btn-outline-primary rounded-pill">Buat Pengajuan Baru</a>
                    </div>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th>Perusahaan / Posisi</th>
                            @if(!$user->isMahasiswa()) <th>Pemohon</th> @endif
                            <th class="d-none d-md-table-cell">Waktu</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications->take(5) as $app)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-building text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $app->company_name }}</div>
                                        <div class="small text-muted">Magang</div>
                                    </div>
                                </div>
                            </td>
                            @if(!$user->isMahasiswa())
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 25px; height: 25px; font-size: 10px;">
                                        {{ substr($app->student->name, 0, 1) }}
                                    </div>
                                    <span class="fw-semibold small">{{ explode(' ', $app->student->name)[0] }}</span>
                                </div>
                            </td>
                            @endif
                            <td class="d-none d-md-table-cell">
                                <small class="text-muted bg-light px-2 py-1 rounded">
                                    {{ $app->start_date->diffInDays($app->end_date) }} Hari
                                </small>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $app->status_color }} bg-opacity-10 text-{{ $app->status_color }} px-3 py-2">
                                    {{ $app->status_label }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('internships.show', $app) }}" class="btn btn-light btn-sm rounded-circle text-primary">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <h5 class="fw-bold text-dark mb-3">Aktivitas</h5>
        <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white">
            @if($recent_activities->isEmpty())
                <div class="text-center text-muted my-auto">Tidak ada aktivitas.</div>
            @else
                <div class="timeline-modern">
                    @foreach($recent_activities->take(6) as $activity)
                    <div class="timeline-item mb-4 position-relative">
                        <div class="timeline-point"></div>
                        <div class="ps-2">
                            <p class="mb-1 fw-bold text-dark small">{{ $activity->action }}</p>
                            <p class="text-muted small mb-1 lh-sm">{{ Str::limit($activity->description, 40) }}</p>
                            <small class="text-primary opacity-75" style="font-size: 0.7rem;">
                                {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            timeZone: 'Asia/Jakarta', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false
        });
        document.getElementById('palembangClock').innerText = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush