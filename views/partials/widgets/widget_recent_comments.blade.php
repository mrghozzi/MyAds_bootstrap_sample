<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @php
                // Fetch recent comments joined with user and topic, ordered by id DESC
                $recentComments = \App\Models\ForumComment::with(['user', 'topic'])
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            @if($recentComments->isEmpty())
                <p class="text-center text-muted mb-0"><small>{{ __('messages.no_comments_found') ?? 'No comments found.' }}</small></p>
            @else
                @foreach($recentComments as $comment)
                    @php
                        $user = $comment->user;
                        if (!$user) continue;
                        $topic = $comment->topic;
                        $topicUrl = $topic ? route('forum.topic', $topic->id) . '#comment-' . $comment->id : '#';
                        $snippet = strip_tags(preg_replace('/\[.*?\]/', '', $comment->txt));
                    @endphp
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('profile.short', $user->publicRouteIdentifier()) }}" class="position-relative">
                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}" class="rounded-circle border" style="width: 48px; height: 48px; object-fit: cover;">
                            @if($user->isOnline())
                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle">
                                    <span class="visually-hidden">Online</span>
                                </span>
                            @endif
                        </a>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <a href="{{ route('profile.short', $user->publicRouteIdentifier()) }}" class="text-dark fw-bold text-decoration-none text-truncate d-block">
                                    {{ Str::limit($user->username, 15) }}
                                    @if($user->hasVerifiedBadge())
                                        <i class="fa fa-check-circle text-primary small ms-1" title="Verified"></i>
                                    @endif
                                </a>
                                <div class="text-muted smaller timestamp text-nowrap">
                                    {{ \Carbon\Carbon::createFromTimestamp($comment->date)->diffForHumans() }}
                                </div>
                            </div>
                            <div class="smaller fw-bold text-truncate">
                                <a href="{{ $topicUrl }}" class="text-muted text-decoration-none">
                                    &quot;{{ Str::limit($snippet, 35) }}&quot;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
