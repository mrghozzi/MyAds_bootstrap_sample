@extends('theme::layouts.master')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-shield-halved fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.active_sessions') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.security_member_sessions_desc') ?? 'Monitor and manage your active login sessions across all your devices.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-user-shield fa-10x"></i>
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
            @if($upgradeNotice)
                <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-4" role="alert">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fa fa-info-circle text-warning fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-1">{{ __('messages.notice') ?? 'Notice' }}</div>
                        <div class="small text-muted fw-bold">{!! $upgradeNotice !!}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.manage_sessions') }}</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="d-grid gap-4">
                        @forelse($sessions as $session)
                            @php
                                $icon = 'fa-laptop';
                                if($session->device_type === 'mobile') $icon = 'fa-mobile-screen-button';
                                if($session->device_type === 'tablet') $icon = 'fa-tablet-screen-button';
                                
                                $isRevoked = $session->revoked_at !== null;
                                $isEnded = $session->ended_at !== null && !$isRevoked;
                                $isActive = !$isRevoked && !$isEnded;
                            @endphp
                            
                            <div class="card border-0 shadow-sm rounded-4 transition-all hover-translate-y border border-light overflow-hidden {{ $session->is_current ? 'bg-primary bg-opacity-5' : 'bg-light bg-opacity-25' }}">
                                <div class="card-body p-4">
                                    <div class="row align-items-center g-4">
                                        <div class="col-auto">
                                            <div class="bg-{{ $session->is_current ? 'primary' : 'white' }} bg-opacity-10 text-{{ $session->is_current ? 'primary' : 'muted' }} rounded-4 d-flex align-items-center justify-content-center shadow-sm border border-{{ $session->is_current ? 'primary' : 'light' }}" style="width: 72px; height: 72px;">
                                                <i class="fa-solid {{ $icon }} fs-2"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
                                                <h5 class="fw-black mb-0 text-dark fs-5">{{ $session->browser }} {{ __('messages.on') }} {{ __('messages.' . $session->device_type) }}</h5>
                                                
                                                @if($session->is_current)
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 text-uppercase border border-success border-opacity-10">{{ __('messages.current_session') }}</span>
                                                @elseif($isActive)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 text-uppercase border border-primary border-opacity-10">{{ __('messages.active') }}</span>
                                                @elseif($isRevoked)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 text-uppercase border border-danger border-opacity-10">{{ __('messages.revoked') ?? 'Revoked' }}</span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 text-uppercase border border-secondary border-opacity-10">{{ __('messages.ended') ?? 'Ended' }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="d-flex flex-wrap gap-4 text-muted smaller fw-black letter-spacing-1 text-uppercase">
                                                <span title="{{ __('messages.ip_address') }}" class="d-flex align-items-center">
                                                    <i class="fa-solid fa-network-wired me-2 text-primary opacity-50"></i> {{ $session->ip_address }}
                                                </span>
                                                <span title="{{ __('messages.last_activity') }}" class="d-flex align-items-center">
                                                    <i class="fa-solid fa-clock-rotate-left me-2 text-primary opacity-50"></i> {{ $session->last_seen_at->diffForHumans() }}
                                                </span>
                                                <span title="{{ __('messages.started_at') }}" class="d-flex align-items-center">
                                                    <i class="fa-solid fa-calendar-day me-2 text-primary opacity-50"></i> {{ $session->started_at->format('Y-m-d H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($isActive || $session->is_current)
                                            <div class="col-md-auto text-md-end">
                                                <form action="{{ route('profile.sessions.revoke', $session->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_revoke_session') }}');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-{{ $session->is_current ? 'outline-danger' : 'danger' }} rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                                                        <i class="fa-solid fa-right-from-bracket me-2"></i>
                                                        {{ $session->is_current ? __('messages.logout') : __('messages.revoke_session') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($session->is_current)
                                    <div class="bg-primary bg-opacity-10 py-1 px-4 text-center">
                                        <span class="smallest fw-black text-primary text-uppercase letter-spacing-1">{{ __('messages.security_recommended_action') ?? 'Your active device' }}</span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="p-5 text-center bg-light bg-opacity-25 rounded-4 border border-light">
                                <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-4 border border-light">
                                    <i class="fa-solid fa-user-lock fa-3x text-muted opacity-25"></i>
                                </div>
                                <h4 class="fw-black text-dark">{{ __('messages.no_active_sessions') }}</h4>
                                <p class="text-muted small mb-0 fw-bold">{{ __('messages.security_sessions_empty_desc') ?? 'Your login history will appear here.' }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
