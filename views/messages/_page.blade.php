@php
    $partnerConversationRouteKey = $partnerConversationRouteKey ?? ($partner ? \App\Models\Message::encodeConversationRouteKey(auth()->id(), $partner) : null);
    $threadLatestId = $messages->last()->id_msg ?? 0;
@endphp

@push('head')
    <link href="{{ theme_asset('css/messages.css') }}" rel="stylesheet" type="text/css">
@endpush

@extends('theme::layouts.master')

@section('content')
<div
    id="messages-app"
    class="messages-workspace account-hub-content {{ $partner ? 'has-active-conversation' : 'has-empty-conversation' }}"
    data-messages-app
    data-update-url="{{ route('messages.updates') }}"
    data-active-conversation="{{ $partnerConversationRouteKey ?? '' }}"
    data-send-url="{{ $partnerConversationRouteKey ? route('messages.send', $partnerConversationRouteKey) : '' }}"
    data-history-url="{{ $partnerConversationRouteKey ? route('messages.history', $partnerConversationRouteKey) : '' }}"
    data-latest-id="{{ $threadLatestId }}"
    data-latest-global-id="{{ (int) ($latestGlobalMessageId ?? 0) }}"
    data-unread-count="{{ (int) ($unreadConversationCount ?? 0) }}"
    data-max-attachment-bytes="5242880"
    data-sound-url="{{ theme_asset('sound/pop.wav') }}"
>
    <div class="messages-topbar d-flex justify-content-between align-items-center mb-3">
        <div class="messages-title-block">
            <p class="messages-kicker text-muted mb-0 small">{{ __('messages.my_profile') }}</p>
            <h2 class="fw-bold mb-0">{{ __('messages.msgs') }}</h2>
        </div>

        <div class="messages-topbar-actions d-flex gap-2">
            <a href="{{ route('messages.create') }}" class="btn btn-outline-primary btn-sm" aria-label="{{ __('messages.send_message') }}">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
            <a href="{{ route('messages.index', array_filter(['id' => $partnerConversationRouteKey, 'mark_all_read' => 1])) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-check-double me-1" aria-hidden="true"></i>
                <span>{{ __('messages.mark_all_read') }}</span>
            </a>
        </div>
    </div>

    <div class="messages-layout">
        @if($partner)
            <button type="button" class="messages-mobile-rail-toggle btn btn-sm btn-primary mb-3 d-lg-none" data-rail-toggle aria-expanded="false">
                <i class="fa fa-comments me-1" aria-hidden="true"></i>
                <span>{{ __('messages.msgs') }}</span>
            </button>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <aside class="messages-rail messages-panel card border-0 shadow-sm p-3">
                    <div class="messages-rail-head d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="messages-panel-label text-muted small text-uppercase">{{ __('messages.msgs') }}</span>
                            <span class="badge bg-secondary ms-1" data-message-rail-count>{{ $conversations->total() }}</span>
                        </div>

                        <a href="{{ route('messages.create') }}" class="btn btn-sm btn-link text-primary p-0" aria-label="{{ __('messages.send_message') }}">
                            <i class="fa fa-pen" aria-hidden="true"></i>
                        </a>
                    </div>

                    <div class="messages-search mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa fa-magnifying-glass text-muted" aria-hidden="true"></i></span>
                            <input type="search" id="message-search" class="form-control bg-light border-0" placeholder="{{ __('messages.for_search') }}">
                        </div>
                    </div>

                    <div class="messages-rail-list list-group" id="messages_conversation_list" data-conversation-list data-conversations-url="{{ route('messages.conversations') }}" data-has-more="{{ $conversations->hasPages() ? '1' : '0' }}">
                        @include('theme::messages.partials.conversations', [
                            'conversations' => $conversations,
                            'partner' => $partner,
                        ])
                    </div>
                </aside>
            </div>

            <div class="col-lg-8">
                <section class="messages-chat messages-panel card border-0 shadow-sm">
                    @if($partner)
                        @include('theme::messages.partials.chat_header', [
                            'partner' => $partner,
                            'partnerConversationRouteKey' => $partnerConversationRouteKey,
                        ])

                        <div class="messages-chat-body p-3" id="message_list" data-message-list style="max-height: 500px; overflow-y: auto;">
                            @include('theme::messages.partials.conversation', [
                                'messages' => $messages,
                                'partner' => $partner,
                                'user' => auth()->user(),
                                'hasOlderMessages' => $hasOlderMessages ?? false,
                                'hasPreviousConversationMessage' => $hasPreviousConversationMessage ?? false,
                                'precedingMessageEncrypted' => $precedingMessageEncrypted ?? false,
                            ])
                        </div>

                        @include('theme::messages.partials.composer')
                    @else
                        @include('theme::messages.partials.empty_state')
                    @endif
                </section>
            </div>
        </div>
    </div>

    <div class="messages-toast-stack" id="messages_toast_stack" aria-live="polite" aria-atomic="true"></div>
</div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/messages-app.js') }}"></script>
@endpush
