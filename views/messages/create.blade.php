@extends('theme::layouts.master')

@push('head')
    <link href="{{ theme_asset('css/messages.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="container py-4">
    <!-- Superdesign Hero Banner -->
    <div class="messages-create-banner">
        <p class="messages-kicker text-white-50 mb-1">{{ __('messages.my_profile') }}</p>
        <h1 class="messages-create-title">{{ __('messages.new_message') }}</h1>
        <p class="messages-create-desc">{{ __('messages.send_message') }}</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-4 mb-4 shadow-sm border-0 d-flex align-items-center gap-3">
            <i class="fa fa-triangle-exclamation fs-4"></i>
            <div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar Actions -->
        <div class="col-lg-4 col-md-5">
            <div class="messages-panel p-4 mb-4">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="fa fa-sliders text-primary"></i>
                    <span>{{ __('messages.actions') }}</span>
                </h5>
                <p class="text-muted small mb-4">
                    {{ __('messages.select_conversation_desc') ?? 'Start a private direct conversation with any community member.' }}
                </p>

                <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary w-100 py-2 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                    <i class="fa fa-arrow-left"></i>
                    <span>{{ __('messages.back_to_inbox') ?? __('messages.msgs') }}</span>
                </a>
            </div>

            <!-- Quick Tips Card -->
            <div class="messages-panel p-4 bg-light bg-opacity-50">
                <h6 class="fw-bold mb-2 text-uppercase text-muted small letter-spacing-1">
                    <i class="fa fa-shield-halved text-success me-1"></i>
                    <span>{{ __('messages.end_to_end_encrypted') }}</span>
                </h6>
                <p class="small text-muted mb-0">
                    {{ __('messages.private_messages_encryption_notice') }}
                </p>
            </div>
        </div>

        <!-- Compose Form -->
        <div class="col-lg-8 col-md-7">
            <div class="messages-create-card">
                <form action="{{ route('messages.store') }}" method="POST" id="new-message-form">
                    @csrf

                    <!-- Recipient -->
                    <div class="mb-4">
                        <label for="recipient" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="fa fa-user me-1 text-primary"></i> {{ __('messages.recipient') }}
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">@</span>
                            <input
                                type="text"
                                id="recipient"
                                name="recipient"
                                class="form-control form-control-lg bg-light border-start-0 rounded-end-3 fs-6 fw-semibold @error('recipient') is-invalid @enderror"
                                placeholder="{{ __('messages.name_placeholder') }}..."
                                value="{{ old('recipient', $recipient ?? '') }}"
                                required
                                autofocus
                            >
                        </div>
                        @error('recipient')
                            <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Message Body -->
                    <div class="mb-4">
                        <label for="message_body" class="form-label fw-bold text-muted small text-uppercase">
                            <i class="fa fa-message me-1 text-primary"></i> {{ __('messages.message') ?? __('messages.text') }}
                        </label>
                        <textarea
                            id="message_body"
                            name="message"
                            class="form-control bg-light rounded-3 p-3 fs-6 fw-medium @error('message') is-invalid @enderror"
                            rows="6"
                            placeholder="{{ __('messages.write_reply_placeholder') ?? 'Type your message here...' }}"
                            required
                            style="resize: vertical; min-height: 140px;"
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <div class="text-danger small mt-1 fw-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <a href="{{ route('messages.index') }}" class="btn btn-link text-muted text-decoration-none fw-bold small">
                            {{ __('messages.cancel') }}
                        </a>

                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold shadow-sm d-flex align-items-center gap-2" style="background: linear-gradient(135deg, var(--msg-primary), var(--msg-primary-hover)); border: none;">
                            <i class="fa fa-paper-plane"></i>
                            <span>{{ __('messages.send') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
