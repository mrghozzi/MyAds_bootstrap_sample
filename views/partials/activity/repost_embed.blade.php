@php
    $repost = $activity->repostRecord;
    $original = $repost?->originalStatus;
    $originalUser = $original?->user;
    $originalUserProfileUrl = $originalUser ? route('profile.show', $originalUser->username) : '#';
    $originalUserName = $originalUser?->username ?? __('messages.unknown_user');
    $originalUserAvatar = $originalUser ? $originalUser->avatarUrl() : asset('upload/_avatar.png');
    $originalUserPresence = $originalUser?->isOnline() ? 'online' : 'offline';
    $originalUrl = null;
    $originalTitle = null;
    $originalMeta = null;
    $originalBody = '';

    if ($original && $original->related_content) {
        $originalUrl = match ((int) $original->s_type) {
            1 => route('directory.show', $original->tp_id),
            7867 => route('store.show', $original->related_content->name),
            205 => route('kb.show', ['name' => $original->related_content->o_mode, 'article' => $original->related_content->name]),
            5 => route('news.show', $original->tp_id),
            default => route('forum.topic', $original->tp_id),
        };

        $originalTitle = match ((int) $original->s_type) {
            1, 2, 5, 7867, 205 => $original->related_content->name ?? null,
            default => null,
        };

        $originalMeta = match ((int) $original->s_type) {
            1 => parse_url($original->related_content->url ?? '', PHP_URL_HOST) ?: null,
            2 => $original->related_content->category->name ?? null,
            5 => __('messages.news'),
            7867 => ($original->related_content->type->name ?? null),
            205 => optional($original->related_content->productItem)->name,
            default => null,
        };

        $bodySource = match ((int) $original->s_type) {
            7867 => $original->related_content->o_valuer ?? '',
            205 => $original->related_content->o_valuer ?? '',
            5 => $original->related_content->text ?? '',
            default => $original->related_content->txt ?? '',
        };

        $originalBody = \App\Support\ContentFormatter::format($bodySource);
    }
@endphp

@if($original && $original->related_content)
    <div class="card border border-light-subtle rounded-4 bg-light bg-opacity-25 mt-3 overflow-hidden shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ $originalUserProfileUrl }}" class="me-2 position-relative">
                    <img src="{{ $originalUserAvatar }}" class="rounded-circle border border-white shadow-sm" width="36" height="36" alt="{{ $originalUserName }}">
                    @if($originalUserPresence == 'online')
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                    @endif
                </a>
                <div>
                    <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">
                        <a href="{{ $originalUserProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $originalUserName }}</a>
                    </h6>
                    <small class="text-muted smaller fw-bold"><i class="fa-regular fa-clock me-1 opacity-50"></i> {{ $original->date_formatted }}</small>
                </div>
            </div>

            @if($originalTitle || $originalMeta)
                <div class="mb-2">
                    @if($originalTitle)
                        <h6 class="fw-bold mb-1">
                            <a href="{{ $originalUrl }}" class="text-dark text-decoration-none hover-primary">{{ $originalTitle }}</a>
                        </h6>
                    @endif
                    @if($originalMeta)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 fw-semibold" style="font-size: 0.75rem;">{{ $originalMeta }}</span>
                    @endif
                </div>
            @endif

            @if(trim(strip_tags($originalBody)) !== '')
                <div class="card-text text-secondary small mb-3 lh-lg">
                    {!! $originalBody !!}
                </div>
            @endif

            @if($original->linkPreviewRecord)
                @include('theme::partials.activity.link_preview', ['activity' => $original])
            @endif

            @if((int) $original->s_type === 4)
                @include('theme::partials.activity.gallery', ['activity' => $original])
            @endif
        </div>
    </div>
@endif
