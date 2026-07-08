<div class="messages-chat-empty text-center p-5">
    <div class="messages-empty-badge mb-3 d-inline-flex justify-content-center align-items-center bg-light p-4 rounded-circle">
        <i class="fa fa-message fa-3x text-muted" aria-hidden="true"></i>
    </div>
    <h3 class="fw-bold mb-2">{{ __('messages.msgs') }}</h3>
    <p class="text-muted mb-4">{{ __('messages.no_msg') }}</p>
    <a href="{{ route('messages.create') }}" class="btn btn-primary px-4 py-2">
        <i class="fa fa-pen me-2" aria-hidden="true"></i>
        <span>{{ __('messages.send_message') }}</span>
    </a>
</div>
