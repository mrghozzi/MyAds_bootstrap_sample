@php
    $activityUser = $activity->user;
    $activityUserProfileUrl = $activityUser ? route('profile.show', $activityUser->username) : '#';
    $activityUserName = $activityUser?->username ?? __('messages.unknown_user');
    $activityUserAvatar = $activityUser ? $activityUser->avatarUrl() : asset('upload/_avatar.png');
    $activityUserPresence = $activityUser?->isOnline() ? 'online' : 'offline';
    $activityUserIsAdmin = $activityUser?->isAdmin() ?? false;
    $activityUserHasVerifiedBadge = $activityUser?->hasVerifiedBadge() ?? false;
    $formattedText = \App\Support\ContentFormatter::format($activity->related_content->txt ?? '');
    $repostExcerpt = \Illuminate\Support\Str::limit(
        strip_tags(
            $activity->related_content->txt
            ?? $activity->related_content->o_valuer
            ?? $activity->related_content->text
            ?? $activity->related_content->name
            ?? ''
        ),
        80
    );
    $repostAuthorName = addslashes($activityUserName);
@endphp

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $activity->id }} activity-post-card">
    <!-- Header -->
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ $activityUserProfileUrl }}" class="me-3 position-relative">
                <img src="{{ $activityUserAvatar }}" class="rounded-circle border border-2 border-white shadow-sm" width="48" height="48" alt="{{ $activityUserName }}">
                @if($activityUserPresence == 'online')
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                @endif
            </a>
            <div>
                <h6 class="fw-bold mb-0">
                    <a href="{{ $activityUserProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $activityUserName }}</a>
                    @if($activity->is_pinned)
                        <span class="badge ms-2" style="background-color: var(--bs-primary); color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;"><i class="fa fa-thumb-tack"></i> {{ __('messages.pinned_post') ?? 'Pinned Post' }}</span>
                    @endif
                    @if($activityUserHasVerifiedBadge)
                        <svg class="verified-icon ms-1" viewBox="0 0 24 24" width="14" height="14" fill="#23d2e2" style="vertical-align: middle;"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1.9 14.7L6 12.6l1.5-1.5 2.6 2.6 6.4-6.4 1.5 1.5-7.9 7.9z"/></svg>
                    @endif
                    <small class="text-muted fw-normal ms-1">{{ __('messages.added_photo') }}</small>
                </h6>
                <small class="text-muted smaller fw-bold"><i class="fa-regular fa-clock me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($activity->date)->diffForHumans() }}</small>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                @auth
                                        @if(auth()->id() == $activity->uid)
                        <li>
                            <button class="dropdown-item py-2" onclick="togglePinPost({{ $activity->id }}, {{ $activity->is_pinned ? 'true' : 'false' }}, {{ isset($hasPinnedPost) && $hasPinnedPost ? 'true' : 'false' }})">
                                <i class="fa fa-thumb-tack me-2"></i> {{ $activity->is_pinned ? __('messages.unpin_post') : __('messages.pin_post') }}
                            </button>
                        </li>
                    @endif
@if(auth()->id() == $activity->uid || auth()->user()->isAdmin())
                        <li><button class="dropdown-item py-2" onclick="postEdit({{ $activity->tp_id }}, 4)"><i class="fa-regular fa-pen-to-square me-2"></i> {{ __('messages.edit') }}</button></li>
                        <li><button class="dropdown-item py-2 text-danger" onclick="deletePost({{ $activity->tp_id }}, 4, '.post{{ $activity->id }}')"><i class="fa-regular fa-trash-can me-2"></i> {{ __('messages.delete') }}</button></li>
                    @endif
                    @include('theme::partials.activity.promotion_link', ['activity' => $activity])
                    <li><button class="dropdown-item py-2" onclick="reportPost({{ $activity->tp_id }}, 4, {{ $activity->related_content->id }})"><i class="fa-regular fa-flag me-2"></i> {{ __('messages.report') }}</button></li>
                    <li><button class="dropdown-item py-2" onclick="reportUser({{ $activity->uid }}, {{ $activity->related_content->id }})"><i class="fa-regular fa-user me-2"></i> {{ __('messages.report_author') }}</button></li>
                @endauth
                <li><button class="dropdown-item py-2" onclick="navigator.clipboard.writeText('{{ route('forum.topic', $activity->tp_id) }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa-regular fa-copy me-2"></i> {{ __('messages.copy_link') }}</button></li>
            </ul>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-4">
        @include('theme::partials.activity.promotion_badge', ['activity' => $activity])

        <div class="post-content post_text{{ $activity->related_content->id }}">
            <div class="textpost mb-3 lh-lg" id="post_form{{ $activity->related_content->id }}">
                @if(trim(strip_tags($formattedText)) !== '')
                    {!! $formattedText !!}
                @endif
                <div id="report{{ $activity->related_content->id }}"></div>
            </div>
        </div>

        @include('theme::partials.activity.gallery', ['activity' => $activity])

        @if($activity->linkPreviewRecord)
            @include('theme::partials.activity.link_preview', ['activity' => $activity])
        @endif

        <div id="notif{{ $activity->related_content->id }}"></div>
    </div>

    <!-- Footer -->
    <div class="card-footer bg-white border-top py-3">
        @include('theme::partials.activity.post_footer_shared', ['activity' => $activity, 'repostAuthorName' => $repostAuthorName, 'repostExcerpt' => $repostExcerpt])
    </div>
</div>
