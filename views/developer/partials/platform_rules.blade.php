@php
    $developerPlatformSettings = app(\App\Services\DeveloperPlatformSettings::class);
    $developerRules = [];

    if ($developerPlatformSettings->requireAdminApproval()) {
        $developerRules[] = [
            'title' => __('messages.require_admin_approval'),
            'copy' => __('messages.require_admin_approval_desc'),
        ];
    }

    if ($developerPlatformSettings->getMinAccountAgeDays() > 0) {
        $developerRules[] = [
            'title' => __('messages.min_account_age_days'),
            'copy' => __('messages.dev_reason_min_account_age_days', ['days' => $developerPlatformSettings->getMinAccountAgeDays()]),
        ];
    }

    if ($developerPlatformSettings->getMinFollowersCount() > 0) {
        $developerRules[] = [
            'title' => __('messages.min_followers_count'),
            'copy' => __('messages.dev_reason_min_followers_count', ['count' => $developerPlatformSettings->getMinFollowersCount()]),
        ];
    }

    if ($developerPlatformSettings->requirePaidPlan()) {
        $developerRules[] = [
            'title' => __('messages.require_paid_plan'),
            'copy' => __('messages.require_paid_plan_desc'),
        ];
    }
@endphp

<div class="card border-0 shadow-sm rounded-4 dev-panel">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.platform_status') }}</h6>
    </div>
    <div class="card-body p-4 pt-0">
        <p class="text-muted small mb-0">{{ __('messages.dev_platform_settings_desc') }}</p>

        <div class="d-flex flex-wrap gap-2 mt-3">
            <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                <i class="fa fa-plug text-muted me-1"></i>
                {{ __('messages.v1_api') }}
            </span>
            <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                <i class="fa fa-shield-halved text-muted me-1"></i>
                {{ __('messages.oauth_secured') }}
            </span>
        </div>

        @if(count($developerRules) > 0)
            <div class="dev-rule-list mt-4">
                @foreach($developerRules as $rule)
                    <div class="dev-rule-item {{ $loop->last ? 'border-bottom-0 mb-0 pb-0' : '' }}">
                        <strong>{{ $rule['title'] }}</strong>
                        <span class="dev-rule-value text-muted small mt-1">{{ $rule['copy'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
