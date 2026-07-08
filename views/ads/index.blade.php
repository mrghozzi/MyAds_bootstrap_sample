@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                <i class="fa fa-bullhorn fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-bold mb-1">{{ __('messages.advertising') }}</h1>
                <p class="mb-0 text-white-50 small">{{ __('messages.manage_ads') }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ads.banners.index') }}" class="btn btn-info w-100 py-3 rounded-4 shadow-sm text-white fw-bold d-flex flex-column align-items-center gap-2 transition-all hover-translate-y">
                <i class="fa fa-image fs-3"></i>
                <span class="smaller">{{ __('messages.my_banners') }}</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ads.links.index') }}" class="btn btn-success w-100 py-3 rounded-4 shadow-sm text-white fw-bold d-flex flex-column align-items-center gap-2 transition-all hover-translate-y">
                <i class="fa fa-link fs-3"></i>
                <span class="smaller">{{ __('messages.my_links') }}</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ads.smart.index') }}" class="btn btn-dark w-100 py-3 rounded-4 shadow-sm text-white fw-bold d-flex flex-column align-items-center gap-2 transition-all hover-translate-y">
                <i class="fa fa-crosshairs fs-3"></i>
                <span class="smaller">{{ __('messages.smart_ads') }}</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ads.promote') }}" class="btn btn-warning w-100 py-3 rounded-4 shadow-sm text-white fw-bold d-flex flex-column align-items-center gap-2 transition-all hover-translate-y">
                <i class="fa fa-bullhorn fs-3"></i>
                <span class="smaller">{{ __('messages.promote_your_site') }}</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('ads.posts.index') }}" class="btn btn-primary w-100 py-3 rounded-4 shadow-sm text-white fw-bold d-flex flex-column align-items-center gap-2 transition-all hover-translate-y">
                <i class="fa fa-rocket fs-3"></i>
                <span class="smaller">{{ __('messages.status_promotions_title') }}</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('youtube.advertiser.index') }}" class="btn btn-danger w-100 py-3 rounded-4 shadow-sm text-white fw-bold d-flex flex-column align-items-center gap-2 transition-all hover-translate-y">
                <i class="fa-brands fa-youtube fs-3"></i>
                <span class="smaller">{{ __('messages.yt_campaigns') }}</span>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('dashboard') }}" class="btn btn-light border w-100 py-3 rounded-4 shadow-sm text-dark fw-bold d-flex flex-column align-items-center gap-2 transition-all hover-translate-y">
                <i class="fa fa-home fs-3 opacity-50"></i>
                <span class="smaller">{{ __('messages.home') }}</span>
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-info">
                <h3 class="fw-bold mb-0 text-info">{{ $banners->count() }}</h3>
                <small class="text-muted text-uppercase smaller fw-bold">{{ __('messages.latest_banners') }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-success">
                <h3 class="fw-bold mb-0 text-success">{{ $links->count() }}</h3>
                <small class="text-muted text-uppercase smaller fw-bold">{{ __('messages.latest_links') }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-primary">
                <h3 class="fw-bold mb-0 text-primary">{{ $banners->where('statu', 1)->count() + $links->where('statu', 1)->count() }}</h3>
                <small class="text-muted text-uppercase smaller fw-bold">{{ __('messages.active') }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3 border-start border-4 border-warning">
                <h3 class="fw-bold mb-0 text-warning">{{ $banners->where('statu', '!=', 1)->count() + $links->where('statu', '!=', 1)->count() }}</h3>
                <small class="text-muted text-uppercase smaller fw-bold">{{ __('messages.pending') }}</small>
            </div>
        </div>
    </div>

    <!-- Banners Table -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fa fa-image me-2 text-info"></i>{{ __('messages.latest_banners') }}</h5>
            <a href="{{ route('ads.banners.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">{{ __('messages.see_all') }}</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">#{{ __('messages.id') }}</th>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">{{ __('messages.img') }}</th>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">{{ __('messages.stats') }}</th>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td class="px-4 text-muted small">{{ $banner->id }}</td>
                            <td class="px-4">
                                <img src="{{ asset($banner->img) }}" class="rounded shadow-sm" style="max-height: 40px; max-width: 150px; object-fit: contain;">
                            </td>
                            <td class="px-4">
                                <span class="badge bg-info bg-opacity-10 text-info me-1"><i class="fa fa-eye me-1"></i>{{ $banner->vu }}</span>
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa fa-mouse-pointer me-1"></i>{{ $banner->clik }}</span>
                            </td>
                            <td class="px-4">
                                @if($banner->statu == 1)
                                    <span class="badge bg-success rounded-pill px-3">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge bg-warning rounded-pill px-3">{{ __('messages.pending') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-5 text-center text-muted">
                                <i class="fa fa-image fa-3x mb-3 opacity-10"></i>
                                <p class="mb-0">{{ __('messages.no_banners') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Links Table -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fa fa-link me-2 text-success"></i>{{ __('messages.latest_links') }}</h5>
            <a href="{{ route('ads.links.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">{{ __('messages.see_all') }}</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">#{{ __('messages.id') }}</th>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">{{ __('messages.name') }}</th>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">{{ __('messages.stats') }}</th>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">{{ __('messages.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($links as $link)
                        <tr>
                            <td class="px-4 text-muted small">{{ $link->id }}</td>
                            <td class="px-4">
                                <div class="fw-bold text-dark">{{ $link->name }}</div>
                                <div class="smaller text-muted text-truncate" style="max-width: 250px;">{{ $link->url }}</div>
                            </td>
                            <td class="px-4">
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fa fa-mouse-pointer me-1"></i>{{ $link->clik }}</span>
                            </td>
                            <td class="px-4">
                                @if($link->statu == 1)
                                    <span class="badge bg-success rounded-pill px-3">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge bg-warning rounded-pill px-3">{{ __('messages.pending') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-5 text-center text-muted">
                                <i class="fa fa-link fa-3x mb-3 opacity-10"></i>
                                <p class="mb-0">{{ __('messages.no_links') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Smart Ads Section -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-dark text-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fa fa-crosshairs me-2 text-info"></i>{{ __('messages.smart_ads') }}</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('ads.smart.code') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">{{ __('messages.code') }}</a>
                <a href="{{ route('ads.smart.index') }}" class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-white">{{ __('messages.see_all') }}</a>
            </div>
        </div>
        <div class="card-body p-4">
            @forelse($smartAds as $smartAd)
                <div class="card border rounded-4 mb-3 hover-shadow-sm transition-all">
                    <div class="card-body p-3">
                        <div class="row align-items-center g-3">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-info bg-opacity-10 text-info smaller fw-bold text-uppercase">{{ __('messages.smart_ad') }}</span>
                                    <span class="text-muted smaller">#{{ $smartAd->id }}</span>
                                </div>
                                <h6 class="fw-bold mb-1">{{ $smartAd->displayTitle() }}</h6>
                                <p class="text-muted small mb-2 text-truncate">{{ $smartAd->displayDescription() }}</p>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-light text-muted border fw-normal">{{ \App\Support\SmartAdTargeting::formatTargets($smartAd->targetCountries()) }}</span>
                                    <span class="badge bg-light text-muted border fw-normal">{{ \App\Support\SmartAdTargeting::formatTargets($smartAd->targetDevices()) }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="d-flex justify-content-md-end gap-3 mb-3">
                                    <div class="text-center">
                                        <div class="fw-bold text-primary fs-5">{{ $smartAd->impressions }}</div>
                                        <div class="smaller text-muted text-uppercase">{{ __('messages.smart_impressions_label') }}</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="fw-bold text-success fs-5">{{ $smartAd->clicks }}</div>
                                        <div class="smaller text-muted text-uppercase">{{ __('messages.smart_clicks_label') }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('ads.smart.edit', $smartAd->id) }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold border">
                                    <i class="fa fa-edit me-1 text-primary"></i> {{ __('messages.edit') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-light rounded-4 p-5 text-center">
                    <i class="fa fa-crosshairs fa-3x mb-3 text-muted opacity-25"></i>
                    <h5 class="fw-bold">{{ __('messages.smart_create_first_title') }}</h5>
                    <p class="text-muted small mb-4">{{ __('messages.smart_create_first_desc') }}</p>
                    <a href="{{ route('ads.smart.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa fa-plus me-1"></i> {{ __('messages.smart_create_ad') }}
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.transition-all { transition: all 0.3s ease; }
.hover-translate-y:hover { transform: translateY(-5px); }
.hover-shadow-sm:hover { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
.smaller { font-size: 0.75rem; }
</style>
@endsection
