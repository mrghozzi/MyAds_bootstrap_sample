@php
    $showForumRoleBadges = (int) \App\Support\ForumSettings::get('show_role_badges', 1) === 1;
@endphp

@foreach($comments as $comment)
    @php
        $user = $type === 'forum' ? $comment->user : \App\Models\User::find($comment->o_order);
        $rawText = $type === 'forum' ? $comment->txt : $comment->o_valuer;
        $date = $type === 'forum' ? $comment->date : $comment->o_mode;
        $formattedText = \App\Support\ForumCommentFormatter::format($rawText);

        $commentReactionType = 0;
        $reactionTypeString = '';
        if ($type === 'forum') {
            $commentReactionType = 4;
            $reactionTypeString = 'forum_comment';
        } elseif ($type === 'directory') {
            $commentReactionType = 44;
            $reactionTypeString = 'directory_comment';
        } elseif ($type === 'store') {
            $commentReactionType = 444;
            $reactionTypeString = 'store_comment';
        } elseif ($type === 'knowledgebase') {
            $commentReactionType = \App\Services\KnowledgebaseCommunityService::COMMENT_REACTION_TYPE;
            $reactionTypeString = 'kb_comment';
        } elseif ($type === 'order') {
            $commentReactionType = 66;
            $reactionTypeString = 'order_comment';
        }

        $forumCategoryId = $forum_category_id ?? null;
        if ($type === 'forum' && $forumCategoryId === null) {
            $forumCategoryId = (int) optional(\App\Models\ForumTopic::find($comment->tid))->cat;
        }

        $myCommentReaction = null;
        $myCommentReactionOption = null;
        if (auth()->check()) {
            $myCommentReaction = \App\Models\Like::where('uid', auth()->id())
                ->where('sid', $comment->id)
                ->where('type', $commentReactionType)
                ->first();

            if ($myCommentReaction) {
                $myCommentReactionOption = \App\Models\Option::where('o_parent', $myCommentReaction->id)
                    ->where('o_type', 'data_reaction')
                    ->first();
            }
        }

        $isOwner = auth()->check() && $user && (int) auth()->id() === (int) $user->id;
        $canDeleteAsForumModerator = auth()->check()
            && $type === 'forum'
            && $forumCategoryId
            && auth()->user()->canModerateForum('delete_comments', (int) $forumCategoryId);
        $canDeleteComment = auth()->check() && ($isOwner || auth()->user()->isAdmin() || $canDeleteAsForumModerator);
    @endphp

    <div class="d-flex gap-3 mb-3 p-3 bg-light bg-opacity-50 rounded-4 coment{{ $comment->id }}" id="comment_{{ $comment->id }}">
        <!-- User Avatar -->
        <div>
            @if($user)
                <a href="{{ route('profile.show', $user->username) }}" class="position-relative d-block">
                    <img src="{{ $user->avatarUrl() }}" class="rounded-circle border border-2 border-white shadow-sm" width="36" height="36" alt="{{ $user->username }}">
                    @if($user->isOnline())
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 8px; height: 8px;"></span>
                    @endif
                </a>
            @else
                <img src="{{ asset('upload/_avatar.png') }}" class="rounded-circle border border-2 border-white shadow-sm" width="36" height="36" alt="">
            @endif
        </div>

        <!-- Comment Content -->
        <div class="flex-grow-1 min-width-0">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                <div>
                    @if($user)
                        <h6 class="fw-bold mb-0 small">
                            <a href="{{ route('profile.show', $user->username) }}" class="text-dark text-decoration-none hover-primary">{{ $user->username }}</a>
                            @if($user->hasVerifiedBadge())
                                <svg class="verified-icon ms-1" viewBox="0 0 24 24" width="12" height="12" fill="#23d2e2" style="vertical-align: middle;"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1.9 14.7L6 12.6l1.5-1.5 2.6 2.6 6.4-6.4 1.5 1.5-7.9 7.9z"/></svg>
                            @endif
                        </h6>
                        @if($type === 'forum' && $showForumRoleBadges)
                            <small class="text-muted smaller fw-bold d-block mt-1">{{ $user->forumRoleLabel($forumCategoryId ?: null) }}</small>
                        @endif
                    @else
                        <span class="text-muted small fw-bold">{{ __('messages.deleted_user') }}</span>
                    @endif
                </div>
                <small class="text-muted smaller fw-bold">{{ \Carbon\Carbon::createFromTimestamp((int) $date)->diffForHumans() }}</small>
            </div>

            <div class="small text-secondary lh-lg mb-2 forum-rdx-comment-body post-comment-text">
                {!! $formattedText !!}
            </div>

            <!-- Order Rating / Actions -->
            @if($type === 'order' && isset($order))
                @if($order->best_offer_id == $comment->id)
                    <span class="badge bg-info text-white rounded-pill px-2 py-1 smaller mb-2 d-inline-block">
                        <i class="fa fa-trophy me-1"></i> {{ __('messages.best_offer') }}
                    </span>
                @endif

                @php $rating = (int) $comment->o_mode; @endphp
                @if($rating > 0)
                    <div class="text-warning small mb-2">
                        @for($i=1; $i<=5; $i++)
                            <i class="fa fa-star{{ $i <= $rating ? '' : '-o' }}"></i>
                        @endfor
                    </div>
                @endif

                @if(auth()->check() && auth()->id() == $order->uid && $order->statu == 1)
                    <div class="d-flex gap-2 align-items-center flex-wrap mb-2">
                        @if($order->best_offer_id != $comment->id)
                            <form action="{{ route('orders.select_best', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="offer_id" value="{{ $comment->id }}">
                                <button type="submit" class="btn btn-light btn-sm rounded-pill border px-3 py-1 fw-bold small">
                                    <i class="fa fa-check me-1"></i> {{ __('messages.select_best_offer') }}
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('orders.rate', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="offer_id" value="{{ $comment->id }}">
                            <select name="rating" onchange="this.form.submit()" class="form-select form-select-sm rounded-pill d-inline-block w-auto py-1 px-3 small border" style="font-size: 11px;">
                                <option value="0">{{ __('messages.rate_offer') }}</option>
                                @for($i=1; $i<=5; $i++)
                                    <option value="{{ $i }}" {{ $rating == $i ? 'selected' : '' }}>{{ $i }} {{ __('messages.stars') ?? 'Stars' }}</option>
                                @endfor
                            </select>
                        </form>

                        <a href="{{ url('/messages/' . \App\Models\Message::encodeConversationRouteKey(auth()->user(), $user->id)) }}" class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-bold small text-decoration-none">
                            <i class="fa fa-envelope me-1"></i> {{ __('messages.contact_offer_owner') }}
                        </a>
                    </div>
                @endif
            @endif

            <!-- Comment Actions -->
            <div class="d-flex gap-3 align-items-center">
                @if(auth()->check())
                    <button class="btn btn-link p-0 text-decoration-none text-muted small fw-bold d-flex align-items-center gap-1 border-0 bg-transparent" id="reaction-btn-comment-{{ $comment->id }}" onclick="toggleReaction({{ $comment->id }}, '{{ $reactionTypeString }}', 'like')">
                        @if($myCommentReactionOption)
                            <img src="{{ theme_asset('img/reaction/like.png') }}" width="14" height="14" alt="like">
                            <span class="text-primary">{{ __('messages.liked') ?? 'Liked' }}</span>
                        @else
                            <i class="fa-regular fa-thumbs-up"></i>
                            <span>{{ __('messages.like') ?? 'Like' }}</span>
                        @endif
                    </button>
                @endif

                @if($canDeleteComment)
                    <button class="btn btn-link p-0 text-decoration-none text-danger small fw-bold d-flex align-items-center gap-1 border-0 bg-transparent trash_comment{{ $comment->id }}" onclick="deleteComment({{ $comment->id }}, '{{ $type }}')">
                        <i class="fa-regular fa-trash-can"></i>
                        <span>{{ __('messages.delete') }}</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
@endforeach

@if(!isset($hide_form) || !$hide_form)
    <div class="comment_form{{ $id }} mt-3">
        @auth
            <div class="d-flex gap-3 p-3 bg-white rounded-4 border border-light">
                <img src="{{ auth()->user()->avatarUrl() }}" class="rounded-circle border" width="36" height="36" alt="">
                <div class="flex-grow-1">
                    <div class="border rounded-3 p-2 bg-light bg-opacity-20 forum-rdx-comment-composer" id="comment_composer_{{ $id }}">
                        <!-- Toolbar -->
                        <div class="d-flex gap-1 border-bottom pb-2 mb-2 flex-wrap forum-rdx-comment-toolbar">
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" data-md-action="bold" data-target="txt_comment{{ $id }}" title="{{ __('messages.markdown_bold') }}">
                                <i class="fa fa-bold text-secondary" style="font-size: 11px;"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" data-md-action="italic" data-target="txt_comment{{ $id }}" title="{{ __('messages.markdown_italic') }}">
                                <i class="fa fa-italic text-secondary" style="font-size: 11px;"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" data-md-action="quote" data-target="txt_comment{{ $id }}" title="{{ __('messages.markdown_quote') }}">
                                <i class="fa fa-quote-left text-secondary" style="font-size: 11px;"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" data-md-action="code" data-target="txt_comment{{ $id }}" title="{{ __('messages.markdown_code') }}">
                                <i class="fa fa-code text-secondary" style="font-size: 11px;"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" data-md-action="link" data-target="txt_comment{{ $id }}" data-link-prompt="{{ __('messages.markdown_link_prompt') }}" data-link-label-prompt="{{ __('messages.markdown_link_label_prompt') }}" data-link-default-label="{{ __('messages.markdown_link_default_label') }}" title="{{ __('messages.markdown_link') }}">
                                <i class="fa fa-link text-secondary" style="font-size: 11px;"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" data-md-action="emoji" data-target="txt_comment{{ $id }}" title="{{ __('messages.markdown_emoji') }}">
                                <i class="fa fa-smile text-secondary" style="font-size: 11px;"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" data-comment-media-btn="{{ $id }}" title="{{ __('messages.image') ?? 'إرفاق صورة' }}">
                                <i class="fa fa-image text-secondary" style="font-size: 11px;"></i>
                            </button>
                        </div>
                        <input type="file" id="comment_media_file_{{ $id }}" class="d-none" accept=".jpg,.jpeg,.png,.gif,.webp,.bmp" style="display: none;">
                        <div class="forum-rdx-comment-dropzone-indicator" id="comment_drop_indicator_{{ $id }}">
                            <i class="fa fa-cloud-arrow-up" aria-hidden="true"></i>
                            <span>{{ __('messages.drop_files_here') ?? 'أفلت الصورة هنا للإرفاق' }}</span>
                        </div>
                        <textarea id="txt_comment{{ $id }}" name="comment_text" class="form-control border-0 bg-transparent p-0 small forum-rdx-comment-input" data-md-editor="1" rows="2" placeholder="{{ __('messages.your_comment') }}" style="box-shadow: none; resize: none;"></textarea>
                        <div class="forum-rdx-comment-media-preview is-hidden" id="comment_media_preview_{{ $id }}">
                            <img src="" alt="preview" id="comment_media_thumb_{{ $id }}" class="comment-media-thumb">
                            <span class="comment-media-name" id="comment_media_name_{{ $id }}"></span>
                            <button type="button" class="comment-media-remove" id="comment_media_remove_{{ $id }}" aria-label="{{ __('messages.delete') ?? 'Delete' }}" title="{{ __('messages.delete') ?? 'حذف' }}">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-comment-submit="{{ $id }}" onclick="postComment({{ $id }}, '{{ $type }}')">
                            <i class="fa-regular fa-paper-plane me-1"></i> {{ __('messages.comment') }}
                        </button>
                    </div>
                </div>
            </div>
        @endauth
    </div>
@elseif(($locked_topic ?? false) && $type === 'forum')
    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-0 mt-3 small fw-bold">
        <i class="fa fa-lock me-1"></i> {{ __('messages.topic_locked_for_comments') }}
    </div>
@elseif(($hide_form ?? false) && $type === 'order')
    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-0 mt-3 small fw-bold">
        <i class="fa fa-lock me-1"></i> {{ __('messages.order_closed_for_comments') }}
    </div>
@endif

<p class="text-center mt-3 mb-0">
    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4 fw-bold comment_heading{{ $id }}" onclick="loadComments({{ $id }}, '{{ $type }}', {{ $limit + 5 }})">
        {{ __('messages.load_more_comments') }}
    </button>
</p>

<script>
    window.pendingCommentMedia = window.pendingCommentMedia || {};

    if (typeof window.getCommentPendingMedia !== 'function') {
        window.getCommentPendingMedia = function (id) {
            return window.pendingCommentMedia[id] || null;
        };
    }

    if (typeof window.clearCommentPendingMedia !== 'function') {
        window.clearCommentPendingMedia = function (id) {
            delete window.pendingCommentMedia[id];
            const fileInput = document.getElementById('comment_media_file_' + id);
            const preview = document.getElementById('comment_media_preview_' + id);
            const thumb = document.getElementById('comment_media_thumb_' + id);
            const nameEl = document.getElementById('comment_media_name_' + id);

            if (fileInput) fileInput.value = '';
            if (thumb) thumb.src = '';
            if (nameEl) nameEl.textContent = '';
            if (preview) preview.classList.add('is-hidden');
        };
    }

    if (typeof window.setCommentPendingMedia !== 'function') {
        window.setCommentPendingMedia = function (id, file) {
            if (!file) {
                window.clearCommentPendingMedia(id);
                return;
            }

            const maxBytes = 5 * 1024 * 1024;
            if (file.size > maxBytes) {
                alert(@json(__('messages.max_file_size_exceeded') ?? 'حجم الملف يتجاوز الحد الأقصى (5 ميغابايت)'));
                window.clearCommentPendingMedia(id);
                return;
            }

            window.pendingCommentMedia[id] = file;
            const preview = document.getElementById('comment_media_preview_' + id);
            const thumb = document.getElementById('comment_media_thumb_' + id);
            const nameEl = document.getElementById('comment_media_name_' + id);

            if (nameEl) {
                const sizeKb = (file.size / 1024).toFixed(1) + ' KB';
                nameEl.textContent = file.name + ' (' + sizeKb + ')';
            }

            if (thumb && file.type && file.type.indexOf('image') !== -1) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    thumb.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            if (preview) {
                preview.classList.remove('is-hidden');
            }
        };
    }

    if (typeof window.initCommentComposerMedia !== 'function') {
        window.initCommentComposerMedia = function (id) {
            const composer = document.getElementById('comment_composer_' + id) || (document.getElementById('txt_comment' + id) ? document.getElementById('txt_comment' + id).closest('.forum-rdx-comment-composer') : null);
            const mediaBtn = document.querySelector('[data-comment-media-btn="' + id + '"]');
            const fileInput = document.getElementById('comment_media_file_' + id);
            const removeBtn = document.getElementById('comment_media_remove_' + id);
            const textarea = document.getElementById('txt_comment' + id);

            if (mediaBtn && fileInput && !mediaBtn.dataset.mediaBound) {
                mediaBtn.dataset.mediaBound = '1';
                mediaBtn.addEventListener('click', function () {
                    fileInput.click();
                });
            }

            if (fileInput && !fileInput.dataset.mediaBound) {
                fileInput.dataset.mediaBound = '1';
                fileInput.addEventListener('change', function () {
                    if (fileInput.files && fileInput.files.length > 0) {
                        window.setCommentPendingMedia(id, fileInput.files[0]);
                    }
                });
            }

            if (removeBtn && !removeBtn.dataset.mediaBound) {
                removeBtn.dataset.mediaBound = '1';
                removeBtn.addEventListener('click', function () {
                    window.clearCommentPendingMedia(id);
                });
            }

            if (composer && !composer.dataset.dropBound) {
                composer.dataset.dropBound = '1';
                ['dragenter', 'dragover'].forEach(function (eventName) {
                    composer.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        composer.classList.add('comment-dropzone--active');
                    });
                });

                ['dragleave', 'drop'].forEach(function (eventName) {
                    composer.addEventListener(eventName, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        composer.classList.remove('comment-dropzone--active');
                    });
                });

                composer.addEventListener('drop', function (e) {
                    const dt = e.dataTransfer;
                    if (dt && dt.files && dt.files.length > 0) {
                        const file = dt.files[0];
                        if (file && (!file.type || file.type.indexOf('image') !== -1)) {
                            window.setCommentPendingMedia(id, file);
                        }
                    }
                });
            }

            function handleCommentPaste(e) {
                const cd = e.clipboardData || window.clipboardData;
                if (!cd || !cd.items) return;
                for (let i = 0; i < cd.items.length; i++) {
                    const item = cd.items[i];
                    if (item.type && (item.type.indexOf('image') !== -1 || item.kind === 'file')) {
                        const file = item.getAsFile();
                        if (file) {
                            e.preventDefault();
                            const ext = (file.type.split('/')[1] || 'png').replace('jpeg', 'jpg');
                            const filename = file.name && file.name !== 'image.png'
                                ? file.name
                                : 'pasted-comment-' + Date.now() + '.' + ext;
                            let named = file;
                            try {
                                named = new File([file], filename, { type: file.type });
                            } catch (err) {}
                            window.setCommentPendingMedia(id, named);
                            break;
                        }
                    }
                }
            }

            if (textarea && !textarea.dataset.pasteBound) {
                textarea.dataset.pasteBound = '1';
                textarea.addEventListener('paste', handleCommentPaste);
            }
            if (composer && !composer.dataset.pasteBound) {
                composer.dataset.pasteBound = '1';
                composer.addEventListener('paste', handleCommentPaste);
            }
        };
    }

    if (typeof window.initCommentComposerMedia === 'function') {
        window.initCommentComposerMedia({{ $id }});
    }
</script>
