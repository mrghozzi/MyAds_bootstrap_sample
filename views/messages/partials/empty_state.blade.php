<div class="messages-chat-empty">
    <div class="messages-empty-badge">
        <i class="fa fa-comments" aria-hidden="true"></i>
    </div>
    <h3>{{ __('messages.select_conversation') ?? __('messages.msgs') }}</h3>
    <p>{{ __('messages.select_conversation_desc') ?? __('messages.no_msg') }}</p>
    <a href="{{ route('messages.create') }}" class="messages-topbar-btn btn-primary-action px-4 py-2">
        <i class="fa fa-plus me-1" aria-hidden="true"></i>
        <span>{{ __('messages.new_message') }}</span>
    </a>
</div>
