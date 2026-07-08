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
                                <li><button class="dropdown-item py-2 small fw-bold text-danger" onclick="deletePost({{ $topic->id }}, 2)"><i class="fa fa-trash me-2"></i> {{ __('messages.delete') }}</button></li>
                                @if($status->s_type == 4)
                                    <li><button class="dropdown-item py-2 small fw-bold text-danger" onclick="clearGallery({{ $topic->id }})"><i class="fa fa-images me-2"></i> {{ __('messages.clear_gallery') }}</button></li>
                                @endif
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

                    <!-- Main Image Preview -->
                    @php
                        $imageOption = \App\Models\Option::where('o_parent', $topic->id)->where('o_type', 'image_post')->first();
                        $imageUrl = $imageOption ? $imageOption->o_valuer : '';
                    @endphp
                    @if($imageUrl)
                        <div class="mb-4 text-center">
                            <a href="{{ asset($imageUrl) }}" target="_blank">
                                <img src="{{ asset($imageUrl) }}" class="img-fluid rounded-4 shadow-sm border p-1 bg-white" style="max-height: 500px; object-fit: contain;">
                            </a>
                        </div>
                    @endif

                    <!-- Gallery Attachments Grid -->
                    @if($topic->attachments->isNotEmpty() || $canEditTopic)
                        <div class="picture-item-grid row g-3 mb-4" id="gallery-sortable-grid">
                            @foreach($topic->attachments->sortBy('sort_order') as $attachment)
                                @if($attachment->isImage())
                                    <div class="col-6 col-md-3 attachment-item" data-id="{{ $attachment->id }}" id="attachment-{{ $attachment->id }}">
                                        <div class="position-relative rounded-4 overflow-hidden shadow-sm border ratio ratio-1x1 bg-light cursor-move">
                                            <img src="{{ asset($attachment->file_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $attachment->original_name }}">
                                            @if($canEditTopic)
                                                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 shadow-sm d-flex align-items-center justify-content-center" onclick="deleteGalleryImage({{ $attachment->id }}, this)" style="width: 28px; height: 28px; padding: 0; z-index: 10;">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12" id="attachment-{{ $attachment->id }}">
                                        <div class="card bg-light border border-dashed rounded-4 p-3 d-flex flex-row justify-content-between align-items-center">
                                            <a href="{{ route('forum.attachment.download', $attachment->id) }}" class="text-decoration-none text-primary fw-bold">
                                                <i class="fa fa-paperclip me-2 text-muted"></i>{{ $attachment->original_name }}
                                                <span class="text-muted smaller fw-normal">({{ $attachment->human_size }})</span>
                                            </a>
                                            @if($canEditTopic)
                                                <button type="button" class="btn btn-outline-danger btn-sm border-0 rounded-circle" onclick="deleteGalleryImage({{ $attachment->id }}, this)"><i class="fa fa-times"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if($canEditTopic && $topic->attachments->count() < 10)
                                <div class="col-6 col-md-3">
                                    <label for="add-photos-input" class="ratio ratio-1x1 border border-2 border-dashed rounded-4 d-flex flex-column align-items-center justify-content-center cursor-pointer bg-light hover-bg-light-dark text-muted transition-all" style="height: 100%;">
                                        <div class="text-center p-3">
                                            <i class="fa fa-plus fa-2x mb-2 text-muted opacity-50"></i>
                                            <span class="d-block small fw-bold">{{ __('messages.add_photos') ?? 'Add Photos' }}</span>
                                            <span class="d-block smaller text-muted opacity-75">({{ $topic->attachments->count() }}/10)</span>
                                        </div>
                                    </label>
                                    <input type="file" id="add-photos-input" multiple accept="image/*" class="d-none" onchange="uploadGalleryImages(this, {{ $topic->id }})">
                                </div>
                            @endif
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
                <div class="post-comment-list post-comment-list-{{ $topic->id }} comment_4_{{ $topic->id }}">
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

<!-- GALLERY MANAGEMENT SCRIPTS -->
<script>
    function deleteGalleryImage(attachmentId, btn) {
        if (!confirm('{{ __("messages.confirm_delete_image") }}')) return;

        const icon = btn.querySelector('i') || btn;
        const originalClass = icon.className;
        icon.className = 'fa fa-spinner fa-spin';

        fetch('{{ route("status.gallery.delete_image", ":id") }}'.replace(':id', attachmentId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error deleting image');
                icon.className = originalClass;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting image');
            icon.className = originalClass;
        });
    }

    function clearGallery(topicId) {
        if (!confirm('{{ __("messages.confirm_clear_gallery") }}')) return;

        fetch('{{ route("status.gallery.clear", ":id") }}'.replace(':id', topicId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error clearing gallery');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error clearing gallery');
        });
    }

    function uploadGalleryImages(input, topicId) {
        if (!input.files || input.files.length === 0) return;

        const formData = new FormData();
        for (let i = 0; i < input.files.length; i++) {
            formData.append('images[]', input.files[i]);
        }

        const label = document.querySelector('label[for="add-photos-input"]');
        const originalHTML = label.innerHTML;
        label.innerHTML = '<div class="text-center p-3"><i class="fa fa-spinner fa-spin fa-2x mb-2"></i><span class="d-block small">Uploading...</span></div>';

        fetch('{{ route("status.gallery.add_images", ":id") }}'.replace(':id', topicId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error uploading images');
                label.innerHTML = originalHTML;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error uploading images');
            label.innerHTML = originalHTML;
        });
    }

    // REORDERING LOGIC
    function initGallerySortable() {
        if (typeof jQuery === 'undefined' || typeof jQuery.fn.sortable === 'undefined') {
            return;
        }

        const grid = jQuery('#gallery-sortable-grid');
        if (grid.length === 0) return;

        grid.sortable({
            items: '.attachment-item',
            placeholder: 'col-6 col-md-3 border border-2 border-dashed rounded-4 bg-light bg-opacity-25',
            update: function(event, ui) {
                const order = [];
                grid.find('.attachment-item').each(function() {
                    order.push(jQuery(this).data('id'));
                });

                fetch('{{ route("status.gallery.reorder", $topic->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Error reordering images');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if($canEditTopic)
            initGallerySortable();
        @endif
    });
</script>

@if($canEditTopic)
    <script src="{{ theme_asset('admin-duralux/vendors/js/jquery-ui.min.js') }}"></script>
@endif

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.85rem; }
    .cursor-pointer { cursor: pointer; }
    .cursor-move { cursor: move; }
    .hover-bg-light-dark:hover { background-color: #f1f2f4 !important; }
    .hover-scale:hover { transform: scale(1.15); }
    .hover-primary:hover { color: #615dfa !important; }
    .transition-all { transition: all 0.2s ease-in-out; }
</style>
@endsection
