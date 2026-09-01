<header class="messages-chat-header">
    <div class="messages-chat-identity">
        <a class="messages-chat-avatar" href="{{ route('profile.short', $partner->publicRouteIdentifier()) }}">
            <img src="{{ $partner->avatarUrl() }}" alt="{{ $partner->username }}">
            <span class="{{ $partner->isOnline() ? 'online-indicator' : 'offline-indicator' }}"></span>
        </a>

        <div class="messages-chat-user">
            <div class="messages-chat-name-row">
                <a href="{{ route('profile.short', $partner->publicRouteIdentifier()) }}" class="text-decoration-none">
                    <h3>{{ $partner->username }}</h3>
                </a>
                @if($partner->hasVerifiedBadge())
                    <i class="fa fa-circle-check" title="Verified" aria-hidden="true"></i>
                @endif
            </div>
            <p class="messages-chat-status {{ $partner->isOnline() ? 'is-online' : '' }}">
                {{ $partner->isOnline() ? __('messages.online') : __('messages.offline') }}
            </p>
        </div>
    </div>

    <div class="messages-chat-actions">
        <a href="{{ route('messages.index', ['id' => $partnerConversationRouteKey, 'mark_all_read' => 1]) }}" class="messages-chat-action-btn" title="{{ __('messages.mark_all_read') }}">
            <i class="fa fa-check-double" aria-hidden="true"></i>
        </a>
        <a href="{{ route('profile.short', $partner->publicRouteIdentifier()) }}" class="messages-chat-action-btn" title="{{ __('messages.view_profile') ?? 'Profile' }}">
            <i class="fa fa-user" aria-hidden="true"></i>
        </a>
        <a href="{{ route('settings') }}" class="messages-chat-action-btn" title="{{ __('messages.account_settings') }}">
            <i class="fa fa-gear" aria-hidden="true"></i>
        </a>
    </div>
</header>
