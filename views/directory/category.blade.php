@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-info bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3 shadow-sm">
                    <i class="fa fa-sitemap fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-black mb-1">{{ $category->name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('directory.index') }}" class="text-white text-opacity-75 text-decoration-none small">{{ __('messages.directory') }}</a></li>
                            <li class="breadcrumb-item active text-white small" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3 mt-md-0">
                <a href="{{ route('directory.create') }}" class="btn btn-light btn-lg fw-bold shadow-sm px-4 rounded-pill">
                    <i class="fa fa-plus me-2 text-info"></i> {{ __('messages.addWebsite') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Left -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.board') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('directory.index') }}" class="list-group-item list-group-item-action py-3 border-0 transition-all">
                            <i class="fa fa-home me-2 text-info opacity-50"></i> <span class="small fw-bold">{{ __('messages.directory') }}</span>
                        </a>
                        <a href="{{ route('directory.create') }}" class="list-group-item list-group-item-action py-3 border-0 transition-all">
                            <i class="fa fa-plus me-2 text-success opacity-50"></i> <span class="small fw-bold">{{ __('messages.addWebsite') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            @if($categorySummary['subcategories']->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.subcategories') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @foreach($categorySummary['subcategories'] as $subCategory)
                                <a href="{{ $subCategory['url'] }}" class="btn btn-outline-info btn-sm rounded-pill px-3 py-2 text-start d-flex justify-content-between align-items-center transition-all hover-bg-info hover-text-white">
                                    <span class="fw-bold small">{{ $subCategory['category']->name }}</span>
                                    <span class="badge bg-info text-white rounded-pill">{{ $subCategory['listing_count'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <x-widget-column side="directory_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="h4 fw-black mb-1 text-dark">{{ __('messages.latest_sites') }}</h2>
                            <p class="text-muted small mb-0 opacity-75">{{ $category->name }}</p>
                        </div>
                    </div>
                    
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-3 bg-light bg-opacity-50 rounded-4 border shadow-sm">
                                <h4 class="fw-black mb-0 text-info">{{ number_format($categorySummary['listing_count']) }}</h4>
                                <small class="text-muted text-uppercase smaller fw-bold letter-spacing-1">{{ __('messages.latest_sites') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light bg-opacity-50 rounded-4 border shadow-sm">
                                <h4 class="fw-black mb-0 text-info">{{ number_format($categorySummary['subcategory_count']) }}</h4>
                                <small class="text-muted text-uppercase smaller fw-bold letter-spacing-1">{{ __('messages.subcategories') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="infinite-scroll-container">
                <div id="timeline-content" class="d-grid gap-3">
                    @if($cards->isNotEmpty())
                        @include('theme::directory.partials.feed_items', ['cards' => $cards])
                    @else
                        <div class="card border-0 shadow-sm rounded-4 mb-4 p-5 text-center bg-light bg-opacity-50">
                            <div class="mb-3">
                                <i class="fa fa-search fa-4x text-muted opacity-25"></i>
                            </div>
                            <h5 class="fw-bold text-muted">{{ __('messages.no_listings_found') }}</h5>
                            <p class="text-muted small mb-0">{{ __('messages.be_the_first_to_post') }}</p>
                        </div>
                    @endif
                    @include('theme::partials.ajax.infinite_scroll', ['paginator' => $activities])
                </div>
            </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-3">
            <x-widget-column side="directory_right" />
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-bg-info:hover { background-color: #0dcaf0 !important; }
    .hover-text-white:hover { color: #fff !important; }
    .list-group-item-action:hover {
        background-color: rgba(13, 202, 240, 0.05);
        color: #0dcaf0;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.5);
    }
</style>
@endsection
