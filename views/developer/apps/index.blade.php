@extends('theme::layouts.master')

@section('title', __('messages.my_apps'))

@push('head')
    @include('theme::developer.partials.styles')
@endpush

@section('content')
@php
    $developerApps = collect($apps ?? []);
@endphp

<div class="section-banner">
    <div class="section-banner-icon" style="display: flex; align-items: center; justify-content: center;">
        <i class="fa fa-cubes" style="font-size: 26px; color: #fff;"></i>
    </div>
    <p class="section-banner-title">{{ __('messages.my_apps') }}</p>
    <p class="section-banner-text">{{ __('messages.dev_platform_desc', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>
</div>

<div class="row g-4">
    <div class="col-lg-3">
        <div class="d-flex flex-column gap-4">
            @include('theme::developer.partials.nav', ['active' => 'apps'])
            @include('theme::developer.partials.platform_rules')
        </div>
    </div>

    <div class="col-lg-9">
        @if(session('success'))
            <div class="alert alert-success rounded-4 shadow-sm mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex flex-column gap-4">
            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-1">{{ __('messages.applications') }}</p>
                            <h2 class="h4 fw-bold mb-2">{{ __('messages.my_apps') }}</h2>
                            <p class="text-muted small mb-0">{{ __('messages.dev_platform_settings_desc') }}</p>
                        </div>
                        <a href="{{ route('developer.apps.create') }}" class="btn btn-primary rounded-pill fw-bold px-4">{{ __('messages.create_app') }}</a>
                    </div>

                    <div class="dev-stat-grid mt-4">
                        <div class="dev-stat-card p-3 mb-2">
                            <span>{{ __('messages.total_apps') }}</span>
                            <strong>{{ $developerApps->count() }}</strong>
                        </div>
                        <div class="dev-stat-card p-3 mb-2">
                            <span>{{ __('messages.active_apps') }}</span>
                            <strong>{{ $developerApps->where('status', 'active')->count() }}</strong>
                        </div>
                        <div class="dev-stat-card p-3">
                            <span>{{ __('messages.pending_review') }}</span>
                            <strong>{{ $developerApps->where('status', 'pending_review')->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            @if($developerApps->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 dev-panel">
                    <div class="card-body p-5 text-center">
                        <div class="dev-empty bg-transparent border-0">
                            <i class="fa fa-cubes fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-4">{{ __('messages.no_apps_yet') }}</p>
                            <a href="{{ route('developer.apps.create') }}" class="btn btn-primary rounded-pill fw-bold px-4">{{ __('messages.create_app') }}</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($developerApps as $developerApp)
                        <article class="card border-0 shadow-sm rounded-4 dev-panel dev-app-card">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                    <div>
                                        <a href="{{ route('developer.apps.show', $developerApp->id) }}" class="h5 fw-bold text-body text-decoration-none d-flex align-items-center gap-2 mb-1">
                                            <i class="fa fa-cube text-primary"></i>
                                            {{ $developerApp->name }}
                                        </a>
                                        <div class="text-muted small d-flex align-items-center gap-2 mt-2">
                                            <i class="fa fa-globe"></i>
                                            {{ parse_url($developerApp->domain, PHP_URL_HOST) ?: $developerApp->domain }}
                                        </div>
                                    </div>

                                    @include('theme::developer.partials.status_badge', ['status' => $developerApp->status])
                                </div>

                                <p class="text-muted small mt-3 mb-0">
                                    {{ \Illuminate\Support\Str::limit($developerApp->description, 180) }}
                                </p>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                                        <i class="fa fa-link text-muted me-1"></i>
                                        {{ count($developerApp->redirect_uris ?? []) }} {{ __('messages.redirect_uris') }}
                                    </span>
                                    <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                                        <i class="fa fa-shield-halved text-muted me-1"></i>
                                        {{ count($developerApp->requested_scopes ?? []) }} {{ __('messages.requested_scopes') }}
                                    </span>
                                </div>

                                <div class="mt-4 d-flex align-items-center gap-2 flex-wrap">
                                    <a href="{{ route('developer.apps.show', $developerApp->id) }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4">{{ __('messages.manage') }}</a>
                                    <form action="{{ route('developer.apps.destroy', $developerApp->id) }}" method="POST" onsubmit="return confirm('@lang('messages.confirm_delete_app')')" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-pill fw-bold px-3">
                                            <i class="fa fa-trash me-1"></i> {{ __('messages.delete_app') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
