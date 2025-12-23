@extends('layouts.app')

@section('title', 'Buat Pengajuan Magang')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-0">Buat Pengajuan Magang Baru</h1>
    <p class="text-muted">Lengkapi formulir di bawah ini dengan data yang akurat</p>
</div>

<form action="{{ route('internships.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-building"></i> Data Perusahaan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Perusahaan *</label>
                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" 
                           value="{{ old('company_name') }}" required>
                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kota *</label>
                    <input type="text" name="company_city" class="form-control @error('company_city') is-invalid @enderror" 
                           value="{{ old('company_city') }}" required>
                    @error('company_city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Alamat Lengkap *</label>
                <textarea name="company_address" class="form-control @error('company_address') is-invalid @enderror" 
                          rows="3" required>{{ old('company_address') }}</textarea>
                @error('company_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. Telepon Perusahaan</label>
                    <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone') }}">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Perusahaan</label>
                    <input type="email" name="company_email" class="form-control" value="{{ old('company_email') }}">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Latitude (opsional)</label>
                    <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}" 
                           placeholder="contoh: -2.9761">
                    <small class="text-muted">Untuk integrasi Google Maps</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Longitude (opsional)</label>
                    <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}" 
                           placeholder="contoh: 104.7754">
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-calendar"></i> Periode Magang</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai *</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Selesai *</label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                           value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Deskripsi Magang (opsional)</label>
                <textarea name="internship_description" class="form-control" rows="4" 
                          placeholder="Jelaskan posisi, tugas, atau kegiatan yang akan dilakukan...">{{ old('internship_description') }}</textarea>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Upload Dokumen</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Proposal Magang * <small class="text-muted">(PDF, max 5MB)</small></label>
                <input type="file" name="proposal" class="form-control @error('proposal') is-invalid @enderror" 
                       accept=".pdf" required>
                @error('proposal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mt-1">
                    <i class="bi bi-info-circle"></i> Proposal harus berisi tujuan, rencana kegiatan, dan manfaat magang
                </small>
            </div>
            
            {{-- <div class="mb-3">
                <label class="form-label">Draft Surat Pengantar * <small class="text-muted">(PDF/DOC, max 5MB)</small></label>
                <input type="file" name="surat_pengantar" class="form-control @error('surat_pengantar') is-invalid @enderror" 
                       accept=".pdf,.doc,.docx" required>
                @error('surat_pengantar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mt-1">
                    <i class="bi bi-info-circle"></i> Template surat dapat diunduh di menu Template Dokumen
                </small>
            </div> --}}
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label" for="terms">
                    Saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan
                </label>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Submit Pengajuan
                </button>
                <a href="{{ route('internships.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection