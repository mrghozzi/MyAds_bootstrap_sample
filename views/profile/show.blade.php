@extends('theme::layouts.master')
@section('content')
<div class="container py-4">
    <!-- Profile Header -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 border border-light transition-all">
        <!-- Cover Image -->
        <div class="profile-cover position-relative" style="height: 350px; background: url({{ asset($cover) }}) no-repeat center; background-size: cover;">
            <div class="position-absolute bottom-0 start-0 w-100 p-4 bg-gradient-dark d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="user-avatar-wrap p-1 bg-white rounded-circle shadow-lg" style="width: 160px; height: 160px; margin-bottom: -80px; position: relative; z-index: 2;">
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}" class="rounded-circle border border-4 border-white w-100 h-100" style="object-fit: cover;">
                        @if($showOnlineStatus)
                            <span class="position-absolute bottom-0 end-0 p-3 bg-{{ $user->isOnline() ? 'success' : 'secondary' }} border border-4 border-white rounded-circle shadow-sm" style="width: 28px; height: 28px; margin-right: 12px; margin-bottom: 12px;" title="{{ $user->isOnline() ? 'Online' : 'Offline' }}"></span>
                        @endif
                    </div>
                    <div class="text-white mb-n2 d-none d-md-block">
                        <h1 class="h2 fw-black mb-1 drop-shadow d-flex align-items-center gap-2">
                            {{ $user->username }} 
                            @if($user->hasVerifiedBadge()) <i class="fa fa-check-circle text-primary smaller"></i> @endif
                        </h1>
                        <p class="mb-0 text-white text-opacity-75 fw-bold smaller drop-shadow">@ {{ $user->username }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4 pt-5 mt-4">
            <div class="row align-items-center pt-2">
                <div class="col-md-7">
                    <div class="d-md-none mb-4">
                        <h2 class="h3 fw-black mb-1 text-dark d-flex align-items-center gap-2">
                            {{ $user->username }} 
                            @if($user->hasVerifiedBadge()) <i class="fa fa-check-circle text-primary smaller"></i> @endif
                        </h2>
                        <p class="text-muted smaller fw-bold mb-0">@ {{ $user->username }}</p>
                    </div>
                    
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        @if(!empty($subscriptionProfileBadge))
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-3 py-2 fw-black smaller shadow-sm">
                                <i class="fa fa-crown me-2 text-warning"></i> {{ $subscriptionProfileBadge['label'] }}
                            </span>
                        @endif
                        <span class="text-muted small fw-bold d-flex align-items-center">
                            @if($showOnlineStatus)
                                <i class="fa fa-clock me-2 opacity-50"></i> {{ __('messages.lastcontact') }} {{ \Carbon\Carbon::createFromTimestamp($user->online)->diffForHumans() }}
                            @endif
                        </span>
                    </div>
                    
                    <!-- Stats Bar -->
                    <div class="d-flex flex-wrap gap-4 py-3 bg-light bg-opacity-50 rounded-4 px-4 border border-light shadow-sm">
                        <div class="text-center">
                            <h5 class="fw-black mb-0 text-dark">
                                @if($canViewFollowers)
                                    <a href="{{ route('profile.followers', $user->username) }}" class="text-dark text-decoration-none hover-primary">{{ number_format($followersCount ?? 0) }}</a>
                                @else
                                    {{ number_format($followersCount ?? 0) }}
                                @endif
                            </h5>
                            <small class="text-muted text-uppercase smallest fw-black letter-spacing-1">{{ __('messages.Followers') }}</small>
                        </div>
                        <div class="text-center border-start border-light-subtle ps-4">
                            <h5 class="fw-black mb-0 text-dark">
                                @if($canViewFollowing)
                                    <a href="{{ route('profile.following', $user->username) }}" class="text-dark text-decoration-none hover-primary">{{ number_format($followingCount ?? 0) }}</a>
                                @else
                                    {{ number_format($followingCount ?? 0) }}
                                @endif
                            </h5>
                            <small class="text-muted text-uppercase smallest fw-black letter-spacing-1">{{ __('messages.following') }}</small>
                        </div>
                        <div class="text-center border-start border-light-subtle ps-4">
                            <h5 class="fw-black mb-0 text-dark">{{ number_format($postsCount ?? 0) }}</h5>
                            <small class="text-muted text-uppercase smallest fw-black letter-spacing-1">{{ __('messages.Posts') }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-5 text-md-end mt-4 mt-md-0">
                    <div class="d-flex gap-3 justify-content-md-end flex-wrap">
                        @if(Auth::check() && Auth::id() == $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill fw-black px-4 py-3 shadow-lg transition-all hover-translate-y">
                                <i class="fa fa-edit me-2"></i> {{ __('messages.edit') }}
                            </a>
                            <a href="{{ route('profile.badges') }}" class="btn btn-outline-dark rounded-pill fw-black px-4 py-3 shadow-sm transition-all hover-bg-dark hover-text-white">
                                <i class="fa fa-trophy me-2"></i> {{ __('messages.badges') }}
                            </a>
                        @else
                            @if($isFollowing)
                                <form action="{{ route('profile.follow', $user->username) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger rounded-pill fw-black px-4 py-3 shadow-lg transition-all hover-translate-y">
                                        <i class="fa fa-user-times me-2"></i> {{ __('messages.unfollow') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('profile.follow', $user->username) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary rounded-pill fw-black px-4 py-3 shadow-lg transition-all hover-translate-y">
                                        <i class="fa fa-user-plus me-2"></i> {{ __('messages.follow') }}
                                    </button>
                                </form>
                            @endif
                            @if($canSendMessage)
                                <a href="{{ route('messages.show', \App\Models\Message::encodeConversationRouteKey(auth()->id(), $user)) }}" class="btn btn-outline-primary rounded-pill fw-black px-4 py-3 shadow-sm transition-all hover-translate-y">
                                    <i class="fa fa-envelope me-2"></i> {{ __('messages.send_message') }}
                                </a>
                            @endif
                            <a href="{{ route('profile.block.create', $user->username) }}" class="btn btn-outline-danger rounded-pill fw-black px-3 py-3 shadow-sm transition-all hover-translate-y" title="{{ __('messages.block') ?? 'Block' }}">
                                <i class="fa fa-ban"></i>
                            </a>
                            <a href="{{ route('report.index', ['user' => $user->username]) }}" class="btn btn-outline-warning rounded-pill fw-black px-3 py-3 shadow-sm transition-all hover-translate-y" title="{{ __('messages.report') ?? 'Report' }}">
                                <i class="fa fa-flag"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Navigation -->
        <div class="card-footer bg-white border-top p-0">
            @include('theme::profile.navigation')
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Left -->
        <div class="col-lg-4">
            @if($canViewAbout)
                <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1 d-flex align-items-center">
                            <i class="fa fa-info-circle text-primary me-2"></i> {{ __('messages.about_me') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0 text-dark opacity-75 fs-6 lh-lg" style="white-space: pre-line;">{{ trim((string) $user->sig) !== '' ? $user->sig : __('messages.about_me_empty') }}</p>
                    </div>
                </div>
            @endif

            @if($badgeShowcase->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1 d-flex align-items-center">
                            <i class="fa fa-trophy text-primary me-2"></i> {{ __('messages.badges') }}
                        </h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill fw-black smallest">{{ $badgeShowcase->count() }}</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach($badgeShowcase as $badgeItem)
                                @php $badge = $badgeItem->badge; @endphp
                                @if($badge)
                                    <div class="col-4">
                                        <div class="text-center p-3 rounded-4 bg-light border border-light transition-all hover-translate-y h-100 d-flex align-items-center justify-content-center flex-column shadow-sm" title="{{ __('messages.' . $badge->name_key) }}">
                                            @if($badge->icon && str_contains($badge->icon, ' '))
                                                <i class="{{ $badge->icon }} text-primary fs-3 mb-1" aria-hidden="true"></i>
                                            @elseif($badge->icon && str_starts_with($badge->icon, 'fa-'))
                                                <i class="fa {{ $badge->icon }} text-primary fs-3 mb-1" aria-hidden="true"></i>
                                            @elseif($badge->icon && str_starts_with($badge->icon, 'svg-'))
                                                <svg class="icon {{ $badge->icon }} text-primary fs-3 mb-1"><use xlink:href="#{{ $badge->icon }}"></use></svg>
                                            @else
                                                <i class="fa fa-trophy text-primary fs-3 mb-1" aria-hidden="true"></i>
                                            @endif
                                            <small class="smallest fw-black text-muted text-truncate w-100 d-block">{{ __('messages.' . $badge->name_key) }}</small>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($socialLinks))
                <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1 d-flex align-items-center">
                            <i class="fa fa-share-nodes text-primary me-2"></i> {{ __('messages.social_links') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-2">
                            @foreach($socialLinks as $platform => $url)
                                @php
                                    $platformData = match($platform) {
                                        'facebook' => ['icon' => 'fab fa-facebook-f', 'color' => '#1877F2'],
                                        'twitter' => ['icon' => 'fab fa-x-twitter', 'color' => '#000000'],
                                        'linkedin' => ['icon' => 'fab fa-linkedin-in', 'color' => '#0A66C2'],
                                        'instagram' => ['icon' => 'fab fa-instagram', 'color' => '#E4405F'],
                                        'youtube' => ['icon' => 'fab fa-youtube', 'color' => '#FF0000'],
                                        'adstn' => ['icon' => 'fa-brands fa-buysellads', 'color' => '#615dfa'],
                                        'tiktok' => ['icon' => 'fab fa-tiktok', 'color' => '#000000'],
                                        'discord' => ['icon' => 'fab fa-discord', 'color' => '#5865F2'],
                                        default => ['icon' => 'fa fa-link', 'color' => '#6c757d'],
                                    };
                                @endphp
                                <a href="{{ $url }}" target="_blank" class="btn btn-light rounded-pill px-3 py-2 text-start border border-light d-flex align-items-center transition-all hover-translate-y shadow-sm bg-opacity-25">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 32px; height: 32px; background-color: {{ $platformData['color'] }}15; color: {{ $platformData['color'] }};">
                                        <i class="{{ $platformData['icon'] }} smallest"></i>
                                    </div>
                                    <span class="smallest fw-black text-dark text-uppercase letter-spacing-1">{{ ucfirst($platform) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <x-widget-column side="profile_left" />
        </div>

        <!-- Main Timeline -->
        <div class="col-lg-8">
            @if(Auth::check() && Auth::id() == $user->id && !in_array($selectedTab, ['photos', 'about'], true))
                @include('theme::partials.status.add_post')
            @endif

            <div id="infinite-scroll-container">
                <div id="timeline-content" class="d-grid gap-4">
                    @if($selectedTab === 'about')
                        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 border border-light">
                            <h4 class="fw-black mb-4 text-dark d-flex align-items-center">
                                <span class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle me-3"><i class="fa fa-info-circle small"></i></span>
                                {{ __('messages.about_me') }}
                            </h4>
                            <p class="mb-0 text-muted lh-lg fs-5" style="white-space: pre-line;">{{ trim((string) $user->sig) !== '' ? $user->sig : __('messages.about_me_empty') }}</p>
                        </div>
                    @elseif($selectedTab === 'photos')
                        <div class="row g-4">
                            @forelse($photoItems as $photo)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 border border-light transition-all hover-translate-y">
                                        <a href="{{ $photo->post_url }}" class="d-block overflow-hidden position-relative">
                                            <img src="{{ $photo->image_url }}" alt="" class="card-img-top object-fit-cover transition-all hover-scale" style="height: 250px;">
                                            <div class="position-absolute top-0 end-0 p-3">
                                                <span class="badge bg-dark bg-opacity-50 rounded-pill fw-black smallest"><i class="fa fa-image"></i></span>
                                            </div>
                                        </a>
                                        <div class="card-body p-3">
                                            <p class="smallest text-muted mb-0 text-truncate fw-black text-uppercase letter-spacing-1">{{ $photo->caption ?: __('messages.photo_post') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-25 border border-light">
                                        <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-4 border border-light">
                                            <i class="fa fa-camera fa-3x text-muted opacity-25"></i>
                                        </div>
                                        <h4 class="fw-black text-muted">{{ __('messages.no_photos_found') }}</h4>
                                        <p class="text-muted small fw-bold mb-0">No photos have been uploaded yet.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @else
                        @forelse($activities as $activity)
                            @include('theme::partials.activity.render', ['activity' => $activity])
                        @empty
                            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-25 border border-light">
                                <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-4 border border-light">
                                    <i class="fa fa-comment-slash fa-3x text-muted opacity-25"></i>
                                </div>
                                <h4 class="fw-black text-muted">{{ __('messages.no_activities') }}</h4>
                                <p class="text-muted small fw-bold mb-0">Activity from this user will appear here.</p>
                            </div>
                        @endforelse
                        @if($activities->hasPages())
                            @include('theme::partials.ajax.infinite_scroll', ['paginator' => $activities])
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
