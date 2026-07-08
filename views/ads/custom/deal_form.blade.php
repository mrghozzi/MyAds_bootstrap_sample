@extends('theme::layouts.master')

@section('content')
@include('theme::ads.custom.partials.styles')

@php
    $isInvite = $source === \App\Models\CustomAdDeal::SOURCE_INVITE;
    $isEdit = $isEdit ?? false;
    $action = $isEdit
        ? route('ads.custom.deals.update', $deal)
        : ($isInvite
            ? route('ads.custom.placements.invite.store', $placement)
            : route('ads.custom.placements.request.store', $placement));
@endphp

<div class="container py-4">
    <!-- Header Banner -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient text-white rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #0ea5e9 100%);">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-handshake fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">
                    @if($isEdit)
                        {{ __('messages.edit') }} #{{ $deal->id }}
                    @else
                        {{ $isInvite ? __('messages.custom_ads_invite') : __('messages.custom_ads_request_deal') }}
                    @endif
                </h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ $placement->name }}</p>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="mb-4">
        <a class="btn btn-outline-secondary" href="{{ $isInvite ? route('ads.custom.index') : route('ads.custom.marketplace') }}">
            <i class="fa fa-arrow-left me-1"></i> {{ __('messages.back') }}
        </a>
    </div>

    <!-- Info Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex gap-1 mb-3">
                <span class="badge bg-light text-dark border">{{ __('messages.custom_ads_format_' . $placement->format) }}</span>
                <span class="badge bg-light text-dark border">{{ $placement->size }}</span>
            </div>
            <h4 class="fw-bold mb-2">{{ $placement->name }}</h4>
            <p class="text-muted small mb-2">{{ $placement->description }}</p>
            <div class="text-muted small"><i class="fa fa-user me-1"></i> {{ __('messages.publisher') }}: <strong>{{ $placement->user?->username }}</strong></div>
        </div>
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ $action }}">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                @if($isInvite && !$isEdit)
                    <div class="mb-4">
                        <label class="form-label fw-bold">{{ __('messages.custom_ads_advertiser_lookup') }}</label>
                        <input type="text" name="advertiser" class="form-control @error('advertiser') is-invalid @enderror" value="{{ old('advertiser') }}" required placeholder="username@example.com">
                        @error('advertiser')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('messages.custom_ads_payment_type') }}</label>
                        <select name="payment_type" id="custom-ad-payment-type" class="form-select" required>
                            <option value="{{ \App\Models\CustomAdDeal::PAYMENT_PTS_DAILY }}" @selected(old('payment_type', $deal->payment_type) === \App\Models\CustomAdDeal::PAYMENT_PTS_DAILY)>{{ __('messages.custom_ads_pts_daily') }}</option>
                            <option value="{{ \App\Models\CustomAdDeal::PAYMENT_EXTERNAL }}" @selected(old('payment_type', $deal->payment_type) === \App\Models\CustomAdDeal::PAYMENT_EXTERNAL)>{{ __('messages.custom_ads_external') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6 custom-ad-pts-field">
                        <label class="form-label fw-bold">{{ __('messages.custom_ads_daily_pts') }}</label>
                        <input type="number" name="daily_pts" min="0" step="0.01" class="form-control @error('daily_pts') is-invalid @enderror" value="{{ old('daily_pts', $deal->daily_pts) }}">
                        @error('daily_pts')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 custom-ad-external-field" style="display: none;">
                        <label class="form-label fw-bold">{{ __('messages.custom_ads_external_amount') }}</label>
                        <input type="number" name="external_amount" min="0" step="0.01" class="form-control @error('external_amount') is-invalid @enderror" value="{{ old('external_amount', $deal->external_amount) }}">
                        @error('external_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3 custom-ad-external-field" style="display: none;">
                        <label class="form-label fw-bold">{{ __('messages.currency') }}</label>
                        <input type="text" name="external_currency" maxlength="8" class="form-control" value="{{ old('external_currency', $deal->external_currency ?: 'USD') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('messages.start_date') }}</label>
                        <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at', optional($deal->starts_at)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('messages.end_date') }}</label>
                        <input type="date" name="ends_at" class="form-control @error('ends_at') is-invalid @enderror" value="{{ old('ends_at', optional($deal->ends_at)->format('Y-m-d')) }}" required>
                        <div class="form-text text-muted">{{ __('messages.custom_ads_max_duration', ['days' => $maxDurationDays]) }}</div>
                        @error('ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4 custom-ad-external-field" style="display: none;">
                    <label class="form-label fw-bold">{{ __('messages.custom_ads_external_note') }}</label>
                    <textarea name="external_note" class="form-control" rows="3">{{ old('external_note', $deal->external_note) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">{{ __('messages.custom_ads_terms') }}</label>
                    <textarea name="terms" class="form-control" rows="3">{{ old('terms', $deal->terms) }}</textarea>
                </div>

                <!-- Creative Details -->
                <div class="card border-light bg-light bg-opacity-50 p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fa fa-paint-brush me-1 text-primary"></i> {{ __('messages.custom_ads_creative') }}</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.title') }}</label>
                            <input type="text" name="headline" class="form-control @error('headline') is-invalid @enderror" value="{{ old('headline', $creative->headline) }}" required>
                            @error('headline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.url') }}</label>
                            <input type="url" name="target_url" class="form-control @error('target_url') is-invalid @enderror" value="{{ old('target_url', $creative->target_url) }}" required placeholder="https://example.com">
                            @error('target_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.img') }}</label>
                            <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $creative->image_url) }}" placeholder="https://example.com/banner.png">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.custom_ads_button_label') }}</label>
                            <input type="text" name="button_label" class="form-control" value="{{ old('button_label', $creative->button_label ?: __('messages.custom_ads_learn_more')) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">{{ __('messages.description') }}</label>
                            <textarea name="body" class="form-control" rows="3">{{ old('body', $creative->body) }}</textarea>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('messages.custom_ads_background_color') }}</label>
                            <input type="color" name="background_color" class="form-control form-control-color w-100" value="{{ old('background_color', $creative->background_color ?: '#ffffff') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('messages.custom_ads_text_color') }}</label>
                            <input type="color" name="text_color" class="form-control form-control-color w-100" value="{{ old('text_color', $creative->text_color ?: '#1f2937') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('messages.custom_ads_accent_color') }}</label>
                            <input type="color" name="accent_color" class="form-control form-control-color w-100" value="{{ old('accent_color', $creative->accent_color ?: '#615dfa') }}">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ $isEdit ? __('messages.save') : ($isInvite ? __('messages.custom_ads_send_invite') : __('messages.custom_ads_send_request')) }}
                    </button>
                    <a href="{{ $isEdit ? route('ads.custom.deals.show', $deal) : route('ads.custom.index') }}" class="btn btn-outline-secondary">
                        {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var payment = document.getElementById('custom-ad-payment-type');
    function syncPaymentFields() {
        var external = payment && payment.value === '{{ \App\Models\CustomAdDeal::PAYMENT_EXTERNAL }}';
        document.querySelectorAll('.custom-ad-external-field').forEach(function (node) { node.style.display = external ? '' : 'none'; });
        document.querySelectorAll('.custom-ad-pts-field').forEach(function (node) { node.style.display = external ? 'none' : ''; });
    }
    if (payment) {
        payment.addEventListener('change', syncPaymentFields);
        syncPaymentFields();
    }
});
</script>
@endsection
