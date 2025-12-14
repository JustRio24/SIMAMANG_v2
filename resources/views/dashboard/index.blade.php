@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h3 mb-0">Dashboard</h1>
        <p class="text-muted">Selamat datang, {{ $user->name }}</p>
    </div>
</div>

@if($weather)
<div class="card mb-4" style="background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); color: white;">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1"><i class="bi bi-geo-alt"></i> {{ $weather['city'] }}</h5>
                <h2 class="mb-0">{{ $weather['temp'] }}°C</h2>
                <p class="mb-0">{{ $weather['condition'] }} • Kelembaban: {{ $weather['humidity'] }}%</p>
            </div>
            <div class="col-md-4 text-md-end">
                <i class="bi bi-sun" style="font-size: 4rem; opacity: 0.8;"></i>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    @foreach($stats as $key => $value)
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 text-uppercase small">{{ str_replace('_', ' ', $key) }}</p>
                        <h3 class="mb-0">{{ $value }}</h3>
                    </div>
                    <div class="text-primary" style="font-size: 2.5rem; opacity: 0.2;">
                        <i class="bi bi-{{ $key == 'total' ? 'folder' : ($key == 'pending' ? 'clock' : ($key == 'approved' ? 'check-circle' : 'flag')) }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Recent Applications -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            @if($user->isMahasiswa())
                Pengajuan Magang Terbaru
            @else
                Pengajuan Perlu Perhatian
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
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Pengajuan</th>
                            @if(!$user->isMahasiswa())
                                <th>Mahasiswa</th>
                            @endif
                            <th>Perusahaan</th>
                            <th>Periode</th>
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
                            <td>{{ $app->start_date->format('d/m/Y') }} - {{ $app->end_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $app->status_color }}">
                                    {{ $app->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('internships.show', $app) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detail
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

<!-- Recent Activity -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h5>
    </div>
    <div class="card-body">
        @if($recent_activities->isEmpty())
            <p class="text-muted text-center py-3">Belum ada aktivitas</p>
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