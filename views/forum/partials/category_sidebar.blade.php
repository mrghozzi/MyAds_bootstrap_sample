@if($sidebarCategories->isNotEmpty())
    <div class="card border-0 shadow-sm rounded-4 mb-4 forum-sidebar-card forum-category-board">
        <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">{{ __('messages.cat_s') }}</div>

        <div class="card-body p-4">
            <div class="forum-category-board-list">
                @foreach($sidebarCategories as $entry)
                    <article
                        class="forum-category-board-item{{ $entry['is_active'] ? ' is-active' : '' }}"
                        data-forum-category-id="{{ $entry['category']->id }}"
                        data-topic-count="{{ $entry['topic_count'] }}"
                    >
                        <div class="forum-category-board-header">
                            <a
                                class="forum-category-board-link"
                                href="{{ route('forum.category', $entry['category']->id) }}"
                                @if($entry['is_active']) aria-current="page" @endif
                            >
                                <span class="forum-category-board-icon">
                                    <i class="fa {{ $entry['category']->icons }}" aria-hidden="true"></i>
                                </span>

                                <span class="forum-category-board-copy">
                                    <strong>{{ $entry['category']->name }}</strong>

                                    @if($entry['description'] !== '')
                                        <span>{{ $entry['description'] }}</span>
                                    @endif
                                </span>
                            </a>

                            <span class="forum-category-board-count">{{ $entry['topic_count'] }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endif
