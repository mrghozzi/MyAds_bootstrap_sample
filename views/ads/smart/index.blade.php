@extends('theme::layouts.master')

@section('content')
<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, rgba(15,23,42,.96) 0%, rgba(29,78,216,.94) 56%, rgba(14,165,233,.88) 100%);">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-3" src="{{ theme_asset('img/banner/banner_ads.png') }}" alt="smart-ads" style="width: 60px; height: auto;">
                    <div>
                        <p class="text-white fs-4 fw-bold mb-1">{{ __('messages.smart_ads') }}</p>
                        <p class="text-white-50 mb-0"><b>{{ __('messages.smart_index_byline') }}</b></p>
                    </div>
                </div>

                <a class="btn btn-light rounded-pill fw-bold" href="{{ route('ads.smart.code') }}">
                    <i class="fa fa-code" aria-hidden="true"></i>&nbsp;{{ __('messages.code') }}
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif

<div class="section-filters-bar v6">
    <div class="section-filters-bar-actions">
        <a class="button tertiary" href="{{ route('legacy.state', ['ty' => 'smart', 'st' => 'vu']) }}"><i class="fa fa-line-chart" aria-hidden="true"></i></a>
    </div>
    <p class="text-sticker">
        <svg class="text-sticker-icon icon-info">
            <use xlink:href="#svg-info"></use>
        </svg>
        {{ __('messages.smart_you_have_credits', ['credits' => number_format((float) $user->nsmart, 2)]) }}
    </p>
    <div class="section-filters-bar-actions">
        <a href="{{ route('ads.smart.create') }}" class="button secondary" style="color: #fff;">
            <i class="fa fa-plus nav_icon"></i>&nbsp;{{ __('messages.create') }}
        </a>
    </div>
    <div>
        <a href="{{ route('ads.smart.create') }}" class="btn btn-primary">
            <i class="fa fa-plus me-1"></i> {{ __('messages.create') }}
        </a>
        <a href="{{ route('ads.smart.code') }}" class="btn btn-secondary">
            <i class="fa fa-code me-1"></i> {{ __('messages.code') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if($smartAds->isEmpty())
            <div class="card border-0 shadow-sm p-5 text-center bg-light border-dashed">
                <h4 class="mb-2">{{ __('messages.smart_empty_title') }}</h4>
                <p class="text-muted mb-3">{{ __('messages.smart_empty_desc') }}</p>
                <a href="{{ route('ads.smart.create') }}" class="btn btn-primary">{{ __('messages.smart_create_ad') }}</a>
            </div>
        @else
            <div class="d-flex flex-column gap-3">
                @foreach($smartAds as $smartAd)
                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <div class="row align-items-start">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ __('messages.smart_ad') }}</span>
                                    <small class="text-muted">#{{ $smartAd->id }}</small>
                                    <small class="{{ (int) $smartAd->statu === 1 ? 'text-success' : 'text-warning' }}">{{ (int) $smartAd->statu === 1 ? __('messages.active') : __('messages.smart_status_paused') }}</small>
                                </div>

                                <div class="d-flex gap-3">
                                    @if($smartAd->displayImage())
                                        <div style="width: 100px; height: 100px;" class="flex-shrink-0">
                                            <img src="{{ $smartAd->displayImage() }}" class="img-fluid rounded-3" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-2">{{ $smartAd->displayTitle() }}</h5>
                                        <p class="text-muted mb-2 small">{{ \Illuminate\Support\Str::limit($smartAd->displayDescription(), 200) }}</p>
                                        <a href="{{ $smartAd->landing_url }}" target="_blank" class="text-primary text-decoration-none small">{{ $smartAd->landing_url }}</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <div class="bg-light p-3 rounded-3 mb-3 text-start">
                                    <p class="text-uppercase fw-bold text-primary small mb-1">{{ __('messages.smart_targets') }}</p>
                                    <p class="text-muted small mb-0">{{ __('messages.smart_target_countries_label') }}: {{ \App\Support\SmartAdTargeting::formatTargets($smartAd->targetCountries()) }}</p>
                                    <p class="text-muted small mb-0">{{ __('messages.smart_target_devices_label') }}: {{ \App\Support\SmartAdTargeting::formatTargets($smartAd->targetDevices()) }}</p>
                                </div>
                                <div class="d-flex justify-content-lg-end gap-2 mb-3">
                                    <a href="{{ route('legacy.state', ['ty' => 'smart', 'id' => $smartAd->id]) }}" class="btn btn-light text-center px-4">
                                        <div class="h5 mb-0 text-primary">{{ $smartAd->impressions }}</div>
                                        <small class="text-muted">{{ __('messages.smart_impressions_label') }}</small>
                                    </a>
                                    <a href="{{ route('legacy.state', ['ty' => 'smart_click', 'id' => $smartAd->id]) }}" class="btn btn-light text-center px-4">
                                        <div class="h5 mb-0 text-success">{{ $smartAd->clicks }}</div>
                                        <small class="text-muted">{{ __('messages.smart_clicks_label') }}</small>
                                    </a>
                                </div>
                                <div class="d-flex justify-content-lg-end gap-2">
                                    <a href="{{ route('ads.smart.edit', $smartAd->id) }}" class="btn btn-outline-secondary">{{ __('messages.edit') }}</a>
                                    <form action="{{ route('ads.smart.destroy', $smartAd->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.smart_delete_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('messages.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
