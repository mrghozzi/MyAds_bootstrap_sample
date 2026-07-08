@php
    $billingAvailable = app(\App\Services\V420SchemaService::class)->supports('subscriptions_billing');
    $showBillingLink = $billingAvailable && (\App\Support\SubscriptionSettings::isEnabled() || request()->routeIs('billing.*'));
@endphp

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 border border-light transition-all">
    <div class="card-header bg-white py-3 px-4 border-bottom">
        <h6 class="fw-black mb-0 text-dark text-uppercase smaller letter-spacing-1">{{ __('messages.account_settings') }}</h6>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.edit') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-user-edit {{ request()->routeIs('profile.edit') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.edit_profile') }}</span>
        </a>
        <a href="{{ route('profile.privacy') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.privacy') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-shield-alt {{ request()->routeIs('profile.privacy') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.privacy_settings') }}</span>
        </a>
        <a href="{{ route('profile.social') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.social') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-share-nodes {{ request()->routeIs('profile.social') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.social_links') }}</span>
        </a>
        <a href="{{ route('profile.notifications') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.notifications') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-bell {{ request()->routeIs('profile.notifications') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.notification_settings') }}</span>
        </a>
        <a href="{{ route('profile.sessions') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.sessions') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-shield-halved {{ request()->routeIs('profile.sessions') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.active_sessions') }}</span>
        </a>
        <a href="{{ route('profile.apps') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.apps') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-rocket {{ request()->routeIs('profile.apps') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.authorized_apps') }}</span>
        </a>
        <a href="{{ route('profile.blocks') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.blocks') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-ban {{ request()->routeIs('profile.blocks') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.blocked_users') ?? 'Blocked Users' }}</span>
        </a>
        @if($showBillingLink)
            <a href="{{ route('billing.dashboard') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('billing.*') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
                <i class="fa fa-credit-card {{ request()->routeIs('billing.*') ? 'text-primary' : 'opacity-50' }}"></i>
                <span class="smaller">{{ __('messages.billing_feature_title') }}</span>
            </a>
        @endif
        @if(\App\Models\Option::where('name', 'monetization')->where('o_type', 'plugins')->where('o_valuer', '1')->exists())
            <a href="{{ route('monetization.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('monetization.*') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
                <i class="fa fa-coins {{ request()->routeIs('monetization.*') ? 'text-primary' : 'opacity-50' }}"></i>
                <span class="smaller">{{ __('monetization::messages.monetization') }}</span>
            </a>
        @endif
        <a href="{{ route('profile.badges') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.badges') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-award {{ request()->routeIs('profile.badges') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.badges') }}</span>
        </a>
        <a href="{{ route('profile.history') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.history') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-history {{ request()->routeIs('profile.history') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.pts_history') }}</span>
        </a>
        <a href="{{ route('profile.personal_activity') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all {{ request()->routeIs('profile.personal_activity') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 fw-black' : 'text-muted fw-bold border-light' }}">
            <i class="fa fa-list-alt {{ request()->routeIs('profile.personal_activity') ? 'text-primary' : 'opacity-50' }}"></i>
            <span class="smaller">{{ __('messages.personal_activity') }}</span>
        </a>
        <a href="{{ route('profile.show', $user->username) }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center gap-3 transition-all text-muted fw-bold border-light">
            <i class="fa fa-user-circle opacity-50"></i>
            <span class="smaller">{{ __('messages.view_profile') }}</span>
        </a>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }</style>
