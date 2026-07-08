@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-info bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                    <i class="fa fa-sitemap fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-bold mb-1">{{ __('messages.directory') }}</h1>
                    <p class="mb-0 text-white-50 small">{{ __('messages.landing_community_directory_desc') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3 mt-md-0">
                <a href="{{ route('directory.create') }}" class="btn btn-light btn-lg fw-bold shadow-sm">
                    <i class="fa fa-plus me-2"></i> {{ __('messages.addWebsite') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Left -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.categories') }}</h6>
                </div>
                <div class="card-body p-0">
                    @include('theme::directory.partials.category_board', ['categoryBoard' => $categoryBoard])
                </div>
            </div>
            <x-widget-column side="directory_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="h4 fw-bold mb-1">{{ __('messages.latest_sites') }}</h2>
                            <p class="text-muted small mb-0">{{ __('messages.landing_community_directory_desc') }}</p>
                        </div>
                    </div>
                    
                    <div class="row g-3 text-center mb-4">
                        <div class="col">
                            <div class="p-3 bg-light rounded-3">
                                <h4 class="fw-bold mb-0">{{ $directoryStats['listing_count'] }}</h4>
                                <small class="text-muted text-uppercase smaller">{{ __('messages.latest_sites') }}</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3 bg-light rounded-3">
                                <h4 class="fw-bold mb-0">{{ $directoryStats['category_count'] }}</h4>
                                <small class="text-muted text-uppercase smaller">{{ __('messages.cat_s') }}</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3 bg-light rounded-3">
                                <h4 class="fw-bold mb-0">{{ $directoryStats['subcategory_count'] }}</h4>
                                <small class="text-muted text-uppercase smaller">{{ __('messages.subcategories') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="infinite-scroll-container">
                <div id="timeline-content">
                    @if($cards->isNotEmpty())
                        @include('theme::directory.partials.feed_items', ['cards' => $cards])
                    @else
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-5 text-center text-muted">
                                <i class="fa fa-search fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">{{ __('messages.no_listings_found') }}</p>
                            </div>
                        </div>
                    @endif
                    @include('theme::partials.ajax.infinite_scroll', ['paginator' => $activities])
                </div>
            </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>{{ __('messages.latest_sites') }}</span>
                            <strong class="text-primary">{{ $directoryStats['listing_count'] }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>{{ __('messages.cat_s') }}</span>
                            <strong class="text-primary">{{ $directoryStats['category_count'] }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>{{ __('messages.subcategories') }}</span>
                            <strong class="text-primary">{{ $directoryStats['subcategory_count'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <x-widget-column side="directory_right" />
        </div>
    </div>
</div>
@endsection
