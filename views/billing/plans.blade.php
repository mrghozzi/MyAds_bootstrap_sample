@extends('theme::layouts.master')

@section('content')
@php
    $enabledGateways = collect($gatewayDefinitions)->filter(fn ($gateway) => !empty($gateway['config']['enabled']))->values();
    $baseCurrencyCode = \App\Support\SubscriptionSettings::get('base_currency_code', 'USD');
    $entitlementService = app(\App\Services\Billing\SubscriptionEntitlementService::class);
@endphp

<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-5 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-crown fa-3x text-warning"></i>
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

    @if(auth()->check() && $currentSubscription)
        <!-- Current Subscription Status -->
        <div class="card border-0 shadow-sm rounded-4 mb-5 border border-light overflow-hidden transition-all hover-translate-y">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                    <div>
                        <div class="fw-black smaller text-uppercase letter-spacing-1 text-muted mb-2">{{ __('messages.billing_current_subscription_title') }}</div>
                        <h3 class="fw-black text-primary mb-1">{{ $currentSubscription->plan_name }}</h3>
                        <p class="text-muted small mb-0 fw-bold">
                            {{ __('messages.billing_ends_at_label') }}: {{ optional($currentSubscription->ends_at)->format('Y-m-d H:i') ?: __('messages.billing_lifetime') }}
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        @include('theme::billing.partials.status_badge', ['status' => $currentSubscription->status])
                        @if($currentSubscription->is_trial)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-bold smaller">
                                <i class="fa fa-gift me-1"></i> {{ __('messages.billing_trial_free_tag') }}
                            </span>
                        @endif
                        <a href="{{ route('billing.dashboard') }}" class="btn btn-primary rounded-pill px-4 fw-black shadow-sm transition-all hover-translate-y">
                            {{ __('messages.billing_dashboard_title') }}
                        </a>
                    </div>
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
                $layoutStyle = $plan->layout_style ?? 'default';
                $cardClass = function_exists('apply_filters') 
                    ? apply_filters('billing_plans_card_class', 'card h-100 border-0 shadow-sm rounded-4 overflow-hidden pricing-card transition-all hover-translate-y border border-light', $plan) 
                    : 'card h-100 border-0 shadow-sm rounded-4 overflow-hidden pricing-card transition-all hover-translate-y border border-light';
                $customCardStyle = '';
                if ($layoutStyle === 'glassmorphism') {
                    $customCardStyle = 'background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5) !important; box-shadow: 0 10px 30px rgba(31, 38, 135, 0.1) !important;';
                } elseif ($layoutStyle === 'neon_glow') {
                    $customCardStyle = "box-shadow: 0 0 25px {$accentColor}35 !important; border: 2px solid {$accentColor} !important;";
                } elseif ($layoutStyle === 'minimal') {
                    $customCardStyle = 'box-shadow: none !important; border: 1px solid #e2e8f0 !important;';
                }
            @endphp
            <div class="col-lg-4 col-md-6">
                <div class="{{ $cardClass }}" style="border-top: 6px solid {{ $accentColor }} !important; {{ $customCardStyle }}">
                    <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="text-center mb-4">
                                @if($plan->recommended_text)
                                    <span class="badge rounded-pill px-3 py-2 mb-3 fw-black smaller text-uppercase letter-spacing-1 shadow-sm" style="background-color: {{ $accentColor }}22; color: {{ $accentColor }}; border: 1px solid {{ $accentColor }}44;">
                                        {{ $plan->recommended_text }}
                                    </span>
                                @elseif($plan->is_featured)
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-3 fw-black smaller text-uppercase letter-spacing-1 shadow-sm border border-primary border-opacity-10">
                                        <i class="fa fa-star me-2"></i> {{ __('messages.billing_featured_plan') }}
                                    </span>
                                @endif
                                @if(function_exists('do_action'))
                                    @php(do_action('billing_plans_card_badge', $plan))
                                @endif
                                <h3 class="fw-black mb-2 text-dark">{{ $plan->name }}</h3>
                                <p class="text-muted small mb-0 px-3">{{ $plan->description }}</p>
                            </div>

                            {{-- Price Block --}}
                            <div class="text-center mb-4 bg-light bg-opacity-50 py-4 rounded-4 border border-light shadow-inner">
                                @if($plan->is_on_sale)
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap mb-1">
                                        <span class="text-decoration-line-through text-muted fs-6">{{ number_format((float) $plan->base_price, 2) }}</span>
                                        <span class="display-5 fw-black text-danger mb-0">{{ number_format((float) $plan->sale_price, 2) }}</span>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1 fs-11">
                                            -{{ $plan->discount_percentage }}% {{ __('messages.billing_sale_tag') }}
                                        </span>
                                    </div>
                                    @if($plan->sale_ends_at)
                                        <div class="smaller text-danger fw-bold mb-1">
                                            <i class="fa fa-clock me-1"></i>{{ __('messages.billing_sale_ends_in') }}: {{ $plan->sale_ends_at->diffForHumans() }}
                                        </div>
                                    @endif
                                @else
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="h4 fw-black text-muted mb-0 me-1">{{ $baseCurrencyCode }}</span>
                                        <h1 class="display-4 fw-black mb-0 text-dark">{{ number_format((float) $plan->base_price, 2) }}</h1>
                                    </div>
                                @endif
                                <div class="badge bg-white text-muted rounded-pill px-3 py-2 mt-2 border border-light shadow-sm smaller fw-black">
                                    {{ $plan->is_lifetime ? __('messages.billing_lifetime') : __('messages.billing_duration_days_value', ['days' => $plan->duration_days]) }}
                                </div>
                            </div>

                            {{-- Free Trial Banner / CTA --}}
                            @if($plan->has_trial)
                                <div class="p-3 mb-4 rounded-4 border border-success border-opacity-25 bg-success bg-opacity-10 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                    <div>
                                        <span class="text-success fw-bold smaller d-block">
                                            <i class="fa fa-gift me-1"></i> {{ $plan->trial_days }} {{ __('messages.days') }} {{ __('messages.billing_trial_free_tag') }}
                                        </span>
                                        <span class="text-muted smallest">{{ __('messages.billing_trial_free_help') }}</span>
                                    </div>
                                    @auth
                                        @if(app(\App\Services\Billing\SubscriptionLifecycleService::class)->userCanClaimTrial(auth()->id(), $plan))
                                            <form action="{{ route('billing.trial.claim', $plan->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm btn-claim-trial">
                                                    {{ __('messages.billing_start_free_trial') }}
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            @endif

                            {{-- Highlights & Benefits --}}
                            <div class="mb-4">
                                <h6 class="fw-black smaller text-uppercase text-muted letter-spacing-1 mb-3 text-center">{{ __('messages.billing_plan_benefits_title') }}</h6>
                                <ul class="list-unstyled d-grid gap-2">
                                    @foreach((array) $plan->marketing_bullets as $bullet)
                                        <li class="d-flex align-items-start">
                                            <div class="rounded-circle bg-success bg-opacity-10 p-1 me-2 d-flex align-items-center justify-content-center border border-success border-opacity-10 shadow-sm flex-shrink-0" style="width: 22px; height: 22px;">
                                                <i class="fa fa-check text-success smaller"></i>
                                            </div>
                                            <span class="small fw-bold text-dark">{{ $bullet }}</span>
                                        </li>
                                    @endforeach
                                    @foreach($benefits as $benefit)
                                        <li class="d-flex align-items-start">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-1 me-2 d-flex align-items-center justify-content-center border border-primary border-opacity-10 shadow-sm flex-shrink-0" style="width: 22px; height: 22px;">
                                                <i class="fa fa-plus text-primary smaller"></i>
                                            </div>
                                            <span class="small fw-bold text-dark">{{ $benefit }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div>
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
                                        <div class="mb-3">
                                            <label class="form-label smaller fw-black text-uppercase letter-spacing-1 text-muted">{{ __('messages.billing_choose_currency') }}</label>
                                            <select name="currency_code" class="form-select border-0 bg-white rounded-pill px-3 py-2 shadow-sm smaller fw-bold billing-currency-select" required>
                                                @foreach($activeCurrencies as $currency)
                                                    <option value="{{ $currency->code }}" @selected(old('currency_code') === $currency->code)>{{ $currency->code }}{{ $currency->symbol ? ' · ' . $currency->symbol : '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Promo / Coupon Code Input --}}
                                        <div class="mb-3">
                                            <label class="form-label smaller fw-black text-uppercase letter-spacing-1 text-muted">{{ __('messages.billing_coupon_code') }}</label>
                                            <div class="input-group">
                                                <input type="text" name="coupon_code" class="form-control border-0 bg-white rounded-start-pill px-3 py-2 shadow-sm smaller font-monospace text-uppercase billing-coupon-input coupon-code-input" placeholder="{{ __('messages.billing_coupon_placeholder') }}">
                                                <button type="button" class="btn btn-dark rounded-end-pill px-3 fw-bold smaller btn-apply-coupon" data-plan-id="{{ $plan->id }}">
                                                    {{ __('messages.apply') }}
                                                </button>
                                            </div>
                                            <div class="coupon-feedback-message mt-2 smaller fw-bold" style="display: none;"></div>
                                        </div>

                                        <div class="tabby-phone-container mb-3" style="display: none;">
                                            <label class="form-label smaller fw-black text-uppercase letter-spacing-1 text-muted">{{ __('messages.billing_phone_number') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="phone_number" class="form-control border-0 bg-white rounded-pill px-3 py-2 shadow-sm smaller fw-bold billing-phone-input" placeholder="+966500000000" value="{{ old('phone_number') }}">
                                            <p class="smallest text-muted mt-1">{{ __('messages.billing_tabby_phone_help') }}</p>
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

                            @if(function_exists('do_action'))
                                @php(do_action('billing_plans_card_footer', $plan))
                            @endif
                        </div>
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

    {{-- Comparison Matrix --}}
    @if($plans->isNotEmpty())
        <div class="card border-0 shadow-sm rounded-4 mt-5 border border-light overflow-hidden feature-comparison-matrix">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-5">
                    <h3 class="fw-black text-dark mb-2">{{ __('messages.billing_comparison_title') }}</h3>
                    <p class="text-muted small mx-auto" style="max-width: 600px;">{{ __('messages.billing_comparison_subtitle') }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start py-3 px-4">{{ __('messages.billing_feature_name') }}</th>
                                <th class="py-3 px-4">{{ __('messages.billing_free_member') }}</th>
                                @foreach($plans as $plan)
                                    <th class="py-3 px-4" style="color: {{ $plan->accent_color ?: '#615dfa' }};">
                                        <div class="fw-black">{{ $plan->name }}</div>
                                        <div class="smaller text-muted fw-normal mt-1">
                                            {{ number_format((float) $plan->base_price, 2) }} {{ $baseCurrencyCode }}
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Row: Ad-Free --}}
                            <tr>
                                <td class="text-start py-3 px-4 fw-bold">
                                    <i class="fa fa-shield-alt text-primary me-2"></i> {{ __('messages.billing_comparison_ad_free') }}
                                </td>
                                <td class="text-danger py-3 px-4"><i class="fa fa-times-circle"></i></td>
                                @foreach($plans as $plan)
                                    @php($adFree = !empty(data_get($plan->entitlements, 'ad_free')))
                                    <td class="py-3 px-4">
                                        @if($adFree)
                                            <span class="text-success fw-bold"><i class="fa fa-check-circle me-1"></i> 100% Ad-Free</span>
                                        @else
                                            <span class="text-muted"><i class="fa fa-times-circle"></i></span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Row: PTS Multiplier --}}
                            <tr>
                                <td class="text-start py-3 px-4 fw-bold">
                                    <i class="fa fa-coins text-warning me-2"></i> {{ __('messages.billing_comparison_multiplier') }}
                                </td>
                                <td class="text-muted py-3 px-4">1x (Standard)</td>
                                @foreach($plans as $plan)
                                    @php($mult = (float) data_get($plan->entitlements, 'pts_multiplier', 1.0))
                                    <td class="py-3 px-4">
                                        @if($mult > 1.0)
                                            <span class="badge bg-warning bg-opacity-25 text-dark fw-black rounded-pill px-3 py-1">
                                                {{ $mult }}x Rewards
                                            </span>
                                        @else
                                            <span class="text-muted">1x</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Row: Exchange Priority --}}
                            <tr>
                                <td class="text-start py-3 px-4 fw-bold">
                                    <i class="fa fa-bolt text-danger me-2"></i> {{ __('messages.billing_comparison_priority') }}
                                </td>
                                <td class="text-muted py-3 px-4">Standard</td>
                                @foreach($plans as $plan)
                                    @php($prio = !empty(data_get($plan->entitlements, 'exchange_priority')))
                                    <td class="py-3 px-4">
                                        @if($prio)
                                            <span class="text-danger fw-bold"><i class="fa fa-rocket me-1"></i> High Priority</span>
                                        @else
                                            <span class="text-muted">Standard</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Row: Store Commission --}}
                            <tr>
                                <td class="text-start py-3 px-4 fw-bold">
                                    <i class="fa fa-store text-success me-2"></i> {{ __('messages.billing_comparison_commission') }}
                                </td>
                                <td class="text-muted py-3 px-4">20% (Default)</td>
                                @foreach($plans as $plan)
                                    @php($comm = data_get($plan->entitlements, 'store_commission_pct'))
                                    <td class="py-3 px-4">
                                        @if(!is_null($comm))
                                            <span class="text-success fw-bold">{{ (float) $comm }}% Commission</span>
                                        @else
                                            <span class="text-muted">20%</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Row: Directory Golden Badge --}}
                            <tr>
                                <td class="text-start py-3 px-4 fw-bold">
                                    <i class="fa fa-sitemap text-info me-2"></i> {{ __('messages.billing_comparison_directory') }}
                                </td>
                                <td class="text-muted py-3 px-4"><i class="fa fa-times-circle"></i></td>
                                @foreach($plans as $plan)
                                    @php($dirBadge = !empty(data_get($plan->entitlements, 'featured_directory_badge')))
                                    <td class="py-3 px-4">
                                        @if($dirBadge)
                                            <span class="badge bg-info bg-opacity-25 text-info fw-bold rounded-pill px-3 py-1">
                                                <i class="fa fa-award me-1"></i> Featured Gold
                                            </span>
                                        @else
                                            <span class="text-muted"><i class="fa fa-times-circle"></i></span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Row: Max Media Upload Size --}}
                            <tr>
                                <td class="text-start py-3 px-4 fw-bold">
                                    <i class="fa fa-cloud-upload-alt text-secondary me-2"></i> {{ __('messages.billing_comparison_upload_size') }}
                                </td>
                                <td class="text-muted py-3 px-4">25 MB</td>
                                @foreach($plans as $plan)
                                    @php($uploadMb = data_get($plan->entitlements, 'max_upload_size_mb', 25))
                                    <td class="py-3 px-4 fw-bold text-dark">
                                        {{ $uploadMb }} MB
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Row: Verified Badge --}}
                            <tr>
                                <td class="text-start py-3 px-4 fw-bold">
                                    <i class="fa fa-check-circle text-primary me-2"></i> {{ __('messages.billing_comparison_verified_badge') }}
                                </td>
                                <td class="text-muted py-3 px-4"><i class="fa fa-times-circle"></i></td>
                                @foreach($plans as $plan)
                                    @php($verified = !empty(data_get($plan->entitlements, 'subscription_verified_badge')))
                                    <td class="py-3 px-4">
                                        @if($verified)
                                            <span class="text-primary fw-bold"><i class="fa fa-check-circle me-1"></i> VIP Verified</span>
                                        @else
                                            <span class="text-muted"><i class="fa fa-times-circle"></i></span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Row: Bonus Points (PTS) --}}
                            <tr>
                                <td class="text-start py-3 px-4 fw-bold">
                                    <i class="fa fa-gift text-warning me-2"></i> {{ __('messages.billing_bonus_pts_field') }}
                                </td>
                                <td class="text-muted py-3 px-4">-</td>
                                @foreach($plans as $plan)
                                    @php($bonusPts = data_get($plan->entitlements, 'bonus_pts', 0))
                                    <td class="py-3 px-4 fw-black text-warning">
                                        {{ $bonusPts > 0 ? '+' . number_format($bonusPts) . ' PTS' : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .smallest { font-size: 0.72rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-8px); }
    .hover-scale:hover { transform: scale(1.03); }
    .hover-bg-light:hover { background-color: #f8f9fa !important; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.04); }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.billing-purchase-form');

        forms.forEach(function (form) {
            const gatewaySelect = form.querySelector('.billing-gateway-select');
            const currencySelect = form.querySelector('.billing-currency-select');
            const tabbyPhoneContainer = form.querySelector('.tabby-phone-container');
            const phoneInput = form.querySelector('.billing-phone-input');
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

            const toggleTabbyPhone = function () {
                if (!tabbyPhoneContainer || !phoneInput) return;
                if (gatewaySelect.value === 'tabby') {
                    tabbyPhoneContainer.style.display = 'block';
                    phoneInput.setAttribute('required', 'required');
                } else {
                    tabbyPhoneContainer.style.display = 'none';
                    phoneInput.removeAttribute('required');
                }
            };

            gatewaySelect.addEventListener('change', function () {
                syncCurrencies();
                toggleTabbyPhone();
            });
            syncCurrencies();
            toggleTabbyPhone();
        });

        // Coupon AJAX validation
        document.querySelectorAll('.btn-apply-coupon').forEach(function (button) {
            button.addEventListener('click', function () {
                const planId = this.dataset.planId;
                const form = this.closest('form');
                const input = form.querySelector('.billing-coupon-input');
                const feedback = form.querySelector('.coupon-feedback-message');
                const code = input ? input.value.trim() : '';

                if (!code) {
                    if (feedback) {
                        feedback.style.display = 'block';
                        feedback.className = 'coupon-feedback-message mt-2 smaller fw-bold text-danger';
                        feedback.textContent = '{{ __("messages.billing_coupon_empty") }}';
                    }
                    return;
                }

                button.disabled = true;
                const originalText = button.textContent;
                button.textContent = '...';

                fetch('{{ route("billing.coupon.validate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        plan_id: planId,
                        coupon_code: code
                    })
                })
                .then(res => res.json())
                .then(data => {
                    button.disabled = false;
                    button.textContent = originalText;
                    if (feedback) {
                        feedback.style.display = 'block';
                        if (data.valid) {
                            feedback.className = 'coupon-feedback-message mt-2 smaller fw-bold text-success';
                            feedback.textContent = data.message + ' ({{ __("messages.billing_final_amount") }}: ' + data.final_amount + ')';
                        } else {
                            feedback.className = 'coupon-feedback-message mt-2 smaller fw-bold text-danger';
                            feedback.textContent = data.message;
                        }
                    }
                })
                .catch(() => {
                    button.disabled = false;
                    button.textContent = originalText;
                    if (feedback) {
                        feedback.style.display = 'block';
                        feedback.className = 'coupon-feedback-message mt-2 smaller fw-bold text-danger';
                        feedback.textContent = '{{ __("messages.billing_coupon_error") }}';
                    }
                });
            });
        });
    });
</script>
@endpush
