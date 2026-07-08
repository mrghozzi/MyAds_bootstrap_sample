@php
    $activityUser = $activity->user;
    $activityUserProfileUrl = $activityUser ? route('profile.show', $activityUser->username) : '#';
    $activityUserName = $activityUser?->username ?? __('messages.unknown_user');
    $activityUserAvatar = $activityUser ? $activityUser->avatarUrl() : asset('upload/_avatar.png');
    $activityUserPresence = $activityUser?->isOnline() ? 'online' : 'offline';
@endphp

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $activity->id }} activity-post-card">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ $activityUserProfileUrl }}" class="me-3 position-relative">
                <img src="{{ $activityUserAvatar }}" class="rounded-circle border border-2 border-white shadow-sm" width="48" height="48" alt="{{ $activityUserName }}">
                <span class="position-absolute bottom-0 end-0 p-1 bg-{{ $activityUserPresence == 'online' ? 'success' : 'secondary' }} border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
            </a>
            <div>
                <h6 class="fw-bold mb-0">
                    <a href="{{ $activityUserProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $activityUserName }}</a>
                    @if($activity->is_pinned)
                        <span class="badge ms-2" style="background-color: var(--bs-primary); color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;"><i class="fa fa-thumb-tack"></i> {{ __('messages.pinned_post') ?? 'Pinned Post' }}</span>
                    @endif
                    @if($activity->s_type == 1) <small class="text-muted fw-normal ms-1">{{ __('messages.added_website') }}</small>
                    @elseif(in_array($activity->s_type, [2, 4])) <small class="text-muted fw-normal ms-1">{{ __('messages.posted_topic') }}</small>
                    @elseif($activity->s_type == 7867) <small class="text-muted fw-normal ms-1">{{ __('messages.added_product') }}</small>
                    @endif
                </h6>
                <small class="text-muted smaller fw-bold"><i class="fa fa-clock me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($activity->date)->diffForHumans() }}</small>
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
                        <li><button class="dropdown-item py-2 text-danger" onclick="deletePost({{ $activity->tp_id }}, {{ $activity->s_type }}, '.post{{ $activity->id }}')"><i class="fa fa-trash me-2"></i> {{ __('messages.delete') }}</button></li>
                    @endif
                    <li><button class="dropdown-item py-2" onclick="reportPost({{ $activity->tp_id }}, {{ $activity->s_type }})"><i class="fa fa-flag me-2"></i> {{ __('messages.report') }}</button></li>
                @endauth
                <li><button class="dropdown-item py-2" onclick="navigator.clipboard.writeText('{{ $activity->detail_url ?? url()->current() }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa fa-link me-2"></i> {{ __('messages.copy_link') }}</button></li>
            </ul>
        </div>
    </div>

    <div class="card-body p-4">
        @if($activity->s_type == 1 && $activity->related_content)
            <div class="post-content">
                <p class="mb-3 lh-lg">{{ Str::limit($activity->related_content->txt, 500) }}</p>
                <div class="bg-light p-3 rounded-4 d-flex justify-content-between align-items-center">
                    <span class="text-truncate me-3 fw-bold small text-muted"><i class="fa fa-globe me-2 text-primary"></i> {{ parse_url($activity->related_content->url, PHP_URL_HOST) }}</span>
                    <a href="{{ $activity->related_content->url }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">{{ __('messages.visit') }}</a>
                </div>
            </div>
        @elseif(in_array($activity->s_type, [2, 4]) && $activity->related_content)
            <div class="post-content">
                <h4 class="fw-bold mb-2"><a href="{{ route('forum.topic', $activity->tp_id) }}" class="text-dark text-decoration-none">{{ $activity->related_content->name }}</a></h4>
                <p class="text-muted lh-lg mb-3">{{ Str::limit(strip_tags($activity->related_content->txt), 300) }}</p>
                <a href="{{ route('forum.topic', $activity->tp_id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">{{ __('messages.read_more') }}</a>
            </div>
        @elseif($activity->s_type == 7867 && $activity->related_content)
            <div class="post-content">
                <div class="d-flex gap-3 align-items-center mb-3">
                    @if($activity->related_content->product_image)
                        <img src="{{ asset($activity->related_content->product_image) }}" class="rounded-3 shadow-sm border" width="80" height="80" style="object-fit: cover;">
                    @endif
                    <div>
                        <h4 class="fw-bold mb-1"><a href="{{ route('store.show', $activity->related_content->name) }}" class="text-dark text-decoration-none">{{ $activity->related_content->name }}</a></h4>
                        <span class="badge bg-primary rounded-pill px-3">{{ $activity->related_content->o_order > 0 ? $activity->related_content->o_order . ' PTS' : __('messages.free') }}</span>
                    </div>
                </div>
                <p class="text-muted lh-lg mb-3">{{ Str::limit(strip_tags($activity->related_content->o_valuer), 300) }}</p>
                <a href="{{ route('store.show', $activity->related_content->name) }}" class="btn btn-secondary btn-sm rounded-pill px-4 fw-bold shadow-sm">{{ __('messages.view_product') }}</a>
            </div>
        @else
            <div class="post-content p-4 text-center text-muted italic">
                {{ __('messages.no_content_preview') }}
            </div>
        @endif
    </div>

    <div class="card-footer bg-white py-3 border-top d-flex gap-4">
        <div class="d-flex align-items-center text-muted small fw-bold">
            <i class="fa fa-thumbs-up me-1 text-primary opacity-50"></i> {{ $activity->likes_count ?? 0 }}
        </div>
        <div class="d-flex align-items-center text-muted small fw-bold">
            <i class="fa fa-comment me-1 text-primary opacity-50"></i> {{ $activity->comments_count ?? 0 }}
        </div>
        <div class="d-flex align-items-center text-muted small fw-bold">
            <i class="fa fa-share-alt me-1 text-primary opacity-50"></i> {{ $activity->reposts_count ?? 0 }}
        </div>
    </div>
</div>
