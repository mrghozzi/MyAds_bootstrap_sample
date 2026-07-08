@extends('theme::layouts.master')

@section('title', __('messages.dev_platform'))

@push('head')
    @include('theme::developer.partials.styles')
@endpush

@section('content')
@php
    $developerApps = collect($apps ?? []);
    $activeAppsCount = $developerApps->where('status', 'active')->count();
    $pendingAppsCount = $developerApps->where('status', 'pending_review')->count();
@endphp

<div class="bg-primary bg-gradient rounded-4 text-white p-5 mb-4 text-center shadow-sm">
    <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle mb-3" style="width: 64px; height: 64px;">
        <i class="fa fa-code-branch fs-3"></i>
    </div>
    <h1 class="h3 fw-bold mb-2">{{ __('messages.dev_platform') }}</h1>
    <p class="mb-0 opacity-75">{{ __('messages.dev_platform_desc', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>
</div>

<div class="row g-4">
    <!-- Left Sidebar -->
    <div class="col-lg-3">
        <div class="d-flex flex-column gap-4">
            @include('theme::developer.partials.nav', ['active' => 'overview'])

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ auth()->check() && $eligible ? __('messages.applications') : __('messages.platform_info') }}</h6>
                </div>
                <div class="card-body p-4">
                    @if(auth()->check() && $eligible)
                        <div class="dev-stat-grid dev-stat-grid--compact">
                            <div class="dev-stat-card p-3 mb-2">
                                <span>{{ __('messages.total_apps') }}</span>
                                <strong>{{ $developerApps->count() }}</strong>
                            </div>
                            <div class="dev-stat-card p-3 mb-2">
                                <span>{{ __('messages.active_apps') }}</span>
                                <strong>{{ $activeAppsCount }}</strong>
                            </div>
                            <div class="dev-stat-card p-3">
                                <span>{{ __('messages.pending_review') }}</span>
                                <strong>{{ $pendingAppsCount }}</strong>
                            </div>
                        </div>
                    @else
                        <p class="dev-card-copy mb-3">{{ __('messages.v1_api_desc') }}</p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill py-2 px-3">
                                <i class="fa fa-plug me-1"></i> {{ __('messages.v1_api') }}
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill py-2 px-3">
                                <i class="fa fa-shield-halved me-1"></i> {{ __('messages.oauth_secured') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            @include('theme::developer.partials.platform_rules')
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-6">
        @if(session('error'))
            <div class="alert alert-danger rounded-4 shadow-sm mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="d-flex flex-column gap-4">
            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-1">{{ __('messages.dev_docs') }}</p>
                            <h2 class="h4 fw-bold mb-2">{{ __('messages.dev_docs') }}</h2>
                            <p class="text-muted small mb-0">{{ __('messages.dev_docs_intro') }}</p>
                        </div>

                        @auth
                            @if($eligible)
                                <a href="{{ $developerApps->isEmpty() ? route('developer.apps.create') : route('developer.apps.index') }}" class="btn btn-primary rounded-pill fw-bold px-4">
                                    {{ $developerApps->isEmpty() ? __('messages.create_app') : __('messages.manage_apps') }}
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill fw-bold px-4">{{ __('messages.login') }}</a>
                        @endauth
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-user-shield text-primary me-1"></i> OAuth 2.0
                        </span>
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-bolt text-warning me-1"></i> Widgets
                        </span>
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-share-nodes text-success me-1"></i> Share API
                        </span>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-4">
                <article class="card border-0 shadow-sm rounded-4 dev-panel dev-doc-card">
                    <div class="card-body p-4">
                        <span class="dev-doc-icon mb-4">
                            <i class="fa fa-user-shield"></i>
                        </span>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-uppercase small fw-bold text-muted mb-1">OAuth 2.0</p>
                                <h3 class="h5 fw-bold mb-0">Authorization Code Flow</h3>
                            </div>
                            <span class="badge bg-primary rounded-circle p-2">1</span>
                        </div>
                        <p class="text-muted small">{{ __('messages.dev_oauth_desc', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>

                        <div class="dev-code-block mt-3">
                            <div class="dev-code-toolbar">
                                <span>Code Sample</span>
                                <button type="button" class="dev-copy-btn js-dev-copy" data-copy="GET /oauth/authorize?client_id=YOUR_CLIENT_ID&amp;redirect_uri=YOUR_URL&amp;response_type=code&amp;scope=user.profile.read">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
                            <pre><code>GET /oauth/authorize?client_id=YOUR_CLIENT_ID
&amp;redirect_uri=YOUR_URL
&amp;response_type=code
&amp;scope=user.profile.read</code></pre>
                        </div>
                    </div>
                </article>

                <article class="card border-0 shadow-sm rounded-4 dev-panel dev-doc-card">
                    <div class="card-body p-4">
                        <span class="dev-doc-icon mb-4">
                            <i class="fa fa-wand-magic-sparkles"></i>
                        </span>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-uppercase small fw-bold text-muted mb-1">Widgets</p>
                                <h3 class="h5 fw-bold mb-0">Embed MYADS Surfaces</h3>
                            </div>
                            <span class="badge bg-primary rounded-circle p-2">2</span>
                        </div>
                        <p class="text-muted small">{{ __('messages.dev_widgets_desc', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>

                        <ul class="list-unstyled d-flex flex-column gap-2 mt-3 mb-0 small text-muted">
                            <li class="d-flex gap-2">
                                <i class="fa fa-check-circle text-success mt-1"></i>
                                <span><strong>Follow Widget:</strong> <code>&lt;div id="myads-widget-follow-APPID"&gt;&lt;/div&gt;</code></span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="fa fa-check-circle text-success mt-1"></i>
                                <span><strong>Profile Widget:</strong> <code>&lt;div id="myads-widget-profile-APPID"&gt;&lt;/div&gt;</code></span>
                            </li>
                            <li class="d-flex gap-2">
                                <i class="fa fa-check-circle text-success mt-1"></i>
                                <span><strong>Content Widget:</strong> <code>&lt;div id="myads-widget-content-APPID"&gt;&lt;/div&gt;</code></span>
                            </li>
                        </ul>
                    </div>
                </article>

                <article class="card border-0 shadow-sm rounded-4 dev-panel dev-doc-card">
                    <div class="card-body p-4">
                        <span class="dev-doc-icon mb-4">
                            <i class="fa fa-share-nodes"></i>
                        </span>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-uppercase small fw-bold text-muted mb-1">Share API</p>
                                <h3 class="h5 fw-bold mb-0">Pre-fill the Composer</h3>
                            </div>
                            <span class="badge bg-primary rounded-circle p-2">3</span>
                        </div>
                        <p class="text-muted small">{{ __('messages.dev_share_desc') }}</p>

                        <div class="dev-code-block mt-3">
                            <div class="dev-code-toolbar">
                                <span>Code Sample</span>
                                <button type="button" class="dev-copy-btn js-dev-copy" data-copy="GET /share?text=Hello+World&amp;url=https://example.com">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
                            <pre><code>GET /share?text=Hello+World
&amp;url=https://example.com</code></pre>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-3">
        @include('theme::developer.partials.account_state', ['apps' => $developerApps])
    </div>
</div>
@endsection

@push('scripts')
    @include('theme::developer.partials.scripts')
@endpush
