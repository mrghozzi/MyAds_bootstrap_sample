@php
    $showForumRoleBadges = (int) \App\Support\ForumSettings::get('show_role_badges', 1) === 1;
    $postNumber = 1;
    $viewerUser = auth()->user();
    $canManageTopicComments = false;
    if ($viewerUser && isset($topic) && $topic) {
        $isAuthor = (int) $viewerUser->id === (int) $topic->uid;
        $isModerator = $viewerUser->isAdmin() || $viewerUser->canModerateForum('edit_topics', (int) ($forum_category_id ?? $topic->cat));
        $canManageTopicComments = $isAuthor || $isModerator;
    }
@endphp

<div class="forum-modern-post-stream" id="forumPostStream_{{ $id }}">
    @forelse($comments as $comment)
        @php
            $postNumber++;
            $user = $comment->user;
            $rawText = $comment->txt;
            $date = $comment->date;
            $formattedText = \App\Support\ContentFormatter::formatForum($rawText);

            $myCommentReaction = null;
            $myCommentReactionOption = null;
            if (auth()->check()) {
                $myCommentReaction = \App\Models\Like::where('uid', auth()->id())
                    ->where('sid', $comment->id)
                    ->where('type', 4)
                    ->first();

                if ($myCommentReaction) {
                    $myCommentReactionOption = \App\Models\Option::where('o_parent', $myCommentReaction->id)
                        ->where('o_type', 'data_reaction')
                        ->first();
                }
            }

            $isOwner = auth()->check() && (int) auth()->id() === (int) $comment->uid;
            $catId = (int) ($forum_category_id ?? ($topic->cat ?? 0));
            $isForumModerator = auth()->check() && (
                auth()->user()->isAdmin() ||
                auth()->user()->canModerateForum('delete_comments', $catId ?: null) ||
                auth()->user()->canModerateForum(null, $catId ?: null)
            );
            $canDeleteComment = $isOwner || $isForumModerator;
        @endphp

        <!-- Forum Comment Card (Bootstrap 5 Circular Avatars) -->
        <article class="card border-0 shadow-sm mb-3 forum-modern-comment-card coment{{ $comment->id }} {{ $comment->is_pinned ? 'is-pinned-comment' : '' }} {{ $comment->is_best_answer ? 'is-best-answer' : '' }}" id="post-{{ $comment->id }}" data-comment-id="{{ $comment->id }}">
            <div class="card-body p-3 p-md-4">
                <header class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <div class="d-flex align-items-center gap-3">
                        @if($user)
                            <div class="position-relative" style="width: 42px; height: 42px;">
                                <a href="{{ route('profile.short', $user->publicRouteIdentifier()) }}" title="{{ $user->username }}">
                                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}" class="rounded-circle border border-2 border-white shadow-sm" width="42" height="42" style="object-fit: cover;">
                                </a>
                                @if($user->isOnline())
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                                @endif
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('profile.short', $user->publicRouteIdentifier()) }}" class="text-dark fw-bold text-decoration-none hover-primary fs-6">
                                        {{ $user->username }}
                                    </a>
                                    @if($showForumRoleBadges)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 smaller fw-bold rounded-pill px-2 py-1">
                                            {{ $user->forumRoleLabel($forum_category_id ?? null) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="smaller text-muted" title="{{ \Carbon\Carbon::createFromTimestamp((int) $date)->toDateTimeString() }}">
                                    {{ \Carbon\Carbon::createFromTimestamp((int) $date)->diffForHumans() }}
                                </div>
                            </div>
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 42px; height: 42px;">
                                <i class="fa fa-user-slash text-muted small"></i>
                            </div>
                            <div>
                                <span class="text-muted fw-bold">{{ __('messages.deleted_user') ?? 'Deleted User' }}</span>
                                <div class="smaller text-muted">
                                    {{ \Carbon\Carbon::createFromTimestamp((int) $date)->diffForHumans() }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if($comment->is_pinned)
                            <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small fw-bold" title="{{ __('messages.pinned_comment') ?? 'Pinned Comment' }}">
                                <i class="fa fa-thumb-tack me-1"></i> {{ __('messages.pinned_comment') ?? 'Pinned' }}
                            </span>
                        @endif
                        @if($comment->is_best_answer)
                            <span class="badge bg-success rounded-pill px-2 py-1 small fw-bold" title="{{ __('messages.best_answer') ?? 'Best Answer' }}">
                                <i class="fa fa-check-circle me-1"></i> {{ __('messages.best_answer') ?? 'Best Answer' }}
                            </span>
                        @endif

                        <a href="#post-{{ $comment->id }}" class="badge bg-light text-muted border text-decoration-none">#{{ $postNumber }}</a>

                        @if($canDeleteComment)
                            <button type="button" class="btn btn-sm text-danger p-0 ms-1 trash_comment{{ $comment->id }}" onclick="deleteForumComment({{ $comment->id }})" title="{{ __('messages.delete') }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        @endif
                    </div>
                </header>

                <div class="topic-text-content forum-comment-content fs-6 text-dark mb-3" style="line-height: 1.7;">
                    {!! $formattedText !!}
                </div>

                <footer class="d-flex align-items-center flex-wrap gap-2 pt-2 border-top">
                    @auth
                        <!-- Reaction Button -->
                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold border" id="reaction-btn-comment-{{ $comment->id }}" onclick="toggleReaction({{ $comment->id }}, 'forum_comment', 'like')">
                            @if($myCommentReactionOption)
                                <img src="{{ theme_asset('img/reaction/like.png') }}" width="16" class="me-1" alt="like">
                            @else
                                <i class="fa fa-thumbs-up text-primary me-1"></i>
                            @endif
                            <span>{{ __('messages.like') ?? 'Like' }}</span>
                        </button>

                        <!-- Reply to Comment -->
                        @if($user)
                            <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold border text-info" onclick="replyToComment('{{ $user->username }}', {{ $id }}, {{ $comment->id }})">
                                <i class="fa fa-reply me-1"></i>
                                <span>{{ __('messages.reply') ?? 'Reply' }}</span>
                            </button>
                        @endif

                        @if($canManageTopicComments)
                            <!-- Pin / Unpin Comment -->
                            <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold border {{ $comment->is_pinned ? 'text-warning' : 'text-muted' }}" onclick="togglePinComment({{ $comment->id }}, {{ $id }})" title="{{ $comment->is_pinned ? __('messages.unpin_comment') : __('messages.pin_comment') }}">
                                <i class="fa fa-thumb-tack me-1"></i>
                                <span>{{ $comment->is_pinned ? __('messages.unpin_comment') : __('messages.pin_comment') }}</span>
                            </button>

                            <!-- Best Answer Toggle -->
                            <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold border {{ $comment->is_best_answer ? 'text-success' : 'text-muted' }}" onclick="toggleBestAnswerComment({{ $comment->id }}, {{ $id }})" title="{{ $comment->is_best_answer ? __('messages.unmark_best_answer') : __('messages.choose_best_answer') }}">
                                <i class="fa {{ $comment->is_best_answer ? 'fa-check-circle text-success' : 'fa-check text-muted' }} me-1"></i>
                                <span>{{ $comment->is_best_answer ? __('messages.best_answer') : __('messages.choose_best_answer') }}</span>
                            </button>
                        @endif

                        @if($canDeleteComment)
                            <button type="button" class="btn btn-sm btn-link text-danger ms-auto text-decoration-none" onclick="deleteForumComment({{ $comment->id }})" title="{{ __('messages.delete') ?? 'Delete' }}">
                                <i class="fa fa-trash me-1"></i>
                                <span>{{ __('messages.delete') ?? 'Delete' }}</span>
                            </button>
                        @endif
                    @endauth
                </footer>

                <!-- Nested / Threaded Comments Section -->
                @if($comment->replies && $comment->replies->count() > 0)
                    <div class="forum-modern-replies-tree mt-3 pt-2" id="replies_for_{{ $comment->id }}">
                        @foreach($comment->replies as $reply)
                            @php
                                $replyUser = $reply->user;
                                $replyDate = $reply->date;
                                $replyFormatted = \App\Support\ContentFormatter::formatForum($reply->txt);
                                $isReplyOwner = auth()->check() && (int) auth()->id() === (int) $reply->uid;
                                $canDeleteReply = $isReplyOwner || $isForumModerator;
                            @endphp
                            <div class="card border-0 bg-light bg-opacity-50 rounded-3 p-3 mb-2 coment{{ $reply->id }}" id="comment_{{ $reply->id }}">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($replyUser)
                                            <a href="{{ route('profile.short', $replyUser->publicRouteIdentifier()) }}">
                                                <img src="{{ $replyUser->avatarUrl() }}" alt="{{ $replyUser->username }}" class="rounded-circle border" width="30" height="30" style="object-fit: cover;">
                                            </a>
                                            <div>
                                                <a href="{{ route('profile.short', $replyUser->publicRouteIdentifier()) }}" class="text-dark fw-bold small text-decoration-none hover-primary">
                                                    {{ $replyUser->username }}
                                                </a>
                                                <div class="smaller text-muted">
                                                    {{ \Carbon\Carbon::createFromTimestamp((int) $replyDate)->diffForHumans() }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                <i class="fa fa-user-slash text-muted smaller"></i>
                                            </div>
                                            <span class="text-muted smaller fw-bold">{{ __('messages.deleted_user') ?? 'Deleted User' }}</span>
                                        @endif
                                    </div>

                                    @if($canDeleteReply)
                                        <button type="button" class="btn btn-sm text-danger p-0" onclick="deleteForumComment({{ $reply->id }})" title="{{ __('messages.delete') }}">
                                            <i class="fa fa-trash small"></i>
                                        </button>
                                    @endif
                                </div>

                                <div class="topic-text-content small text-dark ps-4">
                                    {!! $replyFormatted !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </article>
    @empty
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-50">
            <div class="mb-3">
                <i class="fa fa-comments fa-3x text-muted opacity-25"></i>
            </div>
            <h5 class="fw-bold text-muted">{{ __('messages.no_comments_yet') ?? 'No replies yet' }}</h5>
            <p class="text-muted small mb-0">{{ __('messages.be_first_to_reply') ?? 'Be the first to join the discussion!' }}</p>
        </div>
    @endforelse
</div>

<!-- Forum Reply Composer Card -->
<div id="reply" class="forum-modern-reply-target" style="scroll-margin-top: 100px;"></div>
@if(!isset($hide_form) || !$hide_form)
    @auth
        <div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden" id="forumReplyBox_{{ $id }}">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->username }}" class="rounded-circle border" width="34" height="34" style="object-fit: cover;">
                    <span class="fw-bold text-dark">{{ __('messages.reply') ?? 'Write a Reply' }}</span>
                </div>

                <!-- Replying To Banner -->
                <div id="reply_indicator_{{ $id }}" class="badge bg-primary bg-opacity-10 text-primary p-2 px-3 rounded-pill" style="display: none;">
                    <i class="fa fa-reply me-1"></i>
                    <span>{{ __('messages.replying_to') ?? 'Replying to' }} <strong>@<span id="reply_username_{{ $id }}"></span></strong></span>
                    <button type="button" class="btn-close btn-close-sm ms-2" onclick="cancelReply({{ $id }})" style="font-size: 8px;"></button>
                </div>
            </div>

            <div class="card-body p-4">
                <input type="hidden" id="parent_comment_id_{{ $id }}" name="parent_id" value="">

                <!-- Forum Markdown Editor Box -->
                <div class="forum-modern-editor-box">
                    <div class="forum-modern-editor-toolbar">
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('txt_comment{{ $id }}'), '**', '**', 'bold text')" title="Bold"><i class="fa fa-bold"></i></button>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('txt_comment{{ $id }}'), '*', '*', 'italic text')" title="Italic"><i class="fa fa-italic"></i></button>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('txt_comment{{ $id }}'), '~~', '~~', 'strikethrough')" title="Strike"><i class="fa fa-strikethrough"></i></button>
                        <div class="forum-modern-tool-separator"></div>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('txt_comment{{ $id }}'), '# ', '', 'Heading')" title="Heading"><i class="fa fa-header"></i></button>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('txt_comment{{ $id }}'), '`', '`', 'code')" title="Code"><i class="fa fa-code"></i></button>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('txt_comment{{ $id }}'), '\n```\n', '\n```\n', 'code block')" title="Code block"><i class="fa fa-file-code-o"></i></button>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('txt_comment{{ $id }}'), '> ', '', 'quote text')" title="Quote"><i class="fa fa-quote-left"></i></button>
                        <div class="forum-modern-tool-separator"></div>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('txt_comment{{ $id }}'), '[', '](https://)', 'link title')" title="Link"><i class="fa fa-link"></i></button>
                        <button type="button" class="forum-modern-tool-btn" onclick="document.getElementById('comment_media_file_{{ $id }}').click()" title="{{ __('messages.image') ?? 'Attach Image' }}"><i class="fa fa-image"></i></button>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.insert(document.getElementById('txt_comment{{ $id }}'), '\n- Item 1\n- Item 2\n')" title="List"><i class="fa fa-list-ul"></i></button>
                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.insert(document.getElementById('txt_comment{{ $id }}'), ' 😊 ')" title="Emoji"><i class="fa fa-smile-o"></i></button>

                        <button type="button" class="forum-modern-tool-btn ms-auto" onclick="toggleForumCommentPreview({{ $id }})" id="preview_btn_{{ $id }}" title="{{ __('messages.preview') ?? 'Toggle Preview' }}">
                            <i class="fa fa-eye"></i> <span class="smaller ms-1 d-none d-sm-inline">{{ __('messages.preview') ?? 'Preview' }}</span>
                        </button>
                    </div>

                    <input type="file" id="comment_media_file_{{ $id }}" class="d-none" accept=".jpg,.jpeg,.png,.gif,.webp,.bmp" style="display: none;">

                    <div class="forum-modern-editor-split">
                        <textarea 
                            id="txt_comment{{ $id }}" 
                            name="comment" 
                            class="form-control rounded-0" 
                            placeholder="{{ __('messages.forum_post_composer_placeholder') ?? 'Write your reply using Markdown...' }}" 
                            rows="4"
                        ></textarea>
                        <div id="comment_preview_{{ $id }}" class="forum-modern-editor-preview"></div>
                    </div>

                    <!-- Attachment preview bar -->
                    <div class="p-2 border-top bg-light d-flex align-items-center gap-2" id="comment_media_preview_{{ $id }}" style="display: none;">
                        <img src="" alt="preview" id="comment_media_thumb_{{ $id }}" class="rounded" style="max-height: 48px; object-fit: cover;">
                        <span class="smaller text-muted text-truncate" id="comment_media_name_{{ $id }}"></span>
                        <button type="button" class="btn btn-sm btn-link text-danger ms-auto p-0" id="comment_media_remove_{{ $id }}" onclick="clearCommentMedia({{ $id }})">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Footer Action Row -->
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <span class="smaller text-muted">
                        <i class="fa fa-info-circle me-1"></i> {{ __('messages.markdown_supported') ?? 'Markdown formatting supported' }}
                    </span>

                    <button 
                        type="button" 
                        class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" 
                        id="btn_post_comment_{{ $id }}" 
                        onclick="postForumComment({{ $id }})"
                    >
                        <i class="fa fa-paper-plane me-1"></i>
                        <span>{{ __('messages.reply') ?? 'Post Reply' }}</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 mt-4 bg-light">
            <h6 class="fw-bold mb-2">{{ __('messages.login_to_participate') ?? 'Log in to join the discussion' }}</h6>
            <p class="small text-muted mb-3">{{ __('messages.need_account_to_reply') ?? 'You need an account to post replies and join the discussion.' }}</p>
            <div>
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fa fa-sign-in me-1"></i> {{ __('messages.login') ?? 'Sign In' }}
                </a>
            </div>
        </div>
    @endauth
@elseif(($locked_topic ?? false))
    <div class="alert alert-warning mt-4 d-flex align-items-center gap-2 rounded-4">
        <i class="fa fa-lock fa-lg"></i>
        <span>{{ __('messages.topic_locked_for_comments') ?? 'This topic is locked. New replies are not allowed.' }}</span>
    </div>
@endif

<script>
window.postForumComment = function(id) {
    const input = document.getElementById('txt_comment' + id);
    if (!input) return;

    const text = input.value.trim();
    const mediaFileInput = document.getElementById('comment_media_file_' + id);
    const hasFile = mediaFileInput && mediaFileInput.files && mediaFileInput.files.length > 0;
    if (!text && !hasFile) {
        input.focus();
        return;
    }

    const btn = document.getElementById('btn_post_comment_' + id);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Posting...';
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const formData = new FormData();
    formData.append('id', id);
    formData.append('type', 'forum');
    formData.append('comment', text);
    formData.append('limit', 100);
    formData.append('view', 'topic_comments');

    const parentInput = document.getElementById('parent_comment_id_' + id);
    if (parentInput && parentInput.value) {
        formData.append('parent_id', parentInput.value);
    }

    if (hasFile) {
        formData.append('attachment', mediaFileInput.files[0]);
    }

    fetch('{{ route("comment.store") }}', {
        method: 'POST',
        headers: {
            'Accept': 'text/html, application/json',
            'X-CSRF-TOKEN': token
        },
        body: formData
    })
    .then(async response => {
        if (!response.ok) {
            const err = await response.json().catch(() => ({}));
            throw new Error(err.error || 'Failed to post reply');
        }
        return response.text();
    })
    .then(html => {
        const streamContainer = document.querySelector('.post-comment-list-' + id);
        if (streamContainer && html) {
            streamContainer.innerHTML = html;
        }
        input.value = '';
        if (window.clearCommentMedia) window.clearCommentMedia(id);
        if (window.cancelReply) window.cancelReply(id);
    })
    .catch(err => {
        alert(err.message || 'Error posting reply');
    })
    .finally(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-paper-plane me-1"></i> <span>{{ __("messages.reply") ?? "Post Reply" }}</span>';
        }
    });
};

window.replyToComment = function(username, topicId, parentId) {
    const parentInput = document.getElementById('parent_comment_id_' + topicId);
    const indicator = document.getElementById('reply_indicator_' + topicId);
    const userSpan = document.getElementById('reply_username_' + topicId);
    const textarea = document.getElementById('txt_comment' + topicId);

    if (parentInput && parentId) {
        parentInput.value = parentId;
    }
    if (indicator && userSpan) {
        userSpan.textContent = username;
        indicator.style.display = 'inline-flex';
    }
    if (textarea) {
        const mention = '@' + username + ' ';
        if (!textarea.value.includes(mention)) {
            textarea.value = mention + textarea.value;
        }
        textarea.focus();
        textarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
};

window.cancelReply = function(topicId) {
    const parentInput = document.getElementById('parent_comment_id_' + topicId);
    const indicator = document.getElementById('reply_indicator_' + topicId);
    if (parentInput) parentInput.value = '';
    if (indicator) indicator.style.display = 'none';
};

window.toggleForumCommentPreview = function(id) {
    const textarea = document.getElementById('txt_comment' + id);
    const preview = document.getElementById('comment_preview_' + id);
    const btn = document.getElementById('preview_btn_' + id);
    if (!textarea || !preview) return;

    if (preview.classList.contains('is-active')) {
        preview.classList.remove('is-active');
        preview.style.display = 'none';
        if (btn) btn.classList.remove('text-primary');
    } else {
        const text = textarea.value;
        if (typeof ForumEditor !== 'undefined' && typeof ForumEditor.renderMarkdown === 'function') {
            preview.innerHTML = ForumEditor.renderMarkdown(text);
        } else {
            preview.innerHTML = text.replace(/\n/g, '<br>');
        }
        preview.classList.add('is-active');
        preview.style.display = 'block';
        if (btn) btn.classList.add('text-primary');
    }
};

window.clearCommentMedia = function(id) {
    const fileInput = document.getElementById('comment_media_file_' + id);
    if (fileInput) fileInput.value = '';
    const preview = document.getElementById('comment_media_preview_' + id);
    if (preview) preview.style.display = 'none';
    const thumb = document.getElementById('comment_media_thumb_' + id);
    if (thumb) thumb.src = '';
    const nameEl = document.getElementById('comment_media_name_' + id);
    if (nameEl) nameEl.textContent = '';
};

window.togglePinComment = function(commentId, topicId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/forum/comment/' + commentId + '/pin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Failed to toggle pin');
        return data;
    })
    .then(() => reloadForumComments(topicId))
    .catch(err => alert(err.message || 'Error occurred'));
};

window.toggleBestAnswerComment = function(commentId, topicId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/forum/comment/' + commentId + '/best-answer', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Failed to toggle best answer');
        return data;
    })
    .then(() => reloadForumComments(topicId))
    .catch(err => alert(err.message || 'Error occurred'));
};

window.reloadForumComments = function(topicId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('{{ route("comment.load") }}?id=' + topicId + '&type=forum&limit=100&view=topic_comments', {
        headers: {
            'Accept': 'text/html, application/json',
            'X-CSRF-TOKEN': token
        }
    })
    .then(response => response.text())
    .then(html => {
        const container = document.querySelector('.post-comment-list-' + topicId);
        if (container && html) {
            container.innerHTML = html;
        }
    })
    .catch(err => console.error('Error reloading comments:', err));
};

window.deleteForumComment = function(commentId) {
    if (!confirm(@json(__('messages.confirm_delete') ?? 'Are you sure you want to delete this comment?'))) return;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('{{ route("comment.delete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ trashid: commentId, type: 'forum' })
    })
    .then(async response => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok || (data.status !== 'success' && !data.success)) {
            throw new Error(data.error || 'Failed to delete comment');
        }
        return data;
    })
    .then(() => {
        const el = document.getElementById('post-' + commentId) || document.getElementById('comment_' + commentId) || document.querySelector('.coment' + commentId);
        if (el) {
            el.style.transition = 'all 0.3s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(() => el.remove(), 300);
        }
    })
    .catch(err => alert(err.message || 'Error occurred'));
};

(function() {
    const id = {{ $id }};
    const fileInput = document.getElementById('comment_media_file_' + id);
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const preview = document.getElementById('comment_media_preview_' + id);
                const thumb = document.getElementById('comment_media_thumb_' + id);
                const nameEl = document.getElementById('comment_media_name_' + id);
                if (nameEl) nameEl.textContent = file.name;
                if (thumb && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        thumb.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
                if (preview) preview.style.display = 'flex';
            }
        });
    }
})();
</script>
