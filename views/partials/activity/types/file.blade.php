@php
    $activityUser = $activity->user;
    $activityUserProfileUrl = $activityUser ? route('profile.show', $activityUser->username) : '#';
    $activityUserName = $activityUser?->username ?? __('messages.unknown_user');
    $activityUserAvatar = $activityUser ? $activityUser->avatarUrl() : asset('upload/_avatar.png');
    $activityUserPresence = $activityUser?->isOnline() ? 'online' : 'offline';
    $formattedText = \App\Support\ContentFormatter::format($activity->related_content->txt ?? '');
    $repostExcerpt = \Illuminate\Support\Str::limit(strip_tags($activity->related_content->txt ?? ''), 80);
    $repostAuthorName = addslashes($activityUserName);
    $attachments = $activity->related_content->attachments;
@endphp

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $activity->id }} activity-post-card">
    <!-- Header -->
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
                    <small class="text-muted fw-normal ms-1">{{ __('messages.added_files') ?? 'shared files' }}</small>
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

    <!-- Body -->
    <div class="card-body p-4">
        @include('theme::partials.activity.promotion_badge', ['activity' => $activity])
        
        <div class="post-content post_text{{ $activity->related_content->id }}">
            <div class="textpost mb-3" id="post_form{{ $activity->related_content->id }}">
                {!! $formattedText !!}
            </div>

            @if($attachments->count() > 0)
                <div class="list-group list-group-flush border rounded-3 overflow-hidden mb-3">
                    @foreach($attachments as $file)
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-light bg-opacity-25 py-3">
                            <div class="d-flex align-items-center gap-3 min-width-0">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-file-lines fa-lg"></i>
                                </div>
                                <div class="min-width-0">
                                    <p class="mb-0 fw-bold text-dark text-truncate small" title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                                    <span class="text-muted smaller fw-bold">{{ round($file->file_size / 1024, 1) }} KB</span>
                                </div>
                            </div>
                            <a href="{{ asset($file->file_path) }}" download class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                                <i class="fa fa-download me-1"></i> {{ __('messages.download') ?? 'Download' }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="card-footer bg-white border-top py-3">
        @include('theme::partials.activity.post_footer_shared', ['activity' => $activity, 'repostAuthorName' => $repostAuthorName, 'repostExcerpt' => $repostExcerpt])
    </div>
</div>
