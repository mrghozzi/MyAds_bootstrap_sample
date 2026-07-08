@php
    $topics = \App\Models\ForumTopic::with(['user', 'category'])->latest('id')->limit(5)->get();
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @forelse($topics as $topic)
                @php
                    $topicUser = $topic->user;
                    $topicUserProfileUrl = $topicUser ? route('profile.short', $topicUser->publicRouteIdentifier()) : '#';
                    $topicUserAvatar = $topicUser ? $topicUser->avatarUrl() : asset('upload/_avatar.png');
                    $topicUserPresence = $topicUser?->isOnline() ? 'online' : 'offline';
                @endphp
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ $topicUserProfileUrl }}" class="position-relative">
                        <img src="{{ $topicUserAvatar }}" class="rounded-circle border" width="38" height="38" alt="{{ $topicUser?->username ?? '' }}">
                        @if($topicUserPresence == 'online')
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                        @endif
                    </a>
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="mb-0 fw-bold small text-truncate">
                            <a href="{{ route('forum.topic', $topic->id) }}" class="text-dark text-decoration-none hover-primary">{{ $topic->name }}</a>
                        </h6>
                        <small class="text-muted smaller d-block text-truncate">
                            {{ $topic->category?->name ?? __('messages.forum') }} &bull; {{ \Carbon\Carbon::createFromTimestamp($topic->date)->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted small my-3">{{ __('messages.no_topics_found') }}</p>
            @endforelse
        </div>
        <a href="{{ route('forum.index') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-3 fw-bold">
            {{ __('messages.see_all') }}
        </a>
    </div>
</div>
