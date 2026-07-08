@extends('theme::layouts.master')

@section('content')
@include('theme::ads.custom.partials.styles')

<div class="container py-4">
    <!-- Header Banner -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient text-white rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 55%, #38bdf8 100%);">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-rectangle-ad fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.custom_ads') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.custom_ads_dashboard_intro') }}</p>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('ads.index') }}">
                <i class="fa fa-arrow-left me-1"></i> {{ __('messages.advertising') }}
            </a>
            <a class="btn btn-outline-primary" href="{{ route('ads.custom.marketplace') }}">
                <i class="fa fa-store me-1"></i> {{ __('messages.custom_ads_marketplace') }}
            </a>
        </div>
        <div>
            <a href="{{ route('ads.custom.placements.create') }}" class="btn btn-success">
                <i class="fa fa-plus me-1"></i> {{ __('messages.custom_ads_new_placement') }}
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted mb-2">{{ __('messages.custom_ads_placements') }}</h5>
                    <div class="display-5 fw-bold text-teal" style="color: #0f766e;">{{ $placements->count() }}</div>
                    <p class="card-text text-muted small mt-2">{{ __('messages.custom_ads_placements_help') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted mb-2">{{ __('messages.custom_ads_publisher_deals') }}</h5>
                    <div class="display-5 fw-bold text-teal" style="color: #0f766e;">{{ $publisherDeals->count() }}</div>
                    <p class="card-text text-muted small mt-2">{{ __('messages.custom_ads_publisher_deals_help') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 p-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted mb-2">{{ __('messages.custom_ads_advertiser_deals') }}</h5>
                    <div class="display-5 fw-bold text-teal" style="color: #0f766e;">{{ $advertiserDeals->count() }}</div>
                    <p class="card-text text-muted small mt-2">{{ __('messages.custom_ads_advertiser_deals_help') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Placements Table -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 fw-bold">{{ __('messages.custom_ads_placements') }}</h4>
            @if($placements->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.type') }}</th>
                                <th>{{ __('messages.stats') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($placements as $placement)
                                <tr>
                                    <td>
                                        <strong>{{ $placement->name }}</strong>
                                        <div class="text-muted small">{{ $placement->site_url ?: $placement->placement_key }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border me-1">{{ __('messages.custom_ads_format_' . $placement->format) }}</span>
                                        <span class="badge bg-light text-dark border">{{ $placement->size }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 me-1"><i class="fa fa-eye me-1"></i>{{ $placement->summary['impressions'] ?? $placement->impressions }}</span>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 me-1"><i class="fa fa-mouse-pointer me-1"></i>{{ $placement->summary['clicks'] ?? $placement->clicks }}</span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10">CTR {{ $placement->summary['ctr'] ?? $placement->ctr() }}%</span>
                                    </td>
                                    <td>
                                        <span class="custom-ads-status {{ $placement->status }}">{{ $placement->status }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('ads.custom.placements.code', $placement) }}" title="Embed Code"><i class="fa fa-code"></i></a>
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('ads.custom.placements.edit', $placement) }}" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a class="btn btn-sm btn-success" href="{{ route('ads.custom.placements.invite', $placement) }}">{{ __('messages.custom_ads_invite') }}</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted mb-3">{{ __('messages.custom_ads_empty_placements_desc') }}</p>
                    <a href="{{ route('ads.custom.placements.create') }}" class="btn btn-success">{{ __('messages.custom_ads_new_placement') }}</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Publisher Deals -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 fw-bold">{{ __('messages.custom_ads_publisher_deals') }}</h4>
            @include('theme::ads.custom.partials.deals_table', ['deals' => $publisherDeals])
        </div>
    </div>

    <!-- Advertiser Deals -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 fw-bold">{{ __('messages.custom_ads_advertiser_deals') }}</h4>
            @include('theme::ads.custom.partials.deals_table', ['deals' => $advertiserDeals])
        </div>
    </div>
</div>
@endsection
