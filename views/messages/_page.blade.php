@php
    $partnerConversationRouteKey = $partnerConversationRouteKey ?? ($partner ? \App\Models\Message::encodeConversationRouteKey(auth()->id(), $partner) : null);
    $threadLatestId = $messages->last()->id_msg ?? 0;
@endphp

@push('head')
    <link href="{{ theme_asset('css/messages.css') }}" rel="stylesheet" type="text/css">
@endpush

@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <div
        id="messages-app"
        class="messages-workspace {{ $partner ? 'has-active-conversation' : 'has-empty-conversation' }}"
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
        <!-- Topbar / Header -->
        <div class="messages-topbar">
            <div class="messages-title-block">
                <p class="messages-kicker">{{ __('messages.my_profile') }}</p>
                <h2>{{ __('messages.msgs') }}</h2>
            </div>

            <div class="messages-topbar-actions">
                <a href="{{ route('messages.create') }}" class="messages-topbar-btn btn-primary-action" title="{{ __('messages.new_message') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span class="d-none d-sm-inline">{{ __('messages.new_message') }}</span>
                </a>
                <a href="{{ route('messages.index', array_filter(['id' => $partnerConversationRouteKey, 'mark_all_read' => 1])) }}" class="messages-topbar-btn btn-secondary-action" title="{{ __('messages.mark_all_read') }}">
                    <i class="fa fa-check-double" aria-hidden="true"></i>
                    <span class="d-none d-md-inline">{{ __('messages.mark_all_read') }}</span>
                </a>
            </div>
        </div>

        <!-- Mobile Rail Toggle for Small Screens -->
        @if($partner)
            <div class="d-lg-none mb-3">
                <button type="button" class="btn btn-outline-primary btn-sm w-100 rounded-3 py-2 fw-bold" data-rail-toggle aria-expanded="false">
                    <i class="fa fa-comments me-2" aria-hidden="true"></i>
                    <span>{{ __('messages.msgs') }} ({{ $conversations->total() }})</span>
                </button>
            </div>
        @endif

        <!-- Main Messaging 2-Column Grid -->
        <div class="row g-4">
            <!-- Left Column: Conversations Rail -->
            <div class="col-lg-4">
                <aside class="messages-panel messages-rail">
                    <div class="messages-rail-head">
                        <div class="messages-rail-title-wrap">
                            <span class="messages-panel-label">{{ __('messages.msgs') }}</span>
                            <span class="messages-rail-badge" data-message-rail-count>{{ $conversations->total() }}</span>
                        </div>

                        <a href="{{ route('messages.create') }}" class="text-primary text-decoration-none" title="{{ __('messages.new_message') }}">
                            <i class="fa fa-pen-to-square" aria-hidden="true"></i>
                        </a>
                    </div>

                    <div class="messages-rail-search">
                        <div class="messages-search-wrap">
                            <input type="search" id="message-search" class="messages-search-input" placeholder="{{ __('messages.for_search') }}...">
                            <i class="fa fa-magnifying-glass messages-search-icon" aria-hidden="true"></i>
                        </div>
                    </div>

                    <div class="messages-rail-list" id="messages_conversation_list" data-conversation-list data-conversations-url="{{ route('messages.conversations') }}" data-has-more="{{ $conversations->hasPages() ? '1' : '0' }}">
                        @include('theme::messages.partials.conversations', [
                            'conversations' => $conversations,
                            'partner' => $partner,
                        ])
                    </div>
                </aside>
            </div>

            <!-- Right Column: Chat Section -->
            <div class="col-lg-8">
                <section class="messages-panel messages-chat">
                    @if($partner)
                        @include('theme::messages.partials.chat_header', [
                            'partner' => $partner,
                            'partnerConversationRouteKey' => $partnerConversationRouteKey,
                        ])

                        <div class="messages-chat-body" id="message_list" data-message-list>
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

        <!-- Incoming Toast Notifications Stack -->
        <div class="messages-toast-stack" id="messages_toast_stack" aria-live="polite" aria-atomic="true"></div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/messages-app.js') }}"></script>
@endpush
