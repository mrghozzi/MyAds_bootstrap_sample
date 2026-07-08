@php
    $status = $activity;
    $statusUser = $status->user;
    $statusUserProfileUrl = $statusUser ? route('profile.show', $statusUser->username) : '#';
    $statusUserName = $statusUser?->username ?? __('messages.unknown_user');
    $statusUserAvatar = $statusUser ? $statusUser->avatarUrl() : asset('upload/_avatar.png');
    $statusUserPresence = $statusUser?->isOnline() ? 'online' : 'offline';
    $statusUserHasVerifiedBadge = $statusUser?->hasVerifiedBadge() ?? false;
    $site = $activity->related_content;
@endphp

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $status->id }} activity-post-card">
    <!-- Header -->
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ $statusUserProfileUrl }}" class="me-3 position-relative">
                <img src="{{ $statusUserAvatar }}" class="rounded-circle border border-2 border-white shadow-sm" width="48" height="48" alt="{{ $statusUserName }}">
                @if($statusUserPresence == 'online')
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                @endif
            </a>
            <div>
                <h6 class="fw-bold mb-0">
                    <a href="{{ $statusUserProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $statusUserName }}</a>
                    @if($statusUserHasVerifiedBadge)
                        <svg class="verified-icon ms-1" viewBox="0 0 24 24" width="14" height="14" fill="#23d2e2" style="vertical-align: middle;"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1.9 14.7L6 12.6l1.5-1.5 2.6 2.6 6.4-6.4 1.5 1.5-7.9 7.9z"/></svg>
                    @endif
                    <small class="text-muted fw-normal ms-1">{{ __('messages.added_new_site') }}</small>
                </h6>
                <small class="text-muted smaller fw-bold"><i class="fa-regular fa-clock me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($status->date)->diffForHumans() }}</small>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-4">
        @php
            $siteExcerpt = \Illuminate\Support\Str::limit($site->txt ?? '', 180);
            $siteBanner = $site->prominent_image ?: theme_asset('img/dir_image.png');
        @endphp
        @once
            @include('theme::partials.directory.lazy_image_script')
        @endonce
        
        <div class="card border border-light-subtle rounded-4 overflow-hidden shadow-sm hover-translate-y">
            <div class="row g-0">
                <div class="col-md-4 position-relative" style="min-height: 180px; background: url('{{ $siteBanner }}') center center / cover no-repeat;" data-lazy-fetch-url="{{ route('directory.image.fetch', $site->id) }}">
                    @if($site->category)
                        <span class="position-absolute top-0 start-0 m-3 badge bg-primary py-2 px-3 rounded-pill fw-bold">
                            <i class="fa {{ $site->category->icon ?? 'fa-globe' }} me-1"></i>
                            {{ $site->category->name }}
                        </span>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title fw-black mb-2">
                                <a href="{{ $site->url }}" target="_blank" class="text-dark text-decoration-none hover-primary">{{ $site->name }}</a>
                            </h5>
                            <p class="card-text text-secondary small line-clamp-3">{{ $siteExcerpt }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-light">
                            <span class="text-muted smaller fw-bold"><i class="fa-regular fa-eye me-1"></i> {{ $site->vu }}</span>
                            <a href="{{ $site->url }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-black">
                                {{ __('messages.visit_site') }} <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
