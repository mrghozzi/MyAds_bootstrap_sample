@php
    $topicExcerpt = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $topic->txt))), 160);
    $categoryColor = $topic->category ? ($topic->category->color ?? '#615dfa') : '#615dfa';
    $commentsCount = (int) ($topic->comments_count ?? $topic->comments_count_calc ?? ($topic->reply ?? 0));
    $lastPoster = $topic->lastPoster ?? $topic->user;
    $lastActivityTime = $topic->last_posted_at 
        ? \Carbon\Carbon::createFromTimestamp((int) $topic->last_posted_at)->diffForHumans() 
        : ($topic->date ? \Carbon\Carbon::createFromTimestamp((int) $topic->date)->diffForHumans() : '');
@endphp

<article class="card border-0 shadow-sm mb-3 forum-modern-discussion-card {{ $topic->is_pinned ? 'is-pinned' : '' }}" data-topic-id="{{ $topic->id }}">
    <div class="card-body p-3 p-md-4">
        <div class="d-flex align-items-start gap-3">
            <!-- Author Avatar (Strictly Circular for Bootstrap Sample Theme) -->
            <div class="position-relative flex-shrink-0" style="width: 46px; height: 46px;">
                @if($topic->user)
                    <a href="{{ route('profile.short', $topic->user->publicRouteIdentifier()) }}" title="{{ $topic->user->username }}">
                        <img src="{{ $topic->user->avatarUrl() }}" alt="{{ $topic->user->username }}" class="rounded-circle border border-2 border-white shadow-sm" width="46" height="46" style="object-fit: cover;">
                    </a>
                    @if($topic->user->isOnline())
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 12px; height: 12px;" title="Online"></span>
                    @endif
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 46px; height: 46px;">
                        <i class="fa fa-user-slash text-muted small"></i>
                    </div>
                @endif
            </div>

            <!-- Discussion Content -->
            <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                    @if($topic->is_pinned)
                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small fw-bold">
                            <i class="fa fa-thumb-tack me-1"></i> {{ __('messages.pinned') }}
                        </span>
                    @endif
                    @if($topic->is_locked)
                        <span class="badge bg-danger rounded-pill px-2 py-1 small fw-bold">
                            <i class="fa fa-lock me-1"></i> {{ __('messages.locked') }}
                        </span>
                    @endif

                    @if($topic->category)
                        <a href="{{ route('forum.category', $topic->category->id) }}" class="badge rounded-pill text-white text-decoration-none px-3 py-1 small fw-bold" style="background-color: {{ $categoryColor }};">
                            <i class="fa {{ $topic->category->icons ?: 'fa-folder' }} me-1"></i>
                            {{ $topic->category->name }}
                        </a>
                    @endif
                </div>

                <h5 class="fw-bold mb-1">
                    <a href="{{ route('forum.topic', $topic->id) }}" class="text-dark text-decoration-none hover-primary">
                        {{ $topic->name }}
                    </a>
                </h5>

                @if($topicExcerpt !== '')
                    <p class="text-muted small mb-2 text-truncate" style="max-width: 750px;">
                        {{ $topicExcerpt }}
                    </p>
                @endif

                <div class="d-flex align-items-center flex-wrap gap-2 smaller text-muted">
                    @if($topic->user)
                        <span>
                            {{ __('messages.started_by') ?? 'Started by' }} 
                            <a href="{{ route('profile.short', $topic->user->publicRouteIdentifier()) }}" class="text-primary fw-bold text-decoration-none">
                                {{ $topic->user->username }}
                            </a>
                        </span>
                        <span>&bull;</span>
                    @endif

                    @if($commentsCount > 0 && $lastPoster)
                        <span>
                            {{ __('messages.last_reply_by') ?? 'Last reply by' }} 
                            <a href="{{ route('profile.short', $lastPoster->publicRouteIdentifier()) }}" class="text-dark fw-bold text-decoration-none">
                                {{ $lastPoster->username }}
                            </a>
                        </span>
                        <span>&bull;</span>
                        <time>{{ $lastActivityTime }}</time>
                    @else
                        <time>{{ $lastActivityTime }}</time>
                    @endif
                </div>
            </div>

            <!-- Replies Count Pill -->
            <div class="flex-shrink-0 text-center d-none d-sm-block">
                <a href="{{ route('forum.topic', $topic->id) }}" class="text-decoration-none">
                    <div class="bg-light p-2 rounded-4 border px-3">
                        <div class="fw-black text-primary fs-5">{{ number_format($commentsCount) }}</div>
                        <div class="smaller text-muted text-uppercase fw-bold">{{ __('messages.replies') }}</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</article>
