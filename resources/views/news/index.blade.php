@extends('layouts.app')

@section('title', 'Berita & Informasi')

@push('styles')
<style>
    :root {
        --primary-color: #00A19C;
        --secondary-color: #00D4CE;
        --card-bg: #ffffff;
        --text-primary: #2d3748;
        --text-muted: #718096;
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

    /* News Card */
    .news-card {
        border: none;
        border-radius: 16px;
        background: var(--card-bg);
        overflow: hidden;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
    }

    .news-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Image Wrapper & Zoom Effect */
    .news-img-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .news-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .news-card:hover .news-img {
        transform: scale(1.1);
    }

    /* Source Badge (Over Image) */
    .source-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary-color);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        z-index: 2;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Placeholder if no image */
    .news-placeholder {
        height: 100%;
        width: 100%;
        background: linear-gradient(135deg, #00A19C 0%, #4facfe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    /* Card Content */
    .news-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .news-date {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .news-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-desc {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Footer / Action */
    .news-footer {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
    }

    .btn-read {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: gap 0.2s;
    }

    .btn-read:hover {
        gap: 0.8rem;
        color: #008783;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        border: 2px dashed #e2e8f0;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-header">
            <div>
                <h1 class="h3 fw-bold mb-1 text-dark">Jendela Informasi</h1>
                <p class="text-muted mb-0">Berita terkini seputar dunia industri, magang, dan teknologi.</p>
            </div>
            <div class="d-none d-md-block text-primary opacity-25">
                <i class="bi bi-newspaper" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
</div>

@if($error)
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i>
            <div>
                <strong>Ups! Terjadi Kesalahan</strong><br>
                {{ $error }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(empty($news))
    <div class="empty-state">
        <div class="mb-3">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #cbd5e0;"></i>
        </div>
        <h5 class="fw-bold text-muted">Belum Ada Berita</h5>
        <p class="text-muted">Saat ini belum ada informasi terbaru yang dapat ditampilkan.</p>
    </div>
@else
    <div class="row g-4">
        @foreach($news as $article)
            <div class="col-md-6 col-lg-4">
                <div class="news-card">
                    <div class="news-img-wrapper">
                        @if($article['source']['name'])
                            <span class="source-badge">
                                {{ $article['source']['name'] }}
                            </span>
                        @endif

                        @if($article['urlToImage'])
                            <img src="{{ $article['urlToImage'] }}" class="news-img" alt="{{ $article['title'] }}" loading="lazy">
                        @else
                            <div class="news-placeholder">
                                <i class="bi bi-image" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                        @endif
                    </div>

                    <div class="news-body">
                        <div class="news-date">
                            <i class="bi bi-calendar4-week"></i>
                            {{ \Carbon\Carbon::parse($article['publishedAt'])->locale('id')->isoFormat('D MMMM Y') }}
                        </div>
                        
                        <h5 class="news-title" title="{{ $article['title'] }}">
                            {{ $article['title'] }}
                        </h5>
                        
                        <p class="news-desc">
                            {{ Str::limit($article['description'] ?? 'Klik tombol baca selengkapnya untuk melihat detail berita ini.', 100) }}
                        </p>
                        
                        <div class="news-footer">
                            <a href="{{ $article['url'] }}" target="_blank" class="btn-read">
                                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection