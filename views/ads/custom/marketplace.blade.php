@extends('theme::layouts.master')

@section('content')
@include('theme::ads.custom.partials.styles')

<div class="container py-4">
    <!-- Header Banner -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient text-white rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #0ea5e9 100%);">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-store fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.custom_ads_marketplace') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.custom_ads_marketplace_intro') }}</p>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a class="btn btn-outline-secondary" href="{{ route('ads.custom.index') }}">
                <i class="fa fa-arrow-left me-1"></i> {{ __('messages.custom_ads') }}
            </a>
        </div>
        <div>
            <a href="{{ route('ads.custom.placements.create') }}" class="btn btn-success">
                <i class="fa fa-plus me-1"></i> {{ __('messages.custom_ads_publish_space') }}
            </a>
        </div>
    </div>

    <!-- Grid -->
    <div class="row g-4">
        @forelse($placements as $placement)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body d-flex flex-column h-100">
                        <div class="d-flex gap-1 mb-3 flex-wrap">
                            <span class="badge bg-light text-dark border">{{ __('messages.custom_ads_format_' . $placement->format) }}</span>
                            <span class="badge bg-light text-dark border">{{ $placement->size }}</span>
                        </div>
                        <h4 class="card-title fw-bold text-dark mb-2">{{ $placement->name }}</h4>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ \Illuminate\Support\Str::limit($placement->description ?: $placement->site_url, 140) }}
                        </p>
                        <div class="text-muted small mb-3">
                            <i class="fa fa-user me-1"></i> {{ __('messages.publisher') }}: <strong>{{ $placement->user?->username }}</strong>
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10"><i class="fa fa-eye me-1"></i>{{ $placement->impressions }}</span>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10"><i class="fa fa-handshake me-1"></i>{{ $placement->active_deals_count }} {{ __('messages.active') }}</span>
                        </div>
                        <a href="{{ route('ads.custom.placements.request', $placement) }}" class="btn btn-primary w-100 mt-auto">{{ __('messages.custom_ads_request_deal') }}</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm p-5 text-center">
                    <h4 class="fw-bold mb-2">{{ __('messages.custom_ads_marketplace_empty_title') }}</h4>
                    <p class="text-muted mb-0">{{ __('messages.custom_ads_marketplace_empty_desc') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $placements->links() }}
    </div>
</div>
@endsection
