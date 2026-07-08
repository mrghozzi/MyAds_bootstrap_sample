@php
    $viewer = auth()->user();
    $topicCategoryId = (int) $topic->cat;
    $group = $topic->group;
    $groupAccess = app(\App\Services\GroupAccessService::class);
    $canManageGroupTopic = $group && auth()->check() ? $groupAccess->canManageGroup($group, auth()->user()) : false;
    $canEditTopic = auth()->check() && (
        (int) auth()->id() === (int) $topic->uid
        || $canManageGroupTopic
        || $viewer->canModerateForum('edit_topics', $topicCategoryId)
    );
    $canDeleteTopic = auth()->check() && (
        (int) auth()->id() === (int) $topic->uid
        || $canManageGroupTopic
        || $viewer->canModerateForum('delete_topics', $topicCategoryId)
    );
    $canPinTopic = auth()->check() && ($canManageGroupTopic || $viewer->canModerateForum('pin_topics', $topicCategoryId));
    $canLockTopic = auth()->check() && ($canManageGroupTopic || $viewer->canModerateForum('lock_topics', $topicCategoryId));
    $showForumRoleBadges = (int) \App\Support\ForumSettings::get('show_role_badges', 1) === 1;

    $reactionsCount = \App\Models\Like::where('sid', $topic->id)->where('type', 2)->count();
    $commentsCount = \App\Models\ForumComment::where('tid', $topic->id)->count();
    $topicExcerpt = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $topic->txt))), 160);
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-3 transition-up post{{ $status->id }}">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex align-items-center">
                @if($topic->user)
                    <img src="{{ $topic->user->avatarUrl() }}" alt="{{ $topic->user->username }}" class="rounded-circle me-3" width="48" height="48">
                    <div>
                        <h6 class="fw-bold mb-0">
                            <a href="{{ route('profile.short', $topic->user->publicRouteIdentifier()) }}" class="text-dark text-decoration-none">
                                {{ $topic->user->username }}
                            </a>
                            @if($showForumRoleBadges)
                                <span class="badge bg-light text-primary border ms-1 small fw-normal">{{ $topic->user->forumRoleLabel($topicCategoryId) }}</span>
                            @endif
                        </h6>
                        <small class="text-muted">
                            {{ $status->date ? \Carbon\Carbon::createFromTimestamp($status->date)->diffForHumans() : '' }}
                            @unless($group)
                                <span class="mx-1">•</span>
                                <i class="fa {{ optional($topic->category)->icons ?: 'fa-folder' }} me-1"></i> {{ optional($topic->category)->name }}
                            @endunless
                        </small>
                    </div>
                @else
                    <img src="{{ asset('upload/_avatar.png') }}" class="rounded-circle me-3" width="48" height="48">
                    <div>
                        <h6 class="fw-bold mb-0 text-muted">{{ __('messages.deleted_user') }}</h6>
                        <small class="text-muted">{{ $status->date ? \Carbon\Carbon::createFromTimestamp($status->date)->diffForHumans() : '' }}</small>
                    </div>
                @endif
            </div>

            <div class="dropdown">
                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                    @if($canEditTopic)
                        <li><a class="dropdown-item py-2" href="{{ route('forum.edit', $topic->id) }}"><i class="fa fa-edit me-2"></i> {{ __('messages.edit') }}</a></li>
                    @endif
                    @if($canDeleteTopic)
                        <li><button class="dropdown-item py-2 text-danger" onclick="deletePost({{ $topic->id }}, 2)"><i class="fa fa-trash me-2"></i> {{ __('messages.delete') }}</button></li>
                    @endif
                    @if($canPinTopic)
                        <li>
                            <form method="POST" action="{{ route('forum.pin', $topic->id) }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2">
                                    <i class="fa fa-thumbtack me-2"></i> {{ $topic->is_pinned ? __('messages.unpin_topic') : __('messages.pin_topic') }}
                                </button>
                            </form>
                        </li>
                    @endif
                    @if($canLockTopic)
                        <li>
                            <form method="POST" action="{{ route('forum.lock', $topic->id) }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2">
                                    <i class="fa {{ $topic->is_locked ? 'fa-unlock' : 'fa-lock' }} me-2"></i> {{ $topic->is_locked ? __('messages.unlock_topic') : __('messages.lock_topic') }}
                                </button>
                            </form>
                        </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li><button class="dropdown-item py-2" onclick="reportPost({{ $topic->id }}, 2)"><i class="fa fa-flag me-2"></i> {{ __('messages.report') }}</button></li>
                    <li><button class="dropdown-item py-2" onclick="navigator.clipboard.writeText('{{ route('forum.topic', $topic->id) }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa fa-link me-2"></i> {{ __('messages.copy_link') }}</button></li>
                </ul>
            </div>
        </div>

        <h5 class="fw-bold mb-2">
            <a href="{{ route('forum.topic', $topic->id) }}" class="text-dark text-decoration-none hover-primary">
                {{ $topic->name }}
            </a>
            @if($topic->is_pinned)
                <span class="badge bg-warning text-dark ms-2 small rounded-pill"><i class="fa fa-thumbtack small"></i></span>
            @endif
            @if($topic->is_locked)
                <span class="badge bg-secondary ms-2 small rounded-pill"><i class="fa fa-lock small"></i></span>
            @endif
        </h5>

        @if($topicExcerpt !== '')
            <p class="text-muted small mb-3">{{ $topicExcerpt }}</p>
        @endif

        @if($group)
            <div class="mb-3">
                @include('theme::partials.groups.badge', ['groupBadge' => $group])
            </div>
        @endif

        <div class="d-flex align-items-center gap-3 pt-3 border-top">
            <div class="small text-muted">
                <i class="fa fa-comment-dots me-1"></i> <span class="fw-bold text-dark">{{ $commentsCount }}</span> {{ __('messages.comments') }}
            </div>
            <div class="small text-muted">
                <i class="fa fa-heart me-1"></i> <span class="fw-bold text-dark">{{ $reactionsCount }}</span> {{ __('messages.reactions') }}
            </div>
        </div>
        
        <div id="report{{ $topic->id }}"></div>
        <div id="notif{{ $topic->id }}"></div>
    </div>
</div>
