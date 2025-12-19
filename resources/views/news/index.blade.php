@extends('layouts.app')

@section('title', 'Berita')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3 mb-0">Berita Terkini</h1>
        <p class="text-muted mb-0">Berita terbaru seputar internship, magang, dan pendidikan</p>
    </div>
</div>

@if($error)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> {{ $error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(empty($news))
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Belum ada berita untuk ditampilkan
    </div>
@else
    <div class="row">
        @foreach($news as $article)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm hover-shadow" style="transition: all 0.3s;">
                    @if($article['urlToImage'])
                        <img src="{{ $article['urlToImage'] }}" class="card-img-top" alt="{{ $article['title'] }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div style="height: 200px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-image" style="font-size: 3rem; color: white; opacity: 0.5;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <p class="small text-muted mb-2">
                            <i class="bi bi-calendar"></i> 
                            {{ \Carbon\Carbon::parse($article['publishedAt'])->locale('id')->format('d M Y') }}
                        </p>
                        <h5 class="card-title">{{ Str::limit($article['title'], 60) }}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($article['description'] ?? '', 80) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            @if($article['source']['name'])
                                <small class="text-primary">{{ $article['source']['name'] }}</small>
                            @endif
                            <a href="{{ $article['url'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                Baca <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
    .hover-shadow {
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
    }
    
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0,161,156,0.15) !important;
        transform: translateY(-2px);
    }
</style>
@endsection
