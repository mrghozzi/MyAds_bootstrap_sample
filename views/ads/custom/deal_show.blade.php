@extends('theme::layouts.master')

@section('content')
@include('theme::ads.custom.partials.styles')

@php
    $viewer = auth()->user();
    $canAccept = $deal->canBeAcceptedBy($viewer);
    $isPublisher = (int) $deal->publisher_id === (int) $viewer->id;
@endphp

<div class="container py-4">
    <!-- Header Banner -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient text-white rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-handshake fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.custom_ads_deal') }} #{{ $deal->id }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ $deal->placement?->name }}</p>
            </div>
        </div>
    </div>

    <!-- Toolbar & Actions -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a class="btn btn-outline-secondary" href="{{ route('ads.custom.index') }}">
                <i class="fa fa-arrow-left me-1"></i> {{ __('messages.custom_ads') }}
            </a>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            @if($canAccept)
                <form method="POST" action="{{ route('ads.custom.deals.accept', $deal) }}">
                    @csrf
                    <button class="btn btn-success" type="submit">{{ __('messages.accept') }}</button>
                </form>
                <form method="POST" action="{{ route('ads.custom.deals.reject', $deal) }}">
                    @csrf
                    <button class="btn btn-danger" type="submit">{{ __('messages.reject') }}</button>
                </form>
                @if($deal->status === \App\Models\CustomAdDeal::STATUS_INVITED && (int) $deal->advertiser_id === (int) $viewer->id)
                    <a href="{{ route('ads.custom.deals.edit', $deal) }}" class="btn btn-warning">{{ __('messages.edit') }}</a>
                @endif
            @endif
            @if($isPublisher && $deal->status === \App\Models\CustomAdDeal::STATUS_ACTIVE)
                <form method="POST" action="{{ route('ads.custom.deals.pause', $deal) }}">
                    @csrf
                    <button class="btn btn-warning" type="submit">{{ __('messages.pause') }}</button>
                </form>
            @endif
            @if($isPublisher && $deal->status === \App\Models\CustomAdDeal::STATUS_PAUSED)
                <form method="POST" action="{{ route('ads.custom.deals.resume', $deal) }}">
                    @csrf
                    <button class="btn btn-success" type="submit">{{ __('messages.resume') }}</button>
                </form>
            @endif
            @if(!in_array($deal->status, [\App\Models\CustomAdDeal::STATUS_CANCELLED, \App\Models\CustomAdDeal::STATUS_COMPLETED, \App\Models\CustomAdDeal::STATUS_REJECTED], true))
                <form method="POST" action="{{ route('ads.custom.deals.cancel', $deal) }}">
                    @csrf
                    <button class="btn btn-outline-danger" type="submit">{{ __('messages.cancel') }}</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-2">{{ __('messages.status') }}</h6>
                    <span class="custom-ads-status {{ $deal->status }}">{{ $deal->status }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-2">{{ __('messages.custom_ads_impressions') }}</h6>
                    <div class="h3 fw-bold text-teal" style="color: #0f766e;">{{ number_format($summary['impressions']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-2">{{ __('messages.custom_ads_clicks') }}</h6>
                    <div class="h3 fw-bold text-teal" style="color: #0f766e;">{{ number_format($summary['clicks']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-2">CTR</h6>
                    <div class="h3 fw-bold text-teal" style="color: #0f766e;">{{ $summary['ctr'] }}%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deal Terms -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 fw-bold">{{ __('messages.custom_ads_deal_terms') }}</h4>
            <div class="row g-3">
                <div class="col-sm-6 col-md-3">
                    <div class="text-muted small">{{ __('messages.publisher') }}</div>
                    <strong>{{ $deal->publisher?->username }}</strong>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="text-muted small">{{ __('messages.custom_ads_advertiser') }}</div>
                    <strong>{{ $deal->advertiser?->username }}</strong>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="text-muted small">{{ __('messages.custom_ads_period') }}</div>
                    <strong>{{ optional($deal->starts_at)->format('Y-m-d') }} → {{ optional($deal->ends_at)->format('Y-m-d') }}</strong>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="text-muted small">{{ __('messages.custom_ads_payment') }}</div>
                    @if($deal->payment_type === \App\Models\CustomAdDeal::PAYMENT_PTS_DAILY)
                        <strong>{{ number_format((float) $deal->daily_pts, 2) }} PTS/{{ __('messages.day') }}</strong>
                        <div class="text-muted small">
                            {{ __('messages.custom_ads_reserved') }} {{ number_format((float) $deal->reserved_pts, 2) }} <br>
                            {{ __('messages.custom_ads_paid') }} {{ number_format((float) $deal->paid_pts, 2) }} <br>
                            {{ __('messages.custom_ads_remaining') }} {{ number_format((float) $deal->remainingReservedPts(), 2) }}
                        </div>
                    @else
                        <strong>{{ __('messages.custom_ads_external') }}</strong>
                        <div class="text-muted small">
                            {{ $deal->external_amount ? number_format((float) $deal->external_amount, 2) . ' ' . $deal->external_currency : '' }}
                        </div>
                    @endif
                </div>
            </div>
            @if($deal->terms)
                <div class="mt-4 pt-3 border-top">
                    <h6>{{ __('messages.custom_ads_terms') }}</h6>
                    <p class="mb-0 text-muted">{{ $deal->terms }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Creative Details -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 fw-bold">{{ __('messages.custom_ads_creative') }}</h4>
            <div class="row g-4">
                @if($deal->creative)
                    <div class="col-md-6">
                        <div class="p-3 border rounded-3 bg-light bg-opacity-25 h-100">
                            <div class="d-flex gap-1 mb-3">
                                <span class="badge bg-secondary">{{ $deal->creative->status }}</span>
                                <span class="badge bg-light text-dark border">{{ __('messages.custom_ads_format_' . ($deal->creative->format ?: 'banner')) }}</span>
                            </div>
                            <h5 class="fw-bold mb-2">{{ $deal->creative->headline }}</h5>
                            <p class="text-muted small mb-3">{{ $deal->creative->body }}</p>
                            <a href="{{ $deal->creative->target_url }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none small text-break"><i class="fa fa-external-link me-1"></i> {{ $deal->creative->target_url }}</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-2 fw-bold">{{ __('messages.custom_ads_live_preview') }}</p>
                        <div class="border rounded-3 p-3 bg-light d-flex justify-content-center align-items-center" style="min-height: 150px;">
                            {!! app(\App\Services\CustomAds\CustomAdServingService::class)->renderMarkup($deal->placement, $deal->creative) !!}
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="text-center py-4 text-muted border border-dashed rounded-3">
                            <i class="fa fa-paint-brush fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">{{ __('messages.custom_ads_no_creative') ?? 'No creative details available for this deal.' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 fw-bold">{{ __('messages.custom_ads_analytics') }}</h4>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 border rounded-3 h-100 bg-light bg-opacity-25">
                        <h6 class="fw-bold mb-3">{{ __('messages.custom_ads_hourly_clicks') }}</h6>
                        @include('theme::partials.ads.mini_heatmap', ['heatmap' => $heatmap])
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 border rounded-3 h-100 bg-light bg-opacity-25">
                        <h6 class="fw-bold mb-3">{{ __('messages.custom_ads_devices') }}</h6>
                        <ul class="list-group list-group-flush bg-transparent">
                            @forelse($devices as $device => $count)
                                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <span>{{ $device }}</span>
                                    <span class="badge bg-secondary rounded-pill">{{ $count }}</span>
                                </li>
                            @empty
                                <li class="list-group-item bg-transparent px-0 text-muted small">{{ __('messages.no_data') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 border rounded-3 h-100 bg-light bg-opacity-25">
                        <h6 class="fw-bold mb-3">{{ __('messages.custom_ads_countries') }}</h6>
                        <ul class="list-group list-group-flush bg-transparent">
                            @forelse($countries as $country => $count)
                                <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0">
                                    <span>{{ $country }}</span>
                                    <span class="badge bg-secondary rounded-pill">{{ $count }}</span>
                                </li>
                            @empty
                                <li class="list-group-item bg-transparent px-0 text-muted small">{{ __('messages.no_data') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payouts Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h4 class="card-title mb-4 fw-bold">{{ __('messages.custom_ads_payouts') }}</h4>
            @if($deal->payouts->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('messages.type') }}</th>
                                <th>{{ __('messages.amount') }}</th>
                                <th>{{ __('messages.date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deal->payouts as $payout)
                                <tr>
                                    <td>{{ $payout->type }}</td>
                                    <td>{{ number_format((float) $payout->amount, 2) }} PTS</td>
                                    <td>{{ optional($payout->payout_date)->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0 small">{{ __('messages.custom_ads_no_payouts') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
