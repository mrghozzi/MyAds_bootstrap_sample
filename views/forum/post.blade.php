@extends('theme::layouts.master')
@include('theme::forum._assets')

@section('content')
@php
    $group = $group ?? null;
    $showForumRoleBadges = (int) ($forumSettings['show_role_badges'] ?? 1) === 1;
    $topicCategoryId = (int) $topic->cat;
    $groupAccess = app(\App\Services\GroupAccessService::class);
    $canManageGroupTopic = $group && auth()->check() ? $groupAccess->canManageGroup($group, auth()->user()) : false;
    $canEditTopic = auth()->check() && (
        auth()->id() === (int) $topic->uid
        || $canManageGroupTopic
        || auth()->user()->canModerateForum('edit_topics', $topicCategoryId)
    );
    $canDeleteTopic = auth()->check() && (
        auth()->id() === (int) $topic->uid
        || $canManageGroupTopic
        || auth()->user()->canModerateForum('delete_topics', $topicCategoryId)
    );
    $canPinTopic = auth()->check() && ($canManageGroupTopic || auth()->user()->canModerateForum('pin_topics', $topicCategoryId));
    $canLockTopic = auth()->check() && ($canManageGroupTopic || auth()->user()->canModerateForum('lock_topics', $topicCategoryId));
    $canCommentWhenLocked = auth()->check() && (
        auth()->id() === (int) $topic->uid
        || $canManageGroupTopic
        || auth()->user()->canModerateForum('lock_topics', $topicCategoryId)
    );
@endphp

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 px-4 rounded-pill shadow-sm border">
            <li class="breadcrumb-item"><a href="{{ route('forum.index') }}" class="text-decoration-none text-primary fw-bold"><i class="fa fa-home"></i></a></li>
            @if($group)
                <li class="breadcrumb-item"><a href="{{ route('groups.index') }}" class="text-decoration-none text-muted small">{{ __('messages.groups_title') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('groups.show', $group) }}" class="text-decoration-none text-muted small">{{ $group->name }}</a></li>
            @elseif($topic->cat > 0)
                <li class="breadcrumb-item"><a href="{{ route('forum.category', $topic->cat) }}" class="text-decoration-none text-muted small">{{ $topic->category->name ?? __('messages.category') }}</a></li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('portal.index') }}" class="text-decoration-none text-muted small">{{ __('messages.portal') ?? 'Community' }}</a></li>
            @endif
            <li class="breadcrumb-item active text-truncate small fw-bold" aria-current="page" style="max-width: 300px;">{{ Str::limit($topic->name, 40) }}</li>
        </ol>
    </nav>

    @include('theme::partials.ads', ['id' => 5])

    <div class="row justify-content-center mt-2">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $status->id }} activity-post-card">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('profile.show', $topic->user->username) }}" class="me-3 position-relative">
                            <img src="{{ $topic->user ? $topic->user->avatarUrl() : asset('upload/_avatar.png') }}" class="rounded-circle border border-2 border-white shadow-sm" width="48" height="48" alt="{{ $topic->user?->username }}">
                            <span class="position-absolute bottom-0 end-0 p-1 bg-{{ $topic->user && $topic->user->isOnline() ? 'success' : 'secondary' }} border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                        </a>
                        <div>
                            <h6 class="fw-bold mb-0">
                                <a href="{{ route('profile.show', $topic->user->username) }}" class="text-dark text-decoration-none hover-primary">{{ $topic->user?->username }}</a>
                                @if($showForumRoleBadges)
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 smaller fw-bold rounded-pill px-2 py-0.5 ms-1" style="font-size: 0.75rem;">
                                        {{ $topic->user->forumRoleLabel($topicCategoryId) }}
                                    </span>
                                @endif
                            </h6>
                            <small class="text-muted smaller fw-bold"><i class="fa fa-clock me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($status->date)->diffForHumans() }}</small>
                            @if($topic->is_pinned || $topic->is_locked)
                                <div class="mt-1">
                                    @if($topic->is_pinned)
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 small shadow-sm"><i class="fa fa-thumbtack me-1"></i> {{ __('messages.pinned') }}</span>
                                    @endif
                                    @if($topic->is_locked)
                                        <span class="badge bg-secondary rounded-pill px-2 py-0.5 small shadow-sm"><i class="fa fa-lock me-1"></i> {{ __('messages.locked') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-ellipsis-v"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                            @if($canEditTopic)
                                @if((int) $topic->cat === 0)
                                    <li><button class="dropdown-item py-2 small fw-bold" onclick="postEdit({{ $topic->id }}, {{ $status->s_type }})"><i class="fa fa-edit me-2 text-muted"></i> {{ __('messages.edit') }}</button></li>
                                @else
                                    <li><a class="dropdown-item py-2 small fw-bold" href="{{ route('forum.edit', $topic->id) }}"><i class="fa fa-edit me-2 text-muted"></i> {{ __('messages.edit') }}</a></li>
                                @endif
                            @endif
                            @if($canDeleteTopic)
                                <li><button class="dropdown-item py-2 small fw-bold text-danger" onclick="deletePost({{ $topic->id }}, 100)"><i class="fa fa-trash me-2"></i> {{ __('messages.delete') }}</button></li>
                            @endif
                            @include('theme::partials.activity.promotion_link', ['activity' => $status])
                            @if($canPinTopic && $topic->cat > 0)
                                <li>
                                    <form method="POST" action="{{ route('forum.pin', $topic->id) }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 small fw-bold"><i class="fa fa-thumbtack me-2 text-muted"></i> {{ $topic->is_pinned ? __('messages.unpin_topic') : __('messages.pin_topic') }}</button>
                                    </form>
                                </li>
                            @endif
                            @if($canLockTopic && $topic->cat > 0)
                                <li>
                                    <form method="POST" action="{{ route('forum.lock', $topic->id) }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 small fw-bold"><i class="fa {{ $topic->is_locked ? 'fa-unlock' : 'fa-lock' }} me-2 text-muted"></i> {{ $topic->is_locked ? __('messages.unlock_topic') : __('messages.lock_topic') }}</button>
                                    </form>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><button class="dropdown-item py-2 small fw-bold" onclick="reportPost({{ $topic->id }}, 2)"><i class="fa fa-flag me-2 text-muted"></i> {{ __('messages.report') }}</button></li>
                            <li><button class="dropdown-item py-2 small fw-bold" onclick="reportUser({{ $topic->uid }})"><i class="fa fa-flag me-2 text-muted"></i> {{ __('messages.report') }} {{ __('messages.author') }}</button></li>
                            <li><button class="dropdown-item py-2 small fw-bold" onclick="navigator.clipboard.writeText('{{ route('forum.topic', $topic->id) }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa fa-link me-2 text-muted"></i> {{ __('messages.copy_link') }}</button></li>
                        </ul>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if($topic->cat > 0)
                        <h1 class="h3 fw-black mb-4 text-dark">{{ $topic->name }}</h1>
                    @endif

                    <div class="post-text-content lh-lg text-dark fs-5 mb-4">
                        @php
                            $content = $topic->txt;
                            $content = preg_replace('/#(\w+)/', '<a href="'.url('/tag/$1').'" class="text-primary text-decoration-none fw-bold">#$1</a>', $content);
                            $content = strip_tags($content, '<p><a><b><br><li><ul><font><span><pre><u><s><img><iframe>');
                        @endphp
                        {!! nl2br($content) !!}
                    </div>

                    @if($status->linkPreviewRecord)
                        <div class="mb-4">
                            @include('theme::partials.activity.link_preview', ['activity' => $status])
                        </div>
                    @endif

                    @if($status->repostRecord)
                        <div class="mb-4">
                            @include('theme::partials.activity.repost_embed', ['activity' => $status])
                        </div>
                    @endif

                    @if($topic->attachments->isNotEmpty())
                        <div class="card bg-light border-0 rounded-4 p-4 mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa fa-paperclip me-2 text-muted"></i>{{ __('messages.topic_attachments') }}</h6>
                            <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                                @foreach($topic->attachments as $attachment)
                                    <li>
                                        <a href="{{ route('forum.attachment.download', $attachment->id) }}" class="text-decoration-none text-primary fw-bold d-inline-flex align-items-center gap-2">
                                            <i class="fa fa-file"></i>
                                            <span>{{ $attachment->original_name }}</span>
                                            <span class="text-muted smaller fw-normal">({{ $attachment->human_size }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="report{{ $topic->id }}"></div>
                    <div id="notif{{ $topic->id }}"></div>
                </div>

                <div class="card-footer bg-white py-3 px-4 border-top d-flex gap-4">
                    <div class="d-flex align-items-center text-muted small fw-bold">
                        <i class="fa fa-thumbs-up me-1 text-primary opacity-50"></i> {{ $topic->likes()->count() }} {{ __('messages.reactions') }}
                    </div>
                    <div class="d-flex align-items-center text-muted small fw-bold">
                        <i class="fa fa-comment me-1 text-primary opacity-50"></i> {{ $topic->comments()->count() }} {{ __('messages.comments') }}
                    </div>
                </div>

                @auth
                <!-- Action Bar -->
                <div class="card-footer bg-light bg-opacity-50 py-3 px-4 border-top d-flex justify-content-between">
                    <div class="position-relative">
                        <button class="btn btn-link text-decoration-none text-muted fw-bold p-0 d-flex align-items-center gap-2" onclick="toggleReactionDropdown(this)">
                            <div id="reaction_image{{ $status->id }}">
                                @php
                                    $myReaction = \App\Models\Like::where('uid', Auth::id())->where('sid', $topic->id)->where('type', 2)->first();
                                    $reactionType = 'like';
                                    if($myReaction) {
                                        $reactionOption = \App\Models\Option::where('o_parent', $myReaction->id)->where('o_type', 'data_reaction')->first();
                                        if($reactionOption) $reactionType = $reactionOption->o_valuer;
                                    }
                                @endphp
                                @if($myReaction)
                                    <img src="{{ theme_asset('img/reaction/'.$reactionType.'.png') }}" width="24" height="24" alt="reaction">
                                @else
                                    <i class="fa fa-thumbs-up"></i>
                                @endif
                            </div>
                            <span class="reaction_txt{{ $status->id }}" style="{{ $myReaction ? 'color: #1bc8db;' : '' }}">
                                {{ $myReaction ? ucfirst($reactionType) : __('messages.react') }}
                            </span>
                        </button>

                        <div class="reaction-options reaction-options-dropdown shadow border rounded-4 bg-white p-2 d-none position-absolute" style="z-index: 1000; bottom: 40px; left: 0; gap: 8px;">
                            @foreach(['like', 'love', 'dislike', 'happy', 'funny', 'wow', 'angry', 'sad'] as $reaction)
                                <div class="reaction-option cursor-pointer transition-all hover-scale" onclick="postReaction({{ $topic->id }}, '{{ $reaction }}')" style="width: 32px; height: 32px;">
                                    <img src="{{ theme_asset('img/reaction/'.$reaction.'.png') }}" class="w-100 h-100" alt="{{ $reaction }}">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if(!$topic->is_locked || $canCommentWhenLocked)
                        <button class="btn btn-link text-decoration-none text-muted fw-bold p-0 d-flex align-items-center gap-2" onclick="focusComment({{ $topic->id }})">
                            <i class="fa fa-comment"></i>
                            <span>{{ __('messages.comment') }}</span>
                        </button>
                    @endif

                    <div class="position-relative">
                        <button class="btn btn-link text-decoration-none text-muted fw-bold p-0 d-flex align-items-center gap-2" onclick="this.nextElementSibling.classList.toggle('d-none'); this.nextElementSibling.classList.toggle('d-flex');">
                            <i class="fa fa-share-alt"></i>
                            <span>{{ __('messages.share') }}</span>
                        </button>
                        <div class="reaction-options reaction-options-dropdown shadow border rounded-4 bg-white p-2 d-none position-absolute" style="z-index: 1000; bottom: 40px; right: 0; gap: 8px;">
                            @foreach(['facebook', 'twitter', 'linkedin', 'telegram'] as $social)
                                <div class="reaction-option cursor-pointer transition-all hover-scale" style="width: 32px; height: 32px;" onclick="sharePost('{{ $social }}', '{{ route('forum.topic', $topic->id) }}', '{{ $topic->name }}')">
                                    <img src="{{ theme_asset('img/icons/'.$social.'-icon.png') }}" class="w-100 h-100">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endauth
            </div>

            <!-- COMMENTS -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 p-4">
                <div class="post-comment-list post-comment-list-{{ $topic->id }} comment_100_{{ $topic->id }}">
                    @include('theme::partials.activity.comments', [
                        'comments' => $topic->comments()->orderBy('id', 'desc')->get(),
                        'id' => $topic->id,
                        'type' => 'forum',
                        'limit' => 100,
                        'hide_form' => $topic->is_locked && !$canCommentWhenLocked,
                        'locked_topic' => (bool) $topic->is_locked,
                        'forum_category_id' => $topicCategoryId
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

@include('theme::forum.scripts')

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.85rem; }
    .cursor-pointer { cursor: pointer; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .hover-scale:hover { transform: scale(1.15); }
    .hover-primary:hover { color: #615dfa !important; }
</style>
@endsection
