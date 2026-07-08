@extends('theme::layouts.master')

@section('content')
<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, rgba(30,41,59,.96) 0%, rgba(234,88,12,.88) 55%, rgba(249,115,22,.82) 100%); position: relative; z-index: 1;">
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-4 rounded-circle bg-white p-2" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}" alt="overview-icon" style="width: 80px; height: 80px;">
                    <div>
                        <p class="text-white fs-3 fw-bold mb-1">{{ __('messages.status_promotion_setup_title') }}</p>
                        <p class="text-white-50 mb-0 fs-5"><b>{{ __('messages.status_promotion_setup_help') }}</b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 flex-wrap mb-4">
    <a href="{{ route('ads.posts.index') }}" class="btn btn-light rounded-pill border fw-bold px-4 text-primary">
        <i class="fa fa-arrow-left me-2"></i> {{ __('messages.status_promotions_title') }}
    </a>
</div>

@if(!empty($upgradeNotice))
    @include('theme::partials.upgrade_notice', ['upgradeNotice' => $upgradeNotice])
@endif

@if($featureAvailable)
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="d-flex flex-column gap-3">
                @include('theme::partials.activity.render', ['activity' => $status, 'detailView' => false])
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 20px;">
                <div class="card-header bg-warning bg-opacity-10 border-bottom-0 p-4">
                    <h5 class="fw-bold text-dark mb-2">{{ __('messages.status_promotion_setup_title') }}</h5>
                    <p class="text-muted small mb-0">{{ __('messages.status_promotion_no_refund_notice') }}</p>
                </div>

                <div class="card-body p-4">
                    <form
                        method="POST"
                        action="{{ url()->current() }}"
                        id="status-promotion-form"
                        data-quote-url="{{ route('ads.posts.quote', $status->id) }}"
                    >
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">{{ __('messages.status_promotion_objective_label') }}</label>
                            <select name="objective" id="promotion-objective" class="form-select form-select-lg bg-light border-0">
                                <option value="views">{{ __('messages.status_promotion_objective_views') }}</option>
                                <option value="comments">{{ __('messages.status_promotion_objective_comments') }}</option>
                                <option value="reactions">{{ __('messages.status_promotion_objective_reactions') }}</option>
                                <option value="days">{{ __('messages.status_promotion_objective_days') }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">{{ __('messages.status_promotion_target_quantity') }}</label>
                            <input
                                type="number"
                                name="target_quantity"
                                id="promotion-target"
                                class="form-control form-control-lg bg-light border-0"
                                min="{{ $settings['min_views_target'] }}"
                                max="{{ $settings['max_views_target'] }}"
                                value="{{ old('target_quantity', $settings['min_views_target']) }}"
                            >
                            @error('target_quantity')
                                <div class="text-danger fw-bold small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="promotion-quote-card" class="bg-primary bg-opacity-10 rounded-4 p-4 border border-primary border-opacity-25 mb-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="small fw-bold text-primary text-uppercase">{{ __('messages.status_promotion_quote_title') }}</div>
                                    <div id="promotion-quote-goal" class="fw-bold text-dark mt-1">
                                        {{ __('messages.status_promotion_goal_summary', ['objective' => __('messages.status_promotion_objective_views'), 'target' => $quote['target_quantity'] ?? $settings['min_views_target']]) }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div id="promotion-quote-price" class="fs-2 fw-bold text-primary lh-1">{{ $quote['charged_pts'] ?? 0 }}</div>
                                    <div class="small text-muted">{{ __('messages.status_promotion_pts_label') }}</div>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="bg-white rounded-3 p-3">
                                        <div class="small text-muted mb-1">{{ __('messages.status_promotion_smart_factor') }}</div>
                                        <div id="promotion-quote-factor" class="fw-bold text-dark">x{{ number_format((float) ($quote['smart_factor'] ?? 1), 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white rounded-3 p-3">
                                        <div class="small text-muted mb-1">{{ __('messages.status_promotion_delivery_cap') }}</div>
                                        <div id="promotion-quote-cap" class="fw-bold text-dark">{{ $quote['delivery_cap_impressions'] ?? 0 }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white rounded-3 p-3">
                                        <div class="small text-muted mb-1">{{ __('messages.status_promotion_estimated_days') }}</div>
                                        <div id="promotion-quote-days" class="fw-bold text-dark">{{ $quote['estimated_duration_days'] ?? 1 }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white rounded-3 p-3">
                                        <div class="small text-muted mb-1">{{ __('messages.status_promotion_current_balance') }}</div>
                                        <div id="promotion-balance" class="fw-bold text-dark">{{ (int) auth()->user()->pts }}</div>
                                    </div>
                                </div>
                            </div>

                            <div id="promotion-quote-message" class="fw-bold text-success text-center">
                                <i class="fa fa-check-circle me-1"></i>{{ __('messages.status_promotion_ready_to_launch') }}
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold" id="promotion-submit">
                            <i class="fa fa-rocket me-2"></i>{{ __('messages.status_promotion_launch') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@once
    @push('scripts')
        <script>
            (function () {
                const form = document.getElementById('status-promotion-form');
                if (!form) {
                    return;
                }

                const settings = @json($settings);
                const objectiveInput = document.getElementById('promotion-objective');
                const targetInput = document.getElementById('promotion-target');
                const submitButton = document.getElementById('promotion-submit');
                const messageBox = document.getElementById('promotion-quote-message');
                const quoteUrl = resolveQuoteUrl();

                const labels = {
                    views: @json(__('messages.status_promotion_objective_views')),
                    comments: @json(__('messages.status_promotion_objective_comments')),
                    reactions: @json(__('messages.status_promotion_objective_reactions')),
                    days: @json(__('messages.status_promotion_objective_days'))
                };

                function getCsrfToken() {
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');

                    return tokenMeta ? tokenMeta.getAttribute('content') : '';
                }

                function resolveQuoteUrl() {
                    const configuredUrl = form.getAttribute('data-quote-url') || '';
                    const fallbackPath = window.location.pathname.replace(/\/promote\/?$/, '/quote');

                    try {
                        const resolvedUrl = new URL(configuredUrl || fallbackPath, window.location.href);

                        if (resolvedUrl.origin !== window.location.origin) {
                            return resolvedUrl.pathname + resolvedUrl.search;
                        }

                        return resolvedUrl.pathname + resolvedUrl.search;
                    } catch (error) {
                        return fallbackPath;
                    }
                }

                async function readPayload(response) {
                    const responseText = await response.text();
                    const normalizedText = (responseText || '').replace(/^\uFEFF/, '').trim();

                    if (!normalizedText) {
                        return {};
                    }

                    try {
                        return JSON.parse(normalizedText);
                    } catch (error) {
                        const redirectedToLogin = response.redirected && /\/login(?:[/?#]|$)/i.test(response.url || '');

                        return {
                            message: (response.status === 419 || redirectedToLogin)
                                ? @json(__('messages.error_419_text'))
                                : @json(__('messages.status_promotion_quote_failed'))
                        };
                    }
                }

                function syncBounds() {
                    const objective = objectiveInput.value;
                    targetInput.min = settings['min_' + objective + '_target'];
                    targetInput.max = settings['max_' + objective + '_target'];

                    if (!targetInput.value || parseInt(targetInput.value, 10) < parseInt(targetInput.min, 10)) {
                        targetInput.value = targetInput.min;
                    }
                }

                async function refreshQuote() {
                    syncBounds();

                    const payload = new URLSearchParams();
                    payload.append('objective', objectiveInput.value);
                    payload.append('target_quantity', targetInput.value);
                    payload.append('_token', getCsrfToken());

                    try {
                        const response = await fetch(quoteUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: payload.toString()
                        });

                        const data = await readPayload(response);
                        if (!response.ok || !data || !data.quote) {
                            throw new Error(data.message || Object.values(data.errors || {}).flat().join(' ') || @json(__('messages.status_promotion_quote_failed')));
                        }

                        document.getElementById('promotion-quote-goal').textContent = @json(__('messages.status_promotion_goal_summary', ['objective' => ':objective', 'target' => ':target']))
                            .replace(':objective', labels[data.quote.objective])
                            .replace(':target', data.quote.target_quantity);
                        document.getElementById('promotion-quote-price').textContent = data.quote.charged_pts;
                        document.getElementById('promotion-quote-factor').textContent = 'x' + Number(data.quote.smart_factor).toFixed(2);
                        document.getElementById('promotion-quote-cap').textContent = data.quote.delivery_cap_impressions;
                        document.getElementById('promotion-quote-days').textContent = data.quote.estimated_duration_days;
                        document.getElementById('promotion-balance').textContent = data.balance_pts;

                        if (data.can_afford) {
                            messageBox.textContent = @json(__('messages.status_promotion_ready_to_launch'));
                            messageBox.style.color = '#0f766e';
                            submitButton.disabled = false;
                            submitButton.style.opacity = '1';
                        } else {
                            messageBox.textContent = @json(__('messages.status_promotion_insufficient_pts'));
                            messageBox.style.color = '#dc2626';
                            submitButton.disabled = true;
                            submitButton.style.opacity = '.6';
                        }
                    } catch (error) {
                        messageBox.textContent = error.message;
                        messageBox.style.color = '#dc2626';
                        submitButton.disabled = true;
                        submitButton.style.opacity = '.6';
                    }
                }

                objectiveInput.addEventListener('change', refreshQuote);
                targetInput.addEventListener('input', refreshQuote);
                refreshQuote();
            })();
        </script>
    @endpush
@endonce
@endsection
