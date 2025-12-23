@extends('layouts.app')

@section('title', 'Buat Pengajuan Magang')

@push('styles')
<style>
    :root {
        --primary-color: #00A19C;
        --primary-hover: #008f8a;
        --bg-light: #f8f9fa;
    }

    /* Form Styling */
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-icon-wrapper {
        width: 35px;
        height: 35px;
        background-color: #e6f6f5;
        color: var(--primary-color);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    /* Input Styling */
    .input-group-text {
        background-color: #fff;
        border-right: none;
        color: #94a3b8;
    }
    
    .form-control, .form-select {
        border-left: none;
        padding: 0.7rem 1rem;
        font-size: 0.95rem;
    }
    
    .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
        border-left: 1px solid var(--primary-color) !important; 
        /* Trick to make left border appear on focus if needed, 
           or just keep standard focus ring but subtle */
    }
    
    .input-group:focus-within .input-group-text {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .input-group:focus-within .form-control {
        border-color: var(--primary-color);
    }

    /* Custom File Upload */
    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        background-color: #f8fafc;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
    }

    .upload-zone:hover {
        border-color: var(--primary-color);
        background-color: #f0fdfc;
    }

    .upload-icon {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }

    /* Card & Layout */
    .main-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .sticky-sidebar {
        position: sticky;
        top: 2rem;
    }
</style>
@endpush

@section('content')
<div class="row mb-4 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-dark mb-1">Pengajuan Magang Baru</h1>
        <p class="text-muted mb-0">Isi formulir di bawah ini untuk memulai proses pengajuan magang Anda.</p>
    </div>
</div>

<form action="{{ route('internships.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card main-card mb-4">
                <div class="card-body p-4">
                    <div class="form-section-title">
                        <div class="form-icon-wrapper"><i class="bi bi-building"></i></div>
                        Informasi Perusahaan
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">NAMA PERUSAHAAN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" 
                                       value="{{ old('company_name') }}" placeholder="PT. Contoh Sejahtera" required>
                            </div>
                            @error('company_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">KOTA <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="company_city" class="form-control @error('company_city') is-invalid @enderror" 
                                       value="{{ old('company_city') }}" placeholder="Contoh: Jakarta Selatan" required>
                            </div>
                            @error('company_city') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label small fw-bold text-secondary">ALAMAT LENGKAP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-map"></i></span>
                                <textarea name="company_address" class="form-control @error('company_address') is-invalid @enderror" 
                                          rows="3" placeholder="Jalan Jendral Sudirman No. 1..." required>{{ old('company_address') }}</textarea>
                            </div>
                            @error('company_address') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">NO. TELEPON</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone') }}" placeholder="021-XXXXXXX">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">EMAIL PERUSAHAAN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="company_email" class="form-control" value="{{ old('company_email') }}" placeholder="hrd@perusahaan.com">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-2">
                        <div class="d-flex gap-2">
                            <i class="bi bi-info-circle text-primary mt-1"></i>
                            <div>
                                <small class="fw-bold d-block text-dark">Koordinat Lokasi (Opsional)</small>
                                <small class="text-muted">Diisi jika Anda ingin lokasi terdeteksi di peta sistem.</small>
                            </div>
                        </div>
                        <div class="row mt-2 g-2">
                            <div class="col-6">
                                <input type="text" name="latitude" class="form-control form-control-sm" value="{{ old('latitude') }}" placeholder="Latitude (ex: -6.2088)">
                            </div>
                            <div class="col-6">
                                <input type="text" name="longitude" class="form-control form-control-sm" value="{{ old('longitude') }}" placeholder="Longitude (ex: 106.8456)">
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title mt-4">
                        <div class="form-icon-wrapper"><i class="bi bi-calendar-range"></i></div>
                        Periode & Detail Magang
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">TANGGAL MULAI <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date') }}" required>
                            @error('start_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">TANGGAL SELESAI <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date') }}" required>
                            @error('end_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">DESKRIPSI RENCANA KEGIATAN</label>
                            <textarea name="internship_description" class="form-control" rows="4" 
                                      placeholder="Jelaskan secara singkat posisi yang dilamar atau rencana kegiatan...">{{ old('internship_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <div class="card main-card mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-paperclip me-2 text-primary"></i>Dokumen Proposal</h6>
                        
                        <div class="mb-3">
                            <label class="form-label small text-muted">Upload Proposal Magang (PDF)</label>
                            <div class="upload-zone position-relative">
                                <input type="file" name="proposal" id="proposalInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".pdf" required onchange="updateFileName(this)">
                                <div id="uploadPlaceholder">
                                    <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                    <p class="mb-1 fw-bold text-dark small">Klik atau Tarik File</p>
                                    <p class="text-muted small mb-0">Format PDF, Maks 5MB</p>
                                </div>
                                <div id="fileSelected" class="d-none">
                                    <i class="bi bi-file-earmark-pdf text-danger fs-1"></i>
                                    <p class="mb-0 fw-bold text-dark small text-truncate px-2" id="fileNameDisplay"></p>
                                    <span class="badge bg-success bg-opacity-10 text-success mt-2">File Terpilih</span>
                                </div>
                            </div>
                            @error('proposal') <div class="text-danger small mt-1 text-center">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="card main-card bg-white">
                    <div class="card-body p-4">
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label small text-muted lh-sm" for="terms">
                                Saya menyatakan bahwa data yang saya isikan adalah benar, akurat, dan dapat dipertanggungjawabkan.
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold" style="background-color: var(--primary-color); border: none;">
                                <i class="bi bi-send-fill me-2"></i>Kirim Pengajuan
                            </button>
                            <a href="{{ route('internships.index') }}" class="btn btn-light text-muted py-2">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function updateFileName(input) {
        const placeholder = document.getElementById('uploadPlaceholder');
        const fileSelected = document.getElementById('fileSelected');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const zone = input.closest('.upload-zone');

        if (input.files && input.files[0]) {
            placeholder.classList.add('d-none');
            fileSelected.classList.remove('d-none');
            fileNameDisplay.textContent = input.files[0].name;
            zone.style.borderColor = '#198754'; // Green border
            zone.style.backgroundColor = '#f0fff4';
        } else {
            placeholder.classList.remove('d-none');
            fileSelected.classList.add('d-none');
            zone.style.borderColor = ''; // Reset
            zone.style.backgroundColor = '';
        }
    }
</script>
@endpush
@endsection