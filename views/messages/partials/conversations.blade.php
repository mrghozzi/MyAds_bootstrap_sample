@forelse($conversations as $conversation)
    @php
        $partnerItem = $conversation['user'];
        $lastMessage = $conversation['message'];
        $conversationKey = $conversation['route_key'] ?? \App\Models\Message::encodeConversationRouteKey(auth()->id(), $partnerItem);
        $isActive = $partner && (int) $partner->id === (int) $partnerItem->id;
        $previewSource = trim(strip_tags((string) ($lastMessage->text ?? '')));
        if ($previewSource === '' && !empty($lastMessage->attachment_path)) {
            $previewSource = __('messages.file');
        }
        $previewText = \Illuminate\Support\Str::limit($previewSource, 75);
        $timeLabel = \Carbon\Carbon::createFromTimestamp($lastMessage->time)->diffForHumans(null, true);
    @endphp

    <a
        class="messages-conversation {{ $isActive ? 'is-active' : '' }} {{ $conversation['unread'] ? 'is-unread' : '' }}"
        href="{{ route('messages.show', $conversationKey) }}"
        data-conversation-row
        data-conversation-key="{{ $conversationKey }}"
        data-name="{{ \Illuminate\Support\Str::lower($partnerItem->username) }}"
        data-message="{{ \Illuminate\Support\Str::lower($previewSource) }}"
    >
        <div class="messages-avatar-wrap">
            <img src="{{ $partnerItem->avatarUrl() }}" alt="{{ $partnerItem->username }}" class="messages-avatar-img">
            <span class="{{ $partnerItem->isOnline() ? 'online-indicator' : 'offline-indicator' }}"></span>
        </div>

        <div class="messages-conversation-body">
            <div class="messages-conversation-head">
                <h6 class="messages-conversation-name">{{ $partnerItem->username }}</h6>
                <time class="messages-conversation-time">{{ $timeLabel }}</time>
            </div>
            <div class="messages-conversation-preview">
                <p class="messages-conversation-text">
                    @if((int) $lastMessage->us_env === (int) auth()->id())
                        <i class="fa fa-reply me-1 text-muted opacity-75"></i>
                    @endif
                    {{ $previewText }}
                </p>
                @if($conversation['unread'])
                    <span class="messages-unread-dot" aria-hidden="true"></span>
                @endif
            </div>
        </div>
    </a>
@empty
    <div class="messages-rail-empty text-center p-5" data-conversation-empty>
        <i class="fa fa-comments fa-2x mb-3 text-muted opacity-50" aria-hidden="true"></i>
        <p class="text-muted small mb-0">{{ __('messages.no_msg') }}</p>
    </div>
@endforelse
