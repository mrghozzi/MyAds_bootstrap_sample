@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: linear-gradient(135deg, rgba(15,23,42,.96) 0%, rgba(29,78,216,.94) 56%, rgba(14,165,233,.88) 100%);">
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/banner_ads.png') }}" alt="overview-icon">
    <p class="section-banner-title">{{ __('messages.smart_create_title') }}</p>
    <p class="section-banner-text">{{ __('messages.smart_create_desc') }}</p>
</div>

<div class="row">
    <div class="col-lg-3 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body" style="display: grid; gap: 12px;">
                <a href="{{ route('ads.smart.index') }}" class="btn btn-primary fw-bold rounded-pill w-100">{{ __('messages.back') }}</a>
                <div style="padding: 16px; border-radius: 18px; background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);">
                    <p class="mb-2 fw-bold text-dark">{{ __('messages.smart_ads_credits') }}</p>
                    <p class="mb-0 fs-4 fw-bolder text-primary">{{ number_format((float) auth()->user()->nsmart, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        @include('theme::ads.smart._form', [
            'smartAd' => $smartAd,
            'formAction' => route('ads.smart.store'),
            'formMethod' => 'POST',
            'submitLabel' => __('messages.smart_create_ad'),
            'targetCountries' => $targetCountries,
            'selectedDevices' => $selectedDevices,
            'deviceOptions' => $deviceOptions,
        ])
    </div>
</div>
@endsection
