@extends('theme::layouts.master')

@section('content')
@include('theme::ads.custom.partials.styles')

<div class="container py-4">
    <!-- Header Banner -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient text-white rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-code fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.custom_ads_embed_code') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ $placement->name }}</p>
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
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('ads.custom.placements.edit', $placement) }}">{{ __('messages.edit') }}</a>
            <a class="btn btn-success" href="{{ route('ads.custom.placements.invite', $placement) }}">{{ __('messages.custom_ads_invite') }}</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-2">{{ __('messages.custom_ads_impressions') }}</h6>
                    <div class="h3 fw-bold text-teal" style="color: #0f766e;">{{ number_format($summary['impressions']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-2">{{ __('messages.custom_ads_clicks') }}</h6>
                    <div class="h3 fw-bold text-teal" style="color: #0f766e;">{{ number_format($summary['clicks']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-2">CTR</h6>
                    <div class="h3 fw-bold text-teal" style="color: #0f766e;">{{ $summary['ctr'] }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Embed Code Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title mb-3 fw-bold">{{ __('messages.custom_ads_embed_code') }}</h4>
            <p class="text-muted small mb-3">{{ __('messages.custom_ads_embed_code_help') }}</p>
            <textarea class="form-control font-monospace p-3 text-dark bg-light" style="direction: ltr; min-height: 100px;" readonly onclick="this.select(); document.execCommand('copy');" title="Click to copy">{!! $embedCode !!}</textarea>
            <div class="form-text text-success small mt-1"><i class="fa fa-info-circle me-1"></i> Click to select and copy code.</div>
        </div>
    </div>

    <!-- Hourly Heatmap Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 fw-bold">{{ __('messages.custom_ads_hourly_clicks') }}</h4>
            @include('theme::partials.ads.mini_heatmap', ['heatmap' => $heatmap])
        </div>
    </div>
</div>
@endsection
