@extends('theme::layouts.master')

@section('content')
<!-- SECTION BANNER -->
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%;" >
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}"  alt="overview-icon">
    <p class="section-banner-title">{{ __('messages.list') }} {{ __('messages.referal') }}</p>
    <p class="section-banner-text">{{ __('messages.ryffyrly') }}</p>
</div>

<div class="row">
    <!-- LEFT SIDEBAR -->
    <div class="col-lg-3 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">
                <h4 class="mb-0 fw-bold">{{ __('messages.menu') }}</h4>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary fw-bold rounded-pill"><i class="fa fa-home me-2" aria-hidden="true"></i> {{ __('messages.dashboard') ?? 'Dashboard' }}</a>
                    <a href="{{ route('ads.referrals') }}" class="btn btn-success fw-bold rounded-pill"><i class="fa fa-code me-2" aria-hidden="true"></i> {{ __('messages.codes') }} {{ __('messages.referal') }}</a>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="col-lg-9">
        
        <!-- REFERRALS -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">
                <h4 class="mb-0 fw-bold">{{ __('messages.list') }} {{ __('messages.referal') }}</h4>
            </div>
            <div class="card-body p-4">
                @if($referrals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#ID</th>
                                <th>{{ __('messages.username') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.pts') }}</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach($referrals as $ref)
                            @php
                                $user = $ref->referredUser;
                            @endphp
                            @if($user)
                            <tr>
                                <td class="fw-bold text-muted">{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a class="me-3 position-relative" href="{{ route('profile.show', $user->username) }}">
                                            <div class="user-avatar small no-outline {{ $user->online > time() - 240 ? 'online' : 'offline' }}">
                                                <div class="user-avatar-content">
                                                    <div class="hexagon-image-30-32" data-src="{{ $user->avatar ? asset($user->avatar) : theme_asset('img/avatar.png') }}" style="width: 30px; height: 32px; position: relative;"></div>
                                                </div>
                                                <div class="user-avatar-progress-border">
                                                    <div class="hexagon-border-40-44" data-line-color="{{ $user->profileBadgeColor() }}" style="width: 40px; height: 44px; position: relative;"></div>
                                                </div>
                                                @if($user->hasVerifiedBadge())
                                                <div class="user-avatar-badge">
                                                    <div class="user-avatar-badge-border">
                                                        <div class="hexagon-22-24" style="width: 22px; height: 24px; position: relative;"></div>
                                                    </div>
                                                    <div class="user-avatar-badge-content">
                                                        <div class="hexagon-dark-16-18" style="width: 16px; height: 18px; position: relative;"></div>
                                                    </div>
                                                    <p class="user-avatar-badge-text"><i class="fa fa-fw fa-check" ></i></p>
                                                </div>
                                                @endif
                                            </div>
                                        </a>
                                        <div>
                                            <a class="fw-bold text-dark text-decoration-none d-block" href="{{ route('profile.show', $user->username) }}">{{ $user->username }}</a>
                                            <span class="small text-muted">{{ '@'.$user->username }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ date('d/m/Y', is_numeric($ref->date) ? $ref->date : strtotime($ref->date)) }}</td>
                                <td class="fw-bold text-primary">{{ $user->pts }}</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">{{ __('messages.unknown') }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $referrals->links('pagination::bootstrap-5') }}
                </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-users fa-3x mb-3 text-light"></i>
                        <p class="mb-0">{{ __('messages.no_user') }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
