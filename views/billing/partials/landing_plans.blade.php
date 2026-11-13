@php
    $landingPlans = \App\Models\SubscriptionPlan::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->take(4)
        ->get();
    $baseCurrency = \App\Support\SubscriptionSettings::get('base_currency_code', 'USD');
@endphp

@if($landingPlans->isNotEmpty())
    <section class="py-5 position-relative bg-light bg-opacity-25 subscription-landing-showcase" id="pricing-plans">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2 mb-3 fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">
                    <i class="fa fa-crown me-2"></i> {{ __('messages.billing_landing_plans_eyebrow') }}
                </span>
                <h2 class="display-5 fw-black text-dark mb-3">
                    {{ __('messages.billing_landing_plans_title') }}
                </h2>
                <p class="lead text-muted mx-auto" style="max-width: 650px;">
                    {{ __('messages.billing_landing_plans_subtitle') }}
                </p>
            </div>

            <div class="row g-4 justify-content-center align-items-stretch">
                @foreach($landingPlans as $plan)
                    @php
                        $accent = $plan->accent_color ?: '#615dfa';
                        $layoutStyle = $plan->layout_style ?? 'default';
                        $cardClass = function_exists('apply_filters') 
                            ? apply_filters('billing_landing_plans_card_class', 'card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative d-flex flex-column justify-content-between transition-all hover-translate-y', $plan) 
                            : 'card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative d-flex flex-column justify-content-between transition-all hover-translate-y';
                        $customCardStyle = '';
                        if ($layoutStyle === 'glassmorphism') {
                            $customCardStyle = 'background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5) !important; box-shadow: 0 10px 30px rgba(31, 38, 135, 0.1) !important;';
                        } elseif ($layoutStyle === 'neon_glow') {
                            $customCardStyle = "box-shadow: 0 0 25px {$accent}35 !important; border: 2px solid {$accent} !important;";
                        } elseif ($layoutStyle === 'minimal') {
                            $customCardStyle = 'box-shadow: none !important; border: 1px solid #e2e8f0 !important;';
                        }
                    @endphp
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="{{ $cardClass }}" style="border-top: 6px solid {{ $accent }} !important; {{ $customCardStyle }}">
                            <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                        <div>
                                            <h4 class="fw-black text-dark mb-1">{{ $plan->name }}</h4>
                                            <p class="small text-muted mb-0" style="min-height: 40px;">{{ $plan->description }}</p>
                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-1">
                                            @if($plan->is_featured)
                                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                                                    <i class="fa fa-star me-1 text-warning"></i> {{ __('messages.billing_featured_plan') }}
                                                </span>
                                            @endif
                                            @if(function_exists('do_action'))
                                                @php(do_action('billing_landing_plans_card_badge', $plan))
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Price Block --}}
                                    <div class="text-center my-4 py-3 bg-light bg-opacity-50 rounded-4 border border-light">
                                        @if($plan->is_on_sale)
                                            <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap mb-1">
                                                <span class="text-decoration-line-through text-muted fs-6">{{ number_format((float) $plan->base_price, 2) }}</span>
                                                <span class="h1 fw-black text-danger mb-0">{{ number_format((float) $plan->sale_price, 2) }}</span>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-2 py-1 fs-11">
                                                    -{{ $plan->discount_percentage }}%
                                                </span>
                                            </div>
                                            @if($plan->sale_ends_at)
                                                <div class="smaller text-danger fw-bold">
                                                    <i class="fa fa-clock me-1"></i>{{ __('messages.billing_sale_ends_in') }}: {{ $plan->sale_ends_at->diffForHumans() }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="h4 fw-black text-muted mb-0 me-1">{{ $baseCurrency }}</span>
                                                <h1 class="display-5 fw-black mb-0 text-dark">{{ number_format((float) $plan->base_price, 2) }}</h1>
                                            </div>
                                        @endif
                                        <div class="badge bg-white text-muted rounded-pill px-3 py-2 mt-2 border border-light shadow-sm smaller fw-bold">
                                            {{ $plan->is_lifetime ? __('messages.billing_lifetime') : __('messages.billing_duration_days_value', ['days' => $plan->duration_days]) }}
                                        </div>
                                    </div>

                                    {{-- Free Trial Badge if available --}}
                                    @if($plan->has_trial)
                                        <div class="p-2 mb-3 rounded-3 text-center border border-success border-opacity-25 bg-success bg-opacity-10 text-success fw-bold smaller">
                                            <i class="fa fa-gift me-1"></i> {{ $plan->trial_days }} {{ __('messages.days') }} {{ __('messages.billing_trial_free_tag') }}
                                        </div>
                                    @endif

                                    {{-- Highlights --}}
                                    @if(!empty($plan->marketing_bullets))
                                        <ul class="list-unstyled mb-4 d-grid gap-2">
                                            @foreach(array_slice((array) $plan->marketing_bullets, 0, 5) as $bullet)
                                                <li class="d-flex align-items-center small">
                                                    <i class="fa fa-check-circle me-2" style="color: {{ $accent }};"></i>
                                                    <span class="text-dark fw-medium">{{ $bullet }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('billing.plans') }}" class="btn btn-lg w-100 rounded-pill fw-black shadow-sm py-3 transition-all hover-scale" style="background-color: {{ $accent }}; color: #ffffff; border: none;">
                                        <span>{{ $plan->has_trial ? __('messages.billing_start_free_trial') : __('messages.billing_choose_plan') }}</span>
                                        <i class="fa fa-arrow-right ms-2 fs-12"></i>
                                    </a>
                                    @if(function_exists('do_action'))
                                        @php(do_action('billing_landing_plans_card_footer', $plan))
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('billing.plans') }}" class="btn btn-outline-primary rounded-pill px-5 py-3 fw-bold shadow-sm transition-all hover-translate-y">
                    <i class="fa fa-table me-2"></i>{{ __('messages.billing_view_all_plans_matrix') }}
                </a>
            </div>
        </div>
    </section>
@endif
