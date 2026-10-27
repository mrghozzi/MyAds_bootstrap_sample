@php
    $config = [];
    if (!empty($widget?->o_valuer)) {
        if (is_array($widget->o_valuer)) {
            $config = $widget->o_valuer;
        } else {
            $decoded = json_decode((string) $widget->o_valuer, true);
            $config = is_array($decoded) ? $decoded : [];
        }
    }

    $limit = (int) ($config['limit'] ?? 5);
    $categoryId = (int) ($config['category_id'] ?? 0);
    $showAuthor = isset($config['show_author']) ? (bool) $config['show_author'] : true;
    $showCategory = isset($config['show_category']) ? (bool) $config['show_category'] : true;
    $showDate = isset($config['show_date']) ? (bool) $config['show_date'] : true;
    $showButton = isset($config['show_button']) ? (bool) $config['show_button'] : true;
    $buttonText = !empty($config['button_text']) ? $config['button_text'] : __('messages.see_all');

    $query = \App\Models\ForumTopic::with(['user', 'category'])->latest('id');
    if ($categoryId > 0) {
        $query->where('cat', $categoryId);
    }
    $topics = $query->limit($limit)->get();
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
                    @if($showAuthor)
                        <a href="{{ $topicUserProfileUrl }}" class="position-relative">
                            <img src="{{ $topicUserAvatar }}" class="rounded-circle border" width="38" height="38" alt="{{ $topicUser?->username ?? '' }}">
                            @if($topicUserPresence == 'online')
                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                            @endif
                        </a>
                    @endif
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="mb-0 fw-bold small text-truncate">
                            <a href="{{ route('forum.topic', $topic->id) }}" class="text-dark text-decoration-none hover-primary">{{ $topic->name }}</a>
                        </h6>
                        <small class="text-muted smaller d-block text-truncate">
                            @if($showCategory)
                                {{ $topic->category?->name ?? __('messages.forum') }}
                            @endif
                            @if($showCategory && $showDate) &bull; @endif
                            @if($showDate)
                                {{ \Carbon\Carbon::createFromTimestamp($topic->date)->diffForHumans() }}
                            @endif
                        </small>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted small my-3">{{ __('messages.no_topics_found') }}</p>
            @endforelse
        </div>
        @if($showButton)
            <a href="{{ route('forum.index') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-3 fw-bold">
                {{ $buttonText }}
            </a>
        @endif
    </div>
</div>
