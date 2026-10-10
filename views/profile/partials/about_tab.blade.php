<div class="bootstrap-about-tab-wrapper d-grid gap-4">
    {{-- 1. PRIVACY RESTRICTIONS CHECK --}}
    @if(!empty($profileContentNotice))
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-25 border border-light">
            <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mx-auto mb-3 border border-light text-danger">
                <i class="fa fa-user-lock fa-3x"></i>
            </div>
            <h4 class="fw-black text-dark mb-2">{{ __('messages.section_private') }}</h4>
            <p class="text-muted small fw-bold mb-4">{{ $profileContentNotice }}</p>
            @if(auth()->check() && !($isFollowing ?? false) && !$isOwnProfile)
                <div>
                    <form action="{{ route('profile.follow', $user->username) }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-user-plus me-1"></i> {{ __('messages.follow') }}
                        </button>
                    </form>
                </div>
            @elseif(!auth()->check())
                <div>
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                        <i class="fa fa-arrow-right-to-bracket me-1"></i> {{ __('messages.login') }}
                    </a>
                </div>
            @endif
        </div>
    @elseif(!$canViewAbout)
        {{-- Section Restricted by About Privacy (Public / Followers / Private) --}}
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-25 border border-light">
            <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mx-auto mb-3 border border-light text-muted">
                <i class="fa fa-lock fa-3x"></i>
            </div>
            <h4 class="fw-black text-dark mb-2">{{ __('messages.section_private') }}</h4>
            <p class="text-muted small fw-bold mb-4">
                @if(($privacySettings->about_visibility ?? '') === 'followers')
                    {{ __('messages.profile_followers_only_notice') ?? 'This section is only available to followers.' }}
                @else
                    {{ __('messages.section_private') }}
                @endif
            </p>
            @if(($privacySettings->about_visibility ?? '') === 'followers' && auth()->check() && !($isFollowing ?? false) && !$isOwnProfile)
                <div>
                    <form action="{{ route('profile.follow', $user->username) }}" method="POST" class="d-inline-block">
                        @csrf
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-user-plus me-1"></i> {{ __('messages.follow') }}
                        </button>
                    </form>
                </div>
            @elseif(!auth()->check())
                <div>
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                        <i class="fa fa-arrow-right-to-bracket me-1"></i> {{ __('messages.login') }}
                    </a>
                </div>
            @endif
        </div>
    @else
        {{-- 2. FULL AUTHORIZED ABOUT TAB DOSSIER --}}

        {{-- Hero Dossier Header --}}
        <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden bg-primary bg-opacity-10">
            <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-black">
                        <i class="fa fa-id-card me-1"></i> {{ __('messages.about_me') }}
                    </span>
                    @if($isOwnProfile && isset($privacySettings))
                        @php $curVisibility = $privacySettings->about_visibility ?? 'public'; @endphp
                        <span class="badge bg-white text-dark border border-light rounded-pill px-3 py-2 fw-bold shadow-sm">
                            @if($curVisibility === 'public')
                                <i class="fa fa-globe text-primary me-1"></i> {{ __('messages.visibility_public') }}
                            @elseif($curVisibility === 'followers')
                                <i class="fa fa-users text-primary me-1"></i> {{ __('messages.visibility_followers') }}
                            @else
                                <i class="fa fa-lock text-muted me-1"></i> {{ __('messages.visibility_private') }}
                            @endif
                        </span>
                    @endif
                </div>
                @if($isOwnProfile)
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-2 fw-black shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-pen me-1"></i> {{ __('messages.edit_profile') }}
                        </a>
                        <a href="{{ route('profile.privacy') }}" class="btn btn-sm btn-white bg-white text-dark border border-light rounded-pill px-3 py-2 fw-bold shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-shield-alt text-primary me-1"></i> {{ __('messages.privacy_settings') }}
                        </a>
                        <a href="{{ route('profile.social') }}" class="btn btn-sm btn-white bg-white text-dark border border-light rounded-pill px-3 py-2 fw-bold shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-share-nodes text-primary me-1"></i> {{ __('messages.social_links') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Main Bio Card --}}
        <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1 d-flex align-items-center">
                    <i class="fa fa-feather text-primary me-2"></i> {{ __('messages.about_me') }}
                </h6>
                @if($isOwnProfile)
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-light rounded-pill px-3 py-1 fw-bold text-primary">
                        <i class="fa fa-pen me-1"></i> {{ __('messages.edit') ?? 'Edit' }}
                    </a>
                @endif
            </div>
            <div class="card-body p-4 p-md-5">
                @if(trim((string) $user->sig) !== '')
                    <p class="mb-0 text-dark opacity-90 fs-5 lh-lg" style="white-space: pre-line;">{{ $user->sig }}</p>
                @else
                    <div class="text-center py-4">
                        <div class="rounded-circle bg-light d-inline-flex p-3 mb-3 text-muted">
                            <i class="fa fa-pen-nib fa-2x"></i>
                        </div>
                        <h6 class="fw-black text-muted mb-2">
                            {{ $isOwnProfile ? (__('messages.about_me_placeholder') ?: 'أخبر المجتمع عنك واهتماماتك') : __('messages.about_me_empty') }}
                        </h6>
                        @if($isOwnProfile)
                            <p class="text-muted smaller mb-3">أضف سيرة ذاتية مميزة ليعرف متابعوك المزيد عنك.</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary rounded-pill px-4 py-2 fw-black shadow-sm">
                                <i class="fa fa-pen me-1"></i> {{ __('messages.edit_profile') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Metrics & Community Grid --}}
        <div class="row g-3">
            {{-- Joined Date --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 border border-light p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa fa-calendar-check"></i>
                    </div>
                    <div>
                        <h5 class="fw-black mb-0 text-dark">{{ $user->created_at ? $user->created_at->format('M Y') : '—' }}</h5>
                        <small class="text-muted fw-bold">{{ __('messages.joined') ?? 'Member Since' }}</small>
                    </div>
                </div>
            </div>

            {{-- Posts Count --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 border border-light p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa fa-newspaper"></i>
                    </div>
                    <div>
                        <h5 class="fw-black mb-0 text-dark">{{ number_format($postsCount) }}</h5>
                        <small class="text-muted fw-bold">{{ __('messages.posts') ?? 'Posts' }}</small>
                    </div>
                </div>
            </div>

            {{-- Followers --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 border border-light p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-purple bg-opacity-10 text-purple d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                        <i class="fa fa-users"></i>
                    </div>
                    <div>
                        <h5 class="fw-black mb-0 text-dark">
                            @if($canViewFollowers)
                                {{ number_format($followersCount) }}
                            @else
                                <i class="fa fa-lock text-muted" title="{{ __('messages.private') ?? 'Private' }}" style="font-size: 14px;"></i>
                            @endif
                        </h5>
                        <small class="text-muted fw-bold">{{ __('messages.Followers') }}</small>
                    </div>
                </div>
            </div>

            {{-- Following --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 border border-light p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <div>
                        <h5 class="fw-black mb-0 text-dark">
                            @if($canViewFollowing)
                                {{ number_format($followingCount) }}
                            @else
                                <i class="fa fa-lock text-muted" title="{{ __('messages.private') ?? 'Private' }}" style="font-size: 14px;"></i>
                            @endif
                        </h5>
                        <small class="text-muted fw-bold">{{ __('messages.following') }}</small>
                    </div>
                </div>
            </div>

            {{-- Profile Views --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 border border-light p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa fa-eye"></i>
                    </div>
                    <div>
                        <h5 class="fw-black mb-0 text-dark">{{ number_format($user->vu ?? 0) }}</h5>
                        <small class="text-muted fw-bold">{{ __('messages.views') ?? 'Profile Views' }}</small>
                    </div>
                </div>
            </div>

            {{-- Points --}}
            <div class="col-sm-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 border border-light p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle p-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa fa-trophy"></i>
                    </div>
                    <div>
                        <h5 class="fw-black mb-0 text-dark">
                            @if($canViewPoints)
                                {{ number_format($user->pts ?? 0) }}
                            @else
                                <i class="fa fa-lock text-muted" title="{{ __('messages.private') ?? 'Private' }}" style="font-size: 14px;"></i>
                            @endif
                        </h5>
                        <small class="text-muted fw-bold">{{ __('messages.pts_history') ?? 'Points' }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details & Social Networks (2 Columns) --}}
        <div class="row g-4">
            {{-- Column 1: Account Credentials & Roles --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1 d-flex align-items-center">
                            <i class="fa fa-id-card-clip text-primary me-2"></i> {{ __('messages.account_details') ?? 'Account Details' }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush">
                            {{-- Username --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted fw-bold small"><i class="fa fa-at text-primary me-2"></i> {{ __('messages.username') }}</span>
                                <span class="fw-black text-dark">{{ $user->username }}</span>
                            </li>

                            {{-- Member ID / Public UID --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted fw-bold small"><i class="fa fa-fingerprint text-primary me-2"></i> {{ __('messages.member_id') ?? 'Public Member UID' }}</span>
                                <code class="bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-3 fw-black">{{ $user->usesPublicMemberIds() && !empty($user->public_uid) ? $user->publicRouteIdentifier() : '#' . $user->id }}</code>
                            </li>

                            {{-- Verification Status --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted fw-bold small"><i class="fa fa-certificate text-primary me-2"></i> {{ __('messages.verification_status') ?? 'Verification' }}</span>
                                <div>
                                    @if($user->hasVerifiedBadge())
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 rounded-pill px-3 py-2 fw-black">
                                            <i class="fa fa-check-circle me-1"></i> {{ __('messages.verified') ?? 'Verified' }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-muted rounded-pill px-3 py-2 fw-bold">
                                            {{ __('messages.not_verified') ?? 'Standard Member' }}
                                        </span>
                                    @endif
                                </div>
                            </li>

                            {{-- Subscription Badge --}}
                            @if(!empty($subscriptionProfileBadge))
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                    <span class="text-muted fw-bold small"><i class="fa fa-crown text-warning me-2"></i> {{ __('messages.subscription_plan') ?? 'Membership' }}</span>
                                    <span class="badge text-white rounded-pill px-3 py-2 fw-black" style="background-color: {{ $subscriptionProfileBadge['color'] ?? '#615dfa' }};">
                                        {{ $subscriptionProfileBadge['label'] }}
                                    </span>
                                </li>
                            @endif

                            {{-- Community Role --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <span class="text-muted fw-bold small"><i class="fa fa-shield-halved text-primary me-2"></i> {{ __('messages.role') ?? 'Role' }}</span>
                                <div>
                                    @if($user->isAdmin())
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 rounded-pill px-3 py-2 fw-black">
                                            {{ __('messages.admin') ?? 'Admin' }}
                                        </span>
                                    @elseif($user->forumRoleLabel() !== __('messages.forum_role_member'))
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-3 py-2 fw-black">
                                            {{ $user->forumRoleLabel() }}
                                        </span>
                                    @else
                                        <span class="text-muted fw-bold small">{{ __('messages.member') ?? 'Member' }}</span>
                                    @endif
                                </div>
                            </li>

                            {{-- Forum Activity --}}
                            @if(!empty($communityStats['topics_count']) || !empty($communityStats['comments_count']))
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-0">
                                    <span class="text-muted fw-bold small"><i class="fa fa-comments text-primary me-2"></i> {{ __('messages.forum_activity') ?? 'Forum Activity' }}</span>
                                    <span class="fw-bold small text-dark">
                                        {{ number_format($communityStats['topics_count'] ?? 0) }} {{ __('messages.topics') ?? 'topics' }},
                                        {{ number_format($communityStats['comments_count'] ?? 0) }} {{ __('messages.replies') ?? 'replies' }}
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Column 2: Connected Social Networks --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1 d-flex align-items-center">
                            <i class="fa fa-share-nodes text-primary me-2"></i> {{ __('messages.social_links') }}
                        </h6>
                        @if($isOwnProfile)
                            <a href="{{ route('profile.social') }}" class="btn btn-sm btn-light rounded-pill px-3 py-1 fw-bold text-primary">
                                <i class="fa fa-gear me-1"></i> {{ __('messages.manage') ?? 'Manage' }}
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        @if(!empty($socialLinks))
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
                                            'github' => ['icon' => 'fab fa-github', 'color' => '#24292e'],
                                            default => ['icon' => 'fa fa-link', 'color' => '#6c757d'],
                                        };
                                    @endphp
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="btn btn-light rounded-pill px-3 py-2 text-start border border-light d-flex align-items-center justify-content-between transition-all hover-translate-y shadow-sm bg-opacity-25">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 32px; height: 32px; background-color: {{ $platformData['color'] }}15; color: {{ $platformData['color'] }};">
                                                <i class="{{ $platformData['icon'] }} smallest"></i>
                                            </div>
                                            <span class="smallest fw-black text-dark text-uppercase letter-spacing-1">{{ ucfirst($platform) }}</span>
                                        </div>
                                        <i class="fa fa-arrow-up-right-from-square text-muted smallest opacity-50"></i>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="rounded-circle bg-light d-inline-flex p-3 mb-3 text-muted">
                                    <i class="fa fa-share-nodes fa-2x"></i>
                                </div>
                                <p class="text-muted smaller mb-3">
                                    {{ $isOwnProfile ? 'لم تقم بربط أي من حساباتك الاجتماعية بعد.' : 'لم يقم هذا العضو بربط حسابات اجتماعية.' }}
                                </p>
                                @if($isOwnProfile)
                                    <a href="{{ route('profile.social') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 py-2 fw-bold">
                                        <i class="fa fa-plus me-1"></i> {{ __('messages.social_links') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Badges Showcase --}}
        @if($badgeShowcase->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1 d-flex align-items-center">
                        <i class="fa fa-trophy text-warning me-2"></i> {{ __('messages.badges') }}
                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill fw-black smallest">{{ $badgeShowcase->count() }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @foreach($badgeShowcase as $badgeItem)
                            @php $badge = $badgeItem->badge; @endphp
                            @if($badge)
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                    <div class="text-center p-3 rounded-4 bg-light border border-light transition-all hover-translate-y h-100 d-flex align-items-center justify-content-center flex-column shadow-sm" title="{{ __('messages.' . $badge->name_key) }}">
                                        @if($badge->icon && str_contains($badge->icon, ' '))
                                            <i class="{{ $badge->icon }} text-primary fs-3 mb-2" aria-hidden="true"></i>
                                        @elseif($badge->icon && str_starts_with($badge->icon, 'fa-'))
                                            <i class="fa {{ $badge->icon }} text-primary fs-3 mb-2" aria-hidden="true"></i>
                                        @elseif($badge->icon && str_starts_with($badge->icon, 'svg-'))
                                            <svg class="icon {{ $badge->icon }} text-primary fs-3 mb-2"><use xlink:href="#{{ $badge->icon }}"></use></svg>
                                        @else
                                            <i class="fa fa-trophy text-primary fs-3 mb-2" aria-hidden="true"></i>
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
    @endif
</div>
