<div class="px-4">
    <ul class="nav nav-pills border-0 flex-nowrap text-nowrap profile-nav-pills py-3 gap-2">
        <li class="nav-item">
            <a class="nav-link rounded-pill px-4 py-2 smaller fw-black transition-all {{ ($selectedTab ?? request('tab', 'timeline')) === 'timeline' ? 'bg-primary text-white shadow-sm' : 'text-muted hover-bg-light' }}" href="{{ route('profile.show', $user->username) }}">
                <i class="fa fa-stream me-2"></i> {{ __('messages.Timeline') }}
            </a>
        </li>
        @if(($canViewAbout ?? true) || ($selectedTab ?? request('tab')) === 'about')
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 py-2 smaller fw-black transition-all {{ ($selectedTab ?? request('tab')) == 'about' ? 'bg-primary text-white shadow-sm' : 'text-muted hover-bg-light' }}" href="{{ route('profile.show', $user->username) }}?tab=about">
                    <i class="fa fa-user me-2"></i> {{ __('messages.about_me') }}
                </a>
            </li>
        @endif
        @if((($canViewPhotos ?? true) && ($canViewProfileContent ?? true)) || ($selectedTab ?? request('tab')) === 'photos')
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 py-2 smaller fw-black transition-all {{ ($selectedTab ?? request('tab')) == 'photos' ? 'bg-primary text-white shadow-sm' : 'text-muted hover-bg-light' }}" href="{{ route('profile.show', $user->username) }}?tab=photos">
                    <i class="fa fa-camera me-2"></i> {{ __('messages.Photos') }}
                </a>
            </li>
        @endif
        @if(($canViewFollowers ?? true) || request()->routeIs('profile.followers'))
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 py-2 smaller fw-black transition-all {{ request()->routeIs('profile.followers') ? 'bg-primary text-white shadow-sm' : 'text-muted hover-bg-light' }}" href="{{ route('profile.followers', $user->username) }}">
                    <i class="fa fa-users me-2"></i> {{ __('messages.Followers') }}
                </a>
            </li>
        @endif
        @if(($canViewFollowing ?? true) || request()->routeIs('profile.following'))
            <li class="nav-item">
                <a class="nav-link rounded-pill px-4 py-2 smaller fw-black transition-all {{ request()->routeIs('profile.following') ? 'bg-primary text-white shadow-sm' : 'text-muted hover-bg-light' }}" href="{{ route('profile.following', $user->username) }}">
                    <i class="fa fa-user-plus me-2"></i> {{ __('messages.following') }}
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link rounded-pill px-4 py-2 smaller fw-black transition-all {{ request('tab') == 'forum' ? 'bg-primary text-white shadow-sm' : 'text-muted hover-bg-light' }}" href="{{ route('profile.show', $user->username) }}?tab=forum">
                <i class="fa fa-comments me-2"></i> {{ __('messages.forum') }}
            </a>
        </li>
    </ul>
</div>

<style>
    .profile-nav-pills { overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none; }
    .profile-nav-pills::-webkit-scrollbar { display: none; }
    .hover-bg-light:hover { background-color: #f8f9fa; color: var(--bs-primary) !important; }
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .transition-all { transition: all 0.3s ease; }</style>
