@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%; background-size: cover;">
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}" alt="overview-icon">
    <p class="section-banner-title">{{ __('messages.yt_exchange') }}</p>
    <p class="section-banner-text">{{ __('messages.yt_watch_earn') }}</p>
</div>

<div class="d-flex flex-wrap gap-2 mt-4 mb-3">
    <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4">
        <i class="fa fa-arrow-left me-2"></i>{{ __('messages.back') }}
    </a>
    <a href="{{ route('youtube.advertiser.index') }}" class="btn btn-primary rounded-pill fw-bold px-4">
        <i class="fa-brands fa-youtube me-2"></i>{{ __('messages.yt_my_campaigns') }}
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm">{{ $errors->first() }}</div>
@endif

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4 p-md-5">
        <h4 class="fw-bold mb-4 text-dark"><i class="fa-brands fa-youtube text-danger me-2"></i> {{ __('messages.yt_available_videos') }}</h4>
        
        <div class="row g-4">
            @forelse($videos as $video)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 border rounded-4 overflow-hidden shadow-sm hover-shadow transition-all">
                        <div class="position-relative">
                            <img src="{{ $video->thumbnail_url }}" alt="Thumbnail" class="card-img-top object-fit-cover" style="height: 160px;">
                            <span class="position-absolute bottom-0 end-0 bg-dark text-white rounded-2 px-2 py-1 m-2 small fw-bold" style="font-size: 0.75rem;">
                                {{ $video->duration_required }}s
                            </span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="fw-bold text-dark text-truncate mb-3" title="{{ $video->title ?? 'YouTube Video' }}">{{ $video->title ?? 'YouTube Video' }}</h6>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">
                                    +{{ $video->reward_points }} PTS
                                </span>
                                <a href="{{ route('youtube.exchange.watch', $video->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                    <i class="fa fa-play me-1"></i> {{ __('messages.yt_watch') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa-brands fa-youtube display-3 text-muted opacity-25 mb-3"></i>
                    <p class="fw-bold text-muted mb-0">{{ __('messages.yt_no_videos') }}</p>
                </div>
            @endforelse
        </div>

        @if($videos->hasPages())
            <div class="mt-4 pt-3 border-top">
                {{ $videos->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-4px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .object-fit-cover { object-fit: cover; }
</style>
@endsection
