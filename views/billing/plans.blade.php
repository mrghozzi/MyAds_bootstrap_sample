@extends('theme::layouts.master')

@section('content')
@php
    $enabledGateways = collect($gatewayDefinitions)->filter(fn ($gateway) => !empty($gateway['config']['enabled']))->values();
    $baseCurrencyCode = \App\Support\SubscriptionSettings::get('base_currency_code', 'USD');
    $entitlementService = app(\App\Services\Billing\SubscriptionEntitlementService::class);
@endphp

<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-crown fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.billing_plans_title') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.billing_plans_description', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-gem fa-10x"></i>
        </div>
    </div>

    @include('theme::billing.partials.alerts')

    <!-- Current Subscription info -->
    @if(auth()->check() && $currentSubscription)
        <div class="card border-0 shadow-sm rounded-4 mb-5 border-start border-4 border-primary bg-light bg-opacity-50 transition-all hover-translate-y">
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap gap-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-4 border border-primary border-opacity-10 shadow-sm">
                        <i class="fa fa-user-shield text-primary fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-black text-uppercase smaller text-muted letter-spacing-1 mb-2">{{ __('messages.billing_current_subscription_title') }}</h6>
                        <h3 class="fw-black mb-1 text-dark">{{ $currentSubscription->plan_name }}</h3>
                        <p class="text-muted small mb-0 fw-bold">
                            <i class="fa fa-clock me-2 opacity-50"></i>{{ __('messages.billing_ends_at_label') }}: <span class="text-primary">{{ optional($currentSubscription->ends_at)->format('Y-m-d H:i') ?: __('messages.billing_lifetime') }}</span>
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @include('theme::billing.partials.status_badge', ['status' => $currentSubscription->status])
                    <a href="{{ route('billing.dashboard') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                        <i class="fa fa-chart-line me-2"></i> {{ __('messages.billing_dashboard_title') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Search/Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-5 border border-light overflow-hidden">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('billing.plans') }}" method="GET" class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <h4 class="fw-black mb-2 text-dark">{{ __('messages.billing_compare_plans') }}</h4>
                    <p class="text-muted small mb-0 fs-6">{{ __('messages.billing_catalog_help') }}</p>
                </div>
                <div class="col-lg-5">
                    <div class="input-group bg-light rounded-pill p-1 border border-light shadow-sm">
                        <input type="text" name="search" class="form-control border-0 bg-transparent px-4 py-2" value="{{ $search }}" placeholder="{{ __('messages.search_placeholder') }}">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm transition-all hover-scale"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($enabledGateways->isEmpty())
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-5 p-4 d-flex align-items-center" role="alert">
            <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                <i class="fa fa-exclamation-triangle text-warning fs-4"></i>
            </div>
            <div>
                <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-1">{{ __('messages.warning') ?? 'Warning' }}</div>
                <div class="small text-muted fw-bold">{{ __('messages.billing_no_gateways_available') }}</div>
            </div>
        </div>
    @endif

    <!-- Pricing Grid -->
    <div class="row g-4">
        @forelse($plans as $plan)
            @php
                $benefits = $entitlementService->memberBenefitLines((array) ($plan->entitlements ?? []));
                $accentColor = $plan->accent_color ?: '#615dfa';
            @endphp
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden pricing-card transition-all hover-translate-y border border-light" style="border-top: 6px solid {{ $accentColor }} !important;">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5">
                            @if($plan->recommended_text)
                                <span class="badge rounded-pill px-3 py-2 mb-3 fw-black smaller text-uppercase letter-spacing-1 shadow-sm" style="background-color: {{ $accentColor }}22; color: {{ $accentColor }}; border: 1px solid {{ $accentColor }}44;">
                                    {{ $plan->recommended_text }}
                                </span>
                            @elseif($plan->is_featured)
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-3 fw-black smaller text-uppercase letter-spacing-1 shadow-sm border border-primary border-opacity-10">
                                    <i class="fa fa-star me-2"></i> {{ __('messages.billing_featured_plan') }}
                                </span>
                            @endif
                            <h3 class="fw-black mb-2 text-dark">{{ $plan->name }}</h3>
                            <p class="text-muted small mb-0 px-3">{{ $plan->description }}</p>
                        </div>

                        <div class="text-center mb-5 bg-light bg-opacity-50 py-4 rounded-4 border border-light shadow-inner">
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="h4 fw-black text-muted mb-0 me-1">{{ $baseCurrencyCode }}</span>
                                <h1 class="display-4 fw-black mb-0 text-dark">{{ number_format((float) $plan->base_price, 2) }}</h1>
                            </div>
                            <div class="badge bg-white text-muted rounded-pill px-3 py-2 mt-2 border border-light shadow-sm smaller fw-black">
                                {{ $plan->is_lifetime ? __('messages.billing_lifetime') : __('messages.billing_duration_days_value', ['days' => $plan->duration_days]) }}
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-black smaller text-uppercase text-muted letter-spacing-1 mb-4 text-center">{{ __('messages.billing_plan_benefits_title') }}</h6>
                            <ul class="list-unstyled d-grid gap-3">
                                @foreach((array) $plan->marketing_bullets as $bullet)
                                    <li class="d-flex align-items-start">
                                        <div class="rounded-circle bg-success bg-opacity-10 p-1 me-3 d-flex align-items-center justify-content-center border border-success border-opacity-10 shadow-sm" style="width: 24px; height: 24px; min-width: 24px;">
                                            <i class="fa fa-check text-success smaller"></i>
                                        </div>
                                        <span class="small fw-bold text-dark">{{ $bullet }}</span>
                                    </li>
                                @endforeach
                                @foreach($benefits as $benefit)
                                    <li class="d-flex align-items-start">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-1 me-3 d-flex align-items-center justify-content-center border border-primary border-opacity-10 shadow-sm" style="width: 24px; height: 24px; min-width: 24px;">
                                            <i class="fa fa-plus text-primary smaller"></i>
                                        </div>
                                        <span class="small fw-bold text-dark">{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @auth
                            @if($systemEnabled && $enabledGateways->isNotEmpty())
                                <form action="{{ route('billing.purchase', $plan->id) }}" method="POST" class="billing-purchase-form bg-light p-4 rounded-4 border border-light">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label smaller fw-black text-uppercase letter-spacing-1 text-muted">{{ __('messages.billing_choose_gateway') }}</label>
                                        <select name="gateway" class="form-select border-0 bg-white rounded-pill px-3 py-2 shadow-sm smaller fw-bold billing-gateway-select" required>
                                            @foreach($enabledGateways as $index => $gateway)
                                                <option value="{{ $gateway['key'] }}" data-supported-currencies="{{ implode(',', (array) ($gateway['supported_currencies'] ?? [])) }}" @selected(old('gateway') === $gateway['key'] || ($index === 0 && old('gateway') === null))>
                                                    {{ $gateway['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label smaller fw-black text-uppercase letter-spacing-1 text-muted">{{ __('messages.billing_choose_currency') }}</label>
                                        <select name="currency_code" class="form-select border-0 bg-white rounded-pill px-3 py-2 shadow-sm smaller fw-bold billing-currency-select" required>
                                            @foreach($activeCurrencies as $currency)
                                                <option value="{{ $currency->code }}" @selected(old('currency_code') === $currency->code)>{{ $currency->code }}{{ $currency->symbol ? ' · ' . $currency->symbol : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-lg rounded-pill fw-black shadow-lg py-3 transition-all hover-scale" style="background-color: {{ $accentColor }}; color: #fff; border: none;">
                                            {{ __('messages.billing_purchase_now') }}
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-info border-0 shadow-sm rounded-4 small p-3 text-center fw-bold" role="alert">
                                    <i class="fa fa-info-circle me-2"></i> {{ __('messages.billing_system_disabled_member_notice') }}
                                </div>
                            @endif
                        @else
                            <div class="d-grid gap-3 mt-4">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg rounded-pill fw-black shadow-sm py-3 transition-all hover-translate-y">
                                    <i class="fa fa-sign-in-alt me-2"></i> {{ __('messages.billing_sign_in_to_purchase') }}
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-light rounded-pill fw-black shadow-sm py-2 border transition-all hover-bg-light">
                                    <i class="fa fa-user-plus me-2"></i> {{ __('messages.register') }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-50 border border-light">
                    <div class="rounded-circle bg-white shadow-sm p-5 mb-4 d-inline-flex border border-light">
                        <i class="fa fa-box-open fa-4xl text-muted opacity-25"></i>
                    </div>
                    <h4 class="fw-black text-muted mb-0">{{ __('messages.no_data') }}</h4>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-10px); }
    .hover-scale:hover { transform: scale(1.05); }
    .hover-bg-light:hover { background-color: #f8f9fa !important; }
    .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2) !important; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.06); }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.billing-purchase-form');

        forms.forEach(function (form) {
            const gatewaySelect = form.querySelector('.billing-gateway-select');
            const currencySelect = form.querySelector('.billing-currency-select');
            if (!gatewaySelect || !currencySelect) return;

            const originalOptions = Array.from(currencySelect.options).map(function (option) {
                return { value: option.value, text: option.text, selected: option.selected };
            });

            const syncCurrencies = function () {
                const supported = (gatewaySelect.options[gatewaySelect.selectedIndex]?.dataset.supportedCurrencies || '')
                    .split(',')
                    .map(item => item.trim())
                    .filter(Boolean);
                const previousValue = currencySelect.value;

                currencySelect.innerHTML = '';
                originalOptions.forEach(optionData => {
                    if (supported.length && !supported.includes(optionData.value)) return;
                    const option = document.createElement('option');
                    option.value = optionData.value;
                    option.textContent = optionData.text;
                    option.selected = optionData.value === previousValue || (!previousValue && optionData.selected);
                    currencySelect.appendChild(option);
                });
                if (!currencySelect.value && currencySelect.options.length) currencySelect.selectedIndex = 0;
            };

            gatewaySelect.addEventListener('change', syncCurrencies);
            syncCurrencies();
        });
    });
</script>
@endpush
