@extends('theme::layouts.master')

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
            @else
                <li class="breadcrumb-item"><a href="{{ route('forum.category', $topic->cat) }}" class="text-decoration-none text-muted small">{{ $topic->category->name ?? __('messages.category') }}</a></li>
            @endif
            <li class="breadcrumb-item active text-truncate small fw-bold" aria-current="page" style="max-width: 300px;">{{ $topic->name }}</li>
        </ol>
    </nav>

    @include('theme::partials.ads', ['id' => 5])

    <div class="row g-4 mt-2">
        <div class="col-lg-9">
            <!-- Main Topic Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $status->id }}">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-bold">
                            <i class="fa fa-comments me-1"></i> {{ __('messages.topic') }}
                        </span>
                        @if($topic->is_pinned) <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm"><i class="fa fa-thumbtack me-1"></i> {{ __('messages.pinned') }}</span> @endif
                        @if($topic->is_locked) <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="fa fa-lock me-1"></i> {{ __('messages.locked') }}</span> @endif
                    </div>
                    
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-ellipsis-v"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                            @if($canEditTopic)
                                <li><a class="dropdown-item py-2 small fw-bold" href="{{ route('forum.edit', $topic->id) }}"><i class="fa fa-edit me-2 text-muted"></i> {{ __('messages.edit') }}</a></li>
                            @endif
                            @if($canDeleteTopic)
                                <li><button class="dropdown-item py-2 small fw-bold text-danger" onclick="deletePost({{ $topic->id }}, 2)"><i class="fa fa-trash me-2"></i> {{ __('messages.delete') }}</button></li>
                            @endif
                            @if($canPinTopic)
                                <li>
                                    <form method="POST" action="{{ route('forum.pin', $topic->id) }}">@csrf
                                        <button type="submit" class="dropdown-item py-2 small fw-bold"><i class="fa fa-thumbtack me-2 text-muted"></i> {{ $topic->is_pinned ? __('messages.unpin_topic') : __('messages.pin_topic') }}</button>
                                    </form>
                                </li>
                            @endif
                            @if($canLockTopic)
                                <li>
                                    <form method="POST" action="{{ route('forum.lock', $topic->id) }}">@csrf
                                        <button type="submit" class="dropdown-item py-2 small fw-bold"><i class="fa {{ $topic->is_locked ? 'fa-unlock' : 'fa-lock' }} me-2 text-muted"></i> {{ $topic->is_locked ? __('messages.unlock_topic') : __('messages.lock_topic') }}</button>
                                    </form>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><button class="dropdown-item py-2 small fw-bold" onclick="reportPost({{ $topic->id }}, 2)"><i class="fa fa-flag me-2 text-muted"></i> {{ __('messages.report') }}</button></li>
                        </ul>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <h1 class="h2 fw-black mb-4 text-dark">{{ $topic->name }}</h1>

                    <div class="row g-4">
                        <!-- Author Sidebar -->
                        <div class="col-md-3 border-end">
                            <div class="text-center sticky-top" style="top: 20px;">
                                @if($topic->user)
                                    <div class="position-relative d-inline-block mb-3">
                                        <a href="{{ route('profile.short', $topic->user->publicRouteIdentifier()) }}">
                                            <img src="{{ $topic->user->avatarUrl() }}" alt="" class="rounded-circle border border-4 border-white shadow" width="100" height="100" style="object-fit: cover;">
                                        </a>
                                        @if($topic->user->isOnline())
                                            <span class="position-absolute bottom-0 end-0 bg-success border border-3 border-white rounded-circle" style="width: 20px; height: 20px;" title="Online"></span>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('profile.short', $topic->user->publicRouteIdentifier()) }}" class="text-dark text-decoration-none">{{ $topic->user->username }}</a>
                                    </h6>
                                    @if($showForumRoleBadges)
                                        <div class="mb-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 smaller fw-bold rounded-pill px-3 py-1">{{ $topic->user->forumRoleLabel($topicCategoryId) }}</span>
                                        </div>
                                    @endif
                                    <p class="smaller text-muted mb-3 opacity-75">@ {{ $topic->user->username }}</p>
                                    
                                    <div class="d-grid gap-2 mt-3">
                                        <div class="bg-light p-2 rounded-3 border">
                                            <div class="smaller fw-bold text-muted text-uppercase letter-spacing-1">{{ __('messages.joined') }}</div>
                                            <div class="small fw-bold">{{ $topic->user->created_at ? (is_string($topic->user->created_at) ? \Carbon\Carbon::parse($topic->user->created_at)->format('M Y') : $topic->user->created_at->format('M Y')) : '' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-light p-4 rounded-circle mb-3 d-inline-block">
                                        <i class="fa fa-user-slash fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <h6 class="fw-bold text-muted">{{ __('messages.deleted_user') }}</h6>
                                @endif
                                
                                <div class="mt-4 pt-3 border-top">
                                    <div class="smaller text-muted mb-1"><i class="fa fa-clock me-1"></i> {{ __('messages.published_at') }}</div>
                                    <div class="small fw-bold">{{ date("Y-m-d H:i", $status->date) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content Body -->
                        <div class="col-md-9">
                            <div class="topic-text-content fs-5 text-dark" style="line-height: 1.8;">
                                @php
                                    $content = $topic->txt;
                                    $content = preg_replace('/#(\w+)/', '<a href="'.url('/tag').'/$1" class="text-primary text-decoration-none fw-bold">#$1</a>', $content);
                                    $content = strip_tags($content, '<p><a><b><br><li><ul><font><span><pre><u><s><img><iframe>');
                                @endphp
                                {!! nl2br($content) !!}

                                @if($topic->imageOption)
                                    <div class="mt-4 text-center">
                                        <a href="{{ asset($topic->imageOption->o_valuer) }}" target="_blank">
                                            <img src="{{ asset($topic->imageOption->o_valuer) }}" class="img-fluid rounded-4 shadow border p-1 bg-white">
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if($topic->attachments->isNotEmpty())
                                <div class="bg-light p-4 rounded-4 border-start border-4 border-primary mt-5 shadow-sm">
                                    <h6 class="fw-bold small text-uppercase text-muted mb-3 letter-spacing-1"><i class="fa fa-paperclip me-2 text-primary"></i>{{ __('messages.topic_attachments') }}</h6>
                                    <div class="d-grid gap-2">
                                        @foreach($topic->attachments as $attachment)
                                            <a href="{{ route('forum.attachment.download', $attachment->id) }}" class="d-flex align-items-center justify-content-between p-2 px-3 bg-white border rounded-pill text-decoration-none hover-bg-light transition-all">
                                                <div class="d-flex align-items-center overflow-hidden">
                                                    <i class="fa fa-file-alt text-primary me-3"></i>
                                                    <span class="text-dark fw-bold small text-truncate">{{ $attachment->original_name }}</span>
                                                </div>
                                                <span class="badge bg-light text-muted border rounded-pill">{{ $attachment->human_size }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Interactions Bar -->
                    <div class="d-flex justify-content-between align-items-center pt-4 mt-5 border-top">
                        <div class="d-flex gap-2 gap-md-4 align-items-center">
                            @auth
                                <div class="position-relative">
                                    <button class="btn btn-light rounded-pill px-3 fw-bold d-flex align-items-center gap-2 shadow-sm border" onclick="toggleReactionDropdown(this)">
                                        <span id="reaction_image{{ $status->id }}">
                                            @php
                                                $myReaction = \App\Models\Like::where('uid', Auth::id())->where('sid', $topic->id)->where('type', 2)->first();
                                                $reactionType = 'like';
                                                if($myReaction) {
                                                    $reactionOption = \App\Models\Option::where('o_parent', $myReaction->id)->where('o_type', 'data_reaction')->first();
                                                    if($reactionOption) $reactionType = $reactionOption->o_valuer;
                                                }
                                            @endphp
                                            @if($myReaction)
                                                <img src="{{ theme_asset('img/reaction/'.$reactionType.'.png') }}" width="22" alt="">
                                                <span class="text-primary small">{{ ucfirst($reactionType) }}</span>
                                            @else
                                                <i class="fa fa-thumbs-up text-muted"></i> <span class="small">{{ __('messages.react') }}</span>
                                            @endif
                                        </span>
                                    </button>
                                    <div class="reaction-options bg-white shadow-lg border rounded-pill p-2 position-absolute bottom-100 start-0 mb-3 d-none animate__animated animate__fadeInUp" style="z-index: 1000; min-width: 320px;">
                                        <div class="d-flex justify-content-around">
                                            @foreach(['like', 'love', 'dislike', 'happy', 'funny', 'wow', 'angry', 'sad'] as $reaction)
                                                <div class="reaction-icon-wrapper p-1 rounded-circle hover-bg-light cursor-pointer transition-all" onclick="postReaction({{ $topic->id }}, '{{ $reaction }}')">
                                                    <img src="{{ theme_asset('img/reaction/'.$reaction.'.png') }}" class="reaction-icon" width="28" title="{{ $reaction }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @if(!$topic->is_locked || $canCommentWhenLocked)
                                    <button class="btn btn-light rounded-pill px-3 fw-bold d-flex align-items-center gap-2 shadow-sm border" onclick="document.querySelector('.comment-form-input').focus()">
                                        <i class="fa fa-comment text-muted"></i> <span class="small">{{ __('messages.comment') }}</span>
                                    </button>
                                @endif
                            @endauth
                            
                            <div class="dropdown">
                                <button class="btn btn-light rounded-pill px-3 fw-bold d-flex align-items-center gap-2 shadow-sm border" data-bs-toggle="dropdown">
                                    <i class="fa fa-share-alt text-muted"></i> <span class="small">{{ __('messages.share') }}</span>
                                </button>
                                <ul class="dropdown-menu shadow border-0 rounded-3">
                                    @foreach(['facebook', 'twitter', 'linkedin', 'telegram'] as $social)
                                        <li><a class="dropdown-item py-2 small fw-bold" href="javascript:void(0);" onclick="sharePost('{{ $social }}', '{{ url()->current() }}', '{{ addslashes($topic->name) }}')">
                                            <i class="fab fa-{{ $social }} me-2 text-primary"></i> {{ ucfirst($social) }}
                                        </a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-center">
                                <div class="fw-black text-dark">{{ $topic->views ?? 0 }}</div>
                                <div class="smaller text-muted text-uppercase fw-bold">{{ __('messages.views') }}</div>
                            </div>
                            <div class="text-center border-start ps-3">
                                <div class="fw-black text-dark">{{ $topic->replies_count ?? 0 }}</div>
                                <div class="smaller text-muted text-uppercase fw-bold">{{ __('messages.replies') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="post-comment-list post-comment-list-{{ $topic->id }} comment_2_{{ $topic->id }} post{{ $status->id }} mt-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <h4 class="fw-black mb-0 text-dark">{{ __('messages.comments') }}</h4>
                    <span class="badge bg-primary rounded-pill px-3">{{ $topic->replies_count ?? 0 }}</span>
                </div>
                
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

        <!-- Sidebar Right -->
        <div class="col-lg-3">
            @if($group)
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="position-relative" style="height: 100px;">
                        <img src="{{ asset($group->cover) }}" class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="position-relative mb-3" style="margin-top: -60px;">
                            <img src="{{ asset($group->avatar) }}" class="rounded-circle border border-4 border-white shadow-sm" width="80" height="80" style="object-fit: cover;">
                        </div>
                        <h5 class="fw-black mb-1 text-dark">{{ $group->name }}</h5>
                        <p class="smaller text-muted mb-3">{{ Str::limit($group->short_description, 100) }}</p>
                        <a href="{{ route('groups.show', $group) }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm w-100">{{ __('messages.groups_open_group') }}</a>
                    </div>
                </div>
            @endif

            <!-- Category Info Widget -->
            @if(!$group && $topic->category)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.category') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                                <i class="fa {{ $topic->category->icons ?: 'fa-comments' }}"></i>
                            </div>
                            <h6 class="fw-bold mb-0"><a href="{{ route('forum.category', $topic->category->id) }}" class="text-dark text-decoration-none">{{ $topic->category->name }}</a></h6>
                        </div>
                        <p class="smaller text-muted mb-0">{!! strip_tags($topic->category->txt) !!}</p>
                    </div>
                </div>
            @endif

            <x-widget-column side="forum_right" />
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .object-fit-cover { object-fit: cover; }
    .hover-bg-light:hover { background-color: #f8f9fa !important; }
    .reaction-icon { transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .reaction-icon-wrapper:hover .reaction-icon { transform: scale(1.4) translateY(-5px); }
    .cursor-pointer { cursor: pointer; }
    .topic-text-content img { max-width: 100%; border-radius: 12px; }
    .sticky-top { z-index: 10; }
</style>

<script>
    function toggleReactionDropdown(btn) {
        const dropdown = btn.parentElement.querySelector('.reaction-options');
        dropdown.classList.toggle('d-none');
    }

    function postReaction(id, reaction) {
        fetch('{{ route('reaction.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id, type: 'forum', reaction: reaction })
        })
        .then(response => response.json())
        .then(data => {
            window.location.reload(); 
        });
    }

    function reportPost(id, type) {
        let reason = prompt('{{ __('messages.report_reason') }}');
        if(reason) {
            fetch('{{ route('forum.report') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ tp_id: id, s_type: type, txt: reason })
            }).then(() => alert('{{ __('messages.report_sent') }}'));
        }
    }
</script>
@endsection
