@extends('layouts.app')

@section('title', 'Daftar Pengajuan Magang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Daftar Pengajuan Magang</h1>
        <p class="text-muted">Kelola pengajuan magang Anda</p>
    </div>
    @if(auth()->user()->isMahasiswa())
    <a href="{{ route('internships.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Buat Pengajuan Baru
    </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        @if($applications->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3; color: #94a3b8;"></i>
                <h5 class="mt-3 text-muted">Belum Ada Pengajuan</h5>
                @if(auth()->user()->isMahasiswa())
                    <p class="text-muted">Mulai dengan membuat pengajuan magang pertama Anda</p>
                    <a href="{{ route('internships.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> Buat Pengajuan
                    </a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            @if(!auth()->user()->isMahasiswa())
                                <th>Mahasiswa</th>
                            @endif
                            <th>Perusahaan</th>
                            <th>Lokasi</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                        <tr>
                            <td><strong>#{{ $app->id }}</strong></td>
                            @if(!auth()->user()->isMahasiswa())
                                <td>
                                    <strong>{{ $app->student->name }}</strong><br>
                                    <small class="text-muted">{{ $app->student->nim }}</small>
                                </td>
                            @endif
                            <td>
                                <strong>{{ $app->company_name }}</strong>
                            </td>
                            <td>{{ $app->company_city }}</td>
                            <td>
                                <small>
                                    {{ $app->start_date->format('d/m/Y') }}<br>
                                    {{ $app->end_date->format('d/m/Y') }}<br>
                                    <span class="text-muted">({{ $app->duration_months }} bulan)</span>
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-status bg-{{ $app->status_color }}">
                                    {{ $app->status_label }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $app->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('internships.show', $app) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->isMahasiswa() && in_array($app->status, ['diajukan', 'revisi']))
                                        <a href="{{ route('internships.edit', $app) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection