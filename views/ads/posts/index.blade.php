@extends('theme::layouts.master')

@section('content')
<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, rgba(15,23,42,.92) 0%, rgba(29,78,216,.82) 55%, rgba(56,189,248,.78) 100%); position: relative; z-index: 1;">
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-4 rounded-circle bg-white p-2" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}" alt="overview-icon" style="width: 80px; height: 80px;">
                    <div>
                        <p class="text-white fs-3 fw-bold mb-1">{{ __('messages.status_promotions_title') }}</p>
                        <p class="text-white-50 mb-0 fs-5"><b>{{ __('messages.status_promotions_member_help') }}</b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 flex-wrap mb-4">
    <a href="{{ route('ads.index') }}" class="btn btn-light rounded-pill border fw-bold px-4 text-primary">
        <i class="fa fa-arrow-left me-2"></i> {{ __('messages.ads') }}
    </a>
    <a href="{{ route('ads.posts.index') }}" class="btn btn-warning text-white rounded-pill border-0 fw-bold px-4" style="background: linear-gradient(135deg, #f97316 0%, #f59e0b 100%);">
        <i class="fa fa-bullhorn me-2"></i> {{ __('messages.status_promotions_title') }}
    </a>
</div>

@if(!empty($upgradeNotice))
    @include('theme::partials.upgrade_notice', ['upgradeNotice' => $upgradeNotice])
@endif

@if($featureAvailable)
    <div style="display: grid; gap: 18px;">
        @forelse($promotions as $promotion)
            @php
                $status = $promotion->promotedStatus;
                $progress = $promotion->progressPercentage($status);
                $currentProgress = $promotion->currentProgressValue($status);
                $objectiveKey = 'messages.status_promotion_objective_' . $promotion->objective;
                $statusKey = 'messages.status_promotion_status_' . $promotion->status;
            @endphp
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <div class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill text-uppercase mb-3 fw-bold">
                            <i class="fa fa-bullhorn me-1"></i> {{ __('messages.status_promotion_ad_badge') }}
                        </div>
                        <h4 class="fw-bold mb-2 text-dark">{{ __('messages.status_promotion_campaign_id', ['id' => $promotion->id]) }}</h4>
                        <p class="text-muted mb-0">
                            {{ __('messages.status_promotion_goal_summary', [
                                'objective' => __($objectiveKey),
                                'target' => $promotion->target_quantity,
                            ]) }}
                        </p>
                    </div>
                    <div class="text-end">
                        <div class="fs-2 fw-bold text-primary lh-1">{{ $promotion->charged_pts }}</div>
                        <div class="small text-muted mb-2">{{ __('messages.status_promotion_pts_label') }}</div>
                        @php
                            $statusColors = [
                                \App\Models\StatusPromotion::STATUS_ACTIVE => ['bg' => 'bg-success bg-opacity-10', 'text' => 'text-success'],
                                \App\Models\StatusPromotion::STATUS_PAUSED => ['bg' => 'bg-warning bg-opacity-10', 'text' => 'text-warning'],
                                \App\Models\StatusPromotion::STATUS_COMPLETED => ['bg' => 'bg-danger bg-opacity-10', 'text' => 'text-danger'],
                                \App\Models\StatusPromotion::STATUS_EXPIRED => ['bg' => 'bg-danger bg-opacity-10', 'text' => 'text-danger'],
                                \App\Models\StatusPromotion::STATUS_BUDGET_CAPPED => ['bg' => 'bg-danger bg-opacity-10', 'text' => 'text-danger'],
                            ];
                            $currentColor = $statusColors[$promotion->status] ?? ['bg' => 'bg-light', 'text' => 'text-dark'];
                        @endphp
                        <div class="badge {{ $currentColor['bg'] }} {{ $currentColor['text'] }} rounded-pill px-3 py-2 fw-bold">
                            {{ __($statusKey) }}
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if($status)
                        <div class="mb-4">
                            <a href="{{ $status->promotionDestinationUrl() }}" class="fw-bold text-primary text-decoration-none">
                                <i class="fa fa-external-link-alt me-1"></i>{{ __('messages.status_promotion_view_post') }}
                            </a>
                        </div>
                    @endif

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2 small fw-bold">
                            <span class="text-dark">{{ __('messages.status_promotion_progress') }}</span>
                            <span class="text-muted">{{ $currentProgress }} / {{ $promotion->target_quantity }}</span>
                        </div>
                        <div class="progress rounded-pill bg-light" style="height: 12px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded-4 border h-100">
                                <div class="small text-muted mb-1">{{ __('messages.status_promotion_remaining_impressions') }}</div>
                                <div class="fs-5 fw-bold text-dark">{{ $promotion->remainingImpressions() }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-4 border border-warning border-opacity-25 h-100">
                                <div class="small text-warning mb-1">{{ __('messages.status_promotion_smart_factor') }}</div>
                                <div class="fs-5 fw-bold text-warning">x{{ number_format((float) $promotion->smart_factor, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 border border-primary border-opacity-25 h-100">
                                <div class="small text-primary mb-1">{{ __('messages.status_promotion_ends_at') }}</div>
                                <div class="fs-5 fw-bold text-primary">{{ optional($promotion->ends_at)->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    @if($promotion->status === \App\Models\StatusPromotion::STATUS_BUDGET_CAPPED)
                        <div class="alert alert-warning border-0 rounded-4 mt-4 mb-0 fw-bold">
                            <i class="fa fa-exclamation-triangle me-2"></i>{{ __('messages.status_promotion_budget_capped_help') }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5 text-center">
                    <i class="fa fa-bullhorn fa-3x text-muted opacity-25 mb-3"></i>
                    <h5 class="fw-bold text-dark">{{ __('messages.status_promotion_empty_title') }}</h5>
                    <p class="text-muted mb-0">{{ __('messages.status_promotion_empty_help') }}</p>
                </div>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 22px;">
        {{ $promotions->links() }}
    </div>
@endif
@endsection
