@extends('theme::layouts.master')

@section('title', $app->name . ' - ' . __('messages.dev_platform'))

@push('head')
    @include('theme::developer.partials.styles')
@endpush

@section('content')
@php
    $redirectUriCount = count($app->redirect_uris ?? []);
    $requestedScopeCount = count($app->requested_scopes ?? []);
@endphp

<div class="section-banner">
    <div class="section-banner-icon" style="display: flex; align-items: center; justify-content: center;">
        <i class="fa fa-cube" style="font-size: 26px; color: #fff;"></i>
    </div>
    <p class="section-banner-title">{{ $app->name }}</p>
    <p class="section-banner-text">{{ parse_url($app->domain, PHP_URL_HOST) ?: $app->domain }}</p>
</div>

<div class="row g-4">
    <div class="col-lg-3">
        <div class="d-flex flex-column gap-4">
            @include('theme::developer.partials.nav', ['active' => 'apps'])
            @include('theme::developer.partials.platform_rules')
        </div>
    </div>

    <div class="col-lg-6">
        <div class="d-flex flex-column gap-4">
            @if(session('success'))
                <div class="alert alert-success rounded-4 shadow-sm mb-0" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-1">{{ __('messages.app_specifications') }}</p>
                            <h2 class="h4 fw-bold mb-2">{{ $app->name }}</h2>
                            <p class="text-muted small mb-0">{{ $app->description }}</p>
                        </div>
                        @include('theme::developer.partials.status_badge', ['status' => $app->status])
                    </div>

                    <div class="dev-stat-grid mt-4">
                        <div class="dev-stat-card p-3 mb-2">
                            <span>{{ __('messages.current_status') }}</span>
                            <strong>{{ __('messages.app_status_' . $app->status) }}</strong>
                        </div>
                        <div class="dev-stat-card p-3 mb-2">
                            <span>{{ __('messages.redirect_uris') }}</span>
                            <strong>{{ $redirectUriCount }}</strong>
                        </div>
                        <div class="dev-stat-card p-3">
                            <span>{{ __('messages.requested_scopes') }}</span>
                            <strong>{{ $requestedScopeCount }}</strong>
                        </div>
                    </div>
                </div>
            </div>

        @if($app->status === 'draft')
            <div class="alert alert-info border-0 bg-primary bg-opacity-10 text-primary rounded-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <strong class="mb-0"><i class="fa fa-info-circle me-1"></i>{{ __('messages.app_draft_notice') }}</strong>
                <form action="{{ route('developer.apps.submit', $app->id) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4 btn-sm">{{ __('messages.submit_for_review') }}</button>
                </form>
            </div>
        @elseif($app->status === 'pending_review')
            <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-dark rounded-4 mb-4">
                <strong><i class="fa fa-exclamation-triangle me-1"></i>{{ __('messages.app_status_pending_review') }}</strong>
                <p class="mb-0 mt-1 small">{{ __('messages.dev_pending_notice') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 mb-4">
                <strong><i class="fa fa-exclamation-circle me-1"></i>{{ __('messages.save_changes') }}</strong>
                <ul class="mb-0 mt-2 small text-danger">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.api_credentials') }}</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="text-muted small mb-4">{{ __('messages.dev_credentials_help') }}</p>

                    <div class="dev-credential-field mb-4">
                        <label for="developer-client-id" class="form-label small fw-bold">{{ __('messages.client_id') }}</label>
                        <div class="dev-credential-input">
                            <input id="developer-client-id" type="text" class="form-control dev-control" value="{{ $app->client_id }}" readonly>
                            <button type="button" class="js-dev-copy dev-inline-icon-btn" data-copy-target="#developer-client-id">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="dev-credential-field mb-4">
                        <label for="developer-client-secret" class="form-label small fw-bold">Client Secret</label>
                        <div class="dev-credential-input">
                            <input id="developer-client-secret" type="password" class="form-control dev-control" value="{{ $app->client_secret }}" readonly>
                            <button type="button" class="js-dev-toggle-secret dev-inline-icon-btn" data-target="#developer-client-secret">
                                <i class="fa fa-eye"></i>
                            </button>
                            <button type="button" class="js-dev-copy dev-inline-icon-btn" data-copy-target="#developer-client-secret">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('developer.apps.rotate_secret', $app->id) }}" method="POST" onsubmit="return confirm('@lang('messages.rotate_secret_confirm')')">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary rounded-pill fw-bold px-4">{{ __('messages.rotate_secret') }}</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.app_settings') }}</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <form action="{{ route('developer.apps.update', $app->id) }}" method="POST" class="dev-form-layout">
                        @csrf
                        @method('PUT')

                        @include('theme::developer.partials.form_fields', [
                            'app' => $app,
                            'scopes' => $scopes,
                            'scopeInputPrefix' => 'developer_show_scope',
                        ])

                        <div class="dev-form-actions mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4">{{ __('messages.save_changes') }}</button>
                            <a href="{{ route('developer.apps.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4">{{ __('messages.my_apps') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="d-flex flex-column gap-4">

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.take_action') }}</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="dev-rule-list">
                        <div class="dev-rule-item">
                            <strong>{{ __('messages.current_status') }}</strong>
                            <span class="dev-rule-value">{{ __('messages.app_status_' . $app->status) }}</span>
                        </div>
                        <div class="dev-rule-item">
                            <strong>{{ __('messages.domain') }}</strong>
                            <span class="dev-rule-value">{{ $app->domain }}</span>
                        </div>
                        @if($app->status === 'draft')
                            <div class="dev-rule-item border-bottom-0 mb-0 pb-0">
                                <strong>{{ __('messages.submit_for_review') }}</strong>
                                <span class="dev-rule-value text-muted small mt-1">{{ __('messages.dev_create_help') }}</span>
                            </div>
                        @endif
                    </div>

                    @if($app->status === 'draft')
                        <form action="{{ route('developer.apps.submit', $app->id) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4 w-100">{{ __('messages.submit_for_review') }}</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.app_about') }}</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="text-muted small mb-0">{{ $app->description }}</p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-link text-muted me-1"></i>
                            {{ $redirectUriCount }} {{ __('messages.redirect_uris') }}
                        </span>
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-shield-halved text-muted me-1"></i>
                            {{ $requestedScopeCount }} {{ __('messages.requested_scopes') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('theme::developer.partials.scripts')
@endpush
