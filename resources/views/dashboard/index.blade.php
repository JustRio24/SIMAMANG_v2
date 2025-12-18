@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="h3 mb-0">Dashboard</h1>
        <p class="text-muted mb-0">Selamat datang, {{ $user->name }}</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="d-inline-block mb-0" style="background: transparant; color: black;">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center">
                    
                    <div>
                        <small class="d-block" style="opacity: 0.8;">Waktu Indonesia Barat (WIB)</small>
                        <strong id="palembangClock" style="font-size: 1.25rem;">--:--:--</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($weather)
<div class="card mb-4" style="background: linear-gradient(135deg, #00A19C  0%, #00D4CE  100%); color: white;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1"><i class="bi bi-geo-alt"></i> {{ $weather['city'] }}</h5>
                <h2 class="mb-0">{{ $weather['temp'] }}°C</h2>
                <p class="mb-0">{{ $weather['condition'] }} | Kelembaban: {{ $weather['humidity'] }}%</p>
            </div>
            <div class="col-md-4 text-md-end">
                <i class="bi bi-sun" style="font-size: 4rem; opacity: 0.8;"></i>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mb-4">
    @foreach($stats as $key => $value)
    <div class="col-6 col-lg-3 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase small">{{ str_replace('_', ' ', $key) }}</p>
                        <h3 class="mb-0">{{ $value }}</h3>
                    </div>
                    <div class="text-primary" style="font-size: 2rem; opacity: 0.3;">
                        <i class="bi bi-{{ $key == 'total' ? 'folder' : ($key == 'pending' ? 'clock' : ($key == 'approved' ? 'check-circle' : 'flag')) }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h5 class="mb-0">
            @if($user->isMahasiswa())
                <i class="bi bi-file-earmark-text me-2"></i>Pengajuan Magang Terbaru
            @else
                <i class="bi bi-exclamation-circle me-2"></i>Pengajuan Perlu Perhatian
            @endif
        </h5>
        <a href="{{ route('internships.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="card-body">
        @if($applications->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3">Belum ada pengajuan</p>
                @if($user->isMahasiswa())
                    <a href="{{ route('internships.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> Buat Pengajuan Baru
                    </a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            @if(!$user->isMahasiswa())
                                <th>Mahasiswa</th>
                            @endif
                            <th>Perusahaan</th>
                            <th class="d-none d-md-table-cell">Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications->take(5) as $app)
                        <tr>
                            <td><strong>#{{ $app->id }}</strong></td>
                            @if(!$user->isMahasiswa())
                                <td>
                                    {{ $app->student->name }}<br>
                                    <small class="text-muted">{{ $app->student->nim }}</small>
                                </td>
                            @endif
                            <td>{{ $app->company_name }}</td>
                            <td class="d-none d-md-table-cell">
                                <small>{{ $app->start_date->format('d/m/Y') }} - {{ $app->end_date->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $app->status_color }}">
                                    {{ $app->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('internships.show', $app) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i><span class="d-none d-sm-inline"> Detail</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h5>
    </div>
    <div class="card-body">
        @if($recent_activities->isEmpty())
            <p class="text-muted text-center py-3 mb-0">Belum ada aktivitas</p>
        @else
            <div class="timeline">
                @foreach($recent_activities as $activity)
                <div class="timeline-item">
                    <div>
                        <strong>{{ $activity->action }}</strong>
                        <p class="text-muted mb-1">{{ $activity->description }}</p>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> {{ $activity->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function updatePalembangClock() {
    const options = {
        timeZone: 'Asia/Jakarta',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    };
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID', options);
    document.getElementById('palembangClock').textContent = timeString;
}

updatePalembangClock();
setInterval(updatePalembangClock, 1000);
</script>
@endpush
