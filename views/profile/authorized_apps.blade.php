@extends('theme::layouts.master')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-5 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-cubes fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.authorized_apps') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.authorized_apps_desc') }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-plug fa-10x"></i>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            @include('theme::profile.settings_nav')
            <x-widget-column side="portal_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden mb-4">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.manage_authorizations') ?? 'Manage Authorizations' }}</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @include('theme::billing.partials.alerts')

                    <div class="d-grid gap-4">
                        @forelse($authorizations as $auth)
                            <div class="card border-0 shadow-sm rounded-4 transition-all hover-translate-y bg-light bg-opacity-25 border border-light overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="row align-items-center g-4">
                                        <div class="col-auto">
                                            @if($auth->app->logo)
                                                <img src="{{ asset($auth->app->logo) }}" alt="{{ $auth->app->name }}" class="rounded-4 border border-white shadow-sm" width="80" height="80" style="object-fit: cover;">
                                            @else
                                                <div class="bg-white text-muted rounded-4 d-flex align-items-center justify-content-center shadow-sm border border-light" style="width: 80px; height: 80px;">
                                                    <i class="fa-solid fa-cube fs-2 opacity-25"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                                                <h5 class="fw-black mb-0 text-dark fs-5">{{ $auth->app->name }}</h5>
                                                @if($auth->app->domain)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 text-uppercase border border-primary border-opacity-10">
                                                        <i class="fa fa-globe me-2"></i> {{ $auth->app->domain }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="d-flex flex-wrap gap-4 text-muted smaller fw-black letter-spacing-1 text-uppercase">
                                                <span class="d-flex align-items-center">
                                                    <i class="fa-solid fa-calendar-check me-2 text-primary opacity-50"></i> @lang('messages.authorized_on') {{ $auth->created_at->format('M d, Y') }}
                                                </span>
                                                @if($auth->app->client_id)
                                                    <span class="d-flex align-items-center">
                                                        <i class="fa-solid fa-fingerprint me-2 text-primary opacity-50"></i> {{ substr($auth->app->client_id, 0, 8) }}...
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-auto text-md-end">
                                            <form action="{{ route('profile.apps.revoke', $auth->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.revoke_app_confirm') }}')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                                                    <i class="fa-solid fa-ban me-2"></i> {{ __('messages.revoke_access') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center bg-light bg-opacity-25 rounded-4 border border-light">
                                <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-4 border border-light">
                                    <i class="fa-solid fa-shield-halved fa-3x text-muted opacity-25"></i>
                                </div>
                                <h4 class="fw-black text-dark">{{ __('messages.no_authorized_apps') }}</h4>
                                <p class="text-muted small mb-0 fw-bold">Apps you authorize via OAuth will appear here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
