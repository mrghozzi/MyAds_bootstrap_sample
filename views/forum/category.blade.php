@extends('theme::layouts.master')
@include('theme::forum._assets')

@section('content')
@php
    $catColor = $category->color ?: '#615dfa';
@endphp

<div class="container py-4 forum-modern-forum-wrapper">
    <!-- Category Page Header -->
    <div class="card border-0 shadow-sm mb-4 text-white rounded-4 overflow-hidden" style="background: linear-gradient(135deg, {{ $catColor }}, #1bc8db);">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                    <i class="fa {{ $category->icons ?: 'fa-comments' }} fa-3x"></i>
                </div>
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="{{ route('forum.index') }}" class="text-white text-opacity-75 text-decoration-none small">{{ __('messages.forum') ?? 'Forum' }}</a></li>
                            @foreach($category->getAncestors() as $ancestor)
                                <li class="breadcrumb-item"><a href="{{ route('forum.category', $ancestor->id) }}" class="text-white text-opacity-75 text-decoration-none small">{{ $ancestor->name }}</a></li>
                            @endforeach
                            <li class="breadcrumb-item active text-white small" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="h2 fw-black mb-1 text-white">{{ $category->name }}</h1>
                    @if($category->txt)
                        <p class="mb-0 text-white-50 small" style="max-width: 650px;">{!! strip_tags($category->txt) !!}</p>
                    @endif
                </div>
            </div>
            @auth
                <button type="button" class="btn btn-light btn-lg fw-bold shadow-sm mt-3 mt-md-0 rounded-pill px-4" onclick="ForumComposer.open('topic', { catId: {{ $category->id }} })">
                    <i class="fa fa-pencil me-2 text-primary"></i> {{ __('messages.w_new_tpc') ?? 'Start a Discussion' }}
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg fw-bold shadow-sm mt-3 mt-md-0 rounded-pill px-4">
                    <i class="fa fa-sign-in me-2 text-primary"></i> {{ __('messages.login_to_participate') ?? 'Login to Participate' }}
                </a>
            @endauth
        </div>
    </div>

    @include('theme::partials.ads', ['id' => 4])

    <!-- Sub-Forums Grid (If Category Has Children) -->
    @if($category->children && $category->children->isNotEmpty())
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <i class="fa fa-folder-open text-primary"></i>
                    <span>{{ __('messages.sub_forums') ?? 'Sub-Forums' }}</span>
                </h6>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $category->children->count() }}</span>
            </div>
            <div class="card-body p-3">
                <div class="row g-3">
                    @foreach($category->children as $sub)
                        @php
                            $subColor = $sub->color ?: $catColor;
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('forum.category', $sub->id) }}" class="card h-100 border text-decoration-none rounded-4 p-3 hover-shadow transition-all bg-light bg-opacity-25">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 p-2 text-white flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: {{ $subColor }};">
                                        <i class="fa {{ $sub->icons ?: 'fa-comments' }} fs-5"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $sub->name }}</h6>
                                        <div class="smaller text-muted">
                                            <i class="fa fa-comments me-1"></i> {{ number_format($sub->topic_count ?? 0) }} {{ __('messages.topics') ?? 'Topics' }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Two-Pane Layout -->
    <div class="row g-4 mt-1">
        <!-- Left Sidebar -->
        <aside class="col-lg-3">
            @auth
                <div class="d-grid mb-3">
                    <button type="button" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm" onclick="ForumComposer.open('topic', { catId: {{ $category->id }} })">
                        <i class="fa fa-plus me-2"></i> {{ __('messages.w_new_tpc') ?? 'Start a Discussion' }}
                    </button>
                </div>
            @endauth

            <!-- Categories Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-dark">{{ __('messages.categories') ?? 'Categories' }}</h6>
                    <span class="badge bg-light text-muted border fw-bold rounded-pill px-2">{{ $categories->count() }}</span>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('forum.index') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 border-0">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-comments me-2 text-primary"></i>
                            <span class="small">{{ __('messages.all_discussions') ?? 'All Discussions' }}</span>
                        </div>
                        <span class="badge bg-light text-muted border rounded-pill">{{ number_format($forumStats['topics'] ?? 0) }}</span>
                    </a>
                    @foreach($categories as $cat)
                        @php
                            $cColor = $cat->color ?: '#615dfa';
                            $isActive = $category->id == $cat->id;
                            $hasChildren = $cat->children && $cat->children->isNotEmpty();
                            $isChildActive = $hasChildren && $cat->children->contains('id', $category->id);
                        @endphp
                        <a href="{{ route('forum.category', $cat->id) }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2 border-0 {{ $isActive ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}">
                            <div class="d-flex align-items-center overflow-hidden">
                                <span class="rounded-circle me-2 flex-shrink-0" style="width: 10px; height: 10px; background-color: {{ $cColor }};"></span>
                                <span class="small text-truncate">{{ $cat->name }}</span>
                            </div>
                            @if(isset($cat->topic_count))
                                <span class="badge bg-light text-muted border rounded-pill smaller">{{ number_format($cat->topic_count) }}</span>
                            @endif
                        </a>
                        @if($hasChildren)
                            @foreach($cat->children as $sub)
                                @php
                                    $subColor = $sub->color ?: $cColor;
                                    $isSubActive = $category->id == $sub->id;
                                @endphp
                                <a href="{{ route('forum.category', $sub->id) }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-1 ps-4 border-0 {{ $isSubActive ? 'bg-primary bg-opacity-10 text-primary fw-bold' : '' }}">
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <span class="text-muted me-1 smaller">&rdsh;</span>
                                        <span class="rounded-circle me-2 flex-shrink-0" style="width: 8px; height: 8px; background-color: {{ $subColor }};"></span>
                                        <span class="smaller text-truncate">{{ $sub->name }}</span>
                                    </div>
                                    @if(isset($sub->topic_count))
                                        <span class="badge bg-light text-muted border rounded-pill smaller">{{ number_format($sub->topic_count) }}</span>
                                    @endif
                                </a>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Community Stats Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-line-chart me-2 text-primary"></i> {{ __('messages.forum_statistics') ?? 'Community Stats' }}</h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="small text-muted"><i class="fa fa-folder-open me-2 text-primary"></i> {{ __('messages.cat_s') }}</span>
                        <strong class="text-dark">{{ $categories->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="small text-muted"><i class="fa fa-comment me-2 text-info"></i> {{ __('messages.topics') }}</span>
                        <strong class="text-dark">{{ number_format($forumStats['topics'] ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="small text-muted"><i class="fa fa-users me-2 text-success"></i> {{ __('messages.members') }}</span>
                        <strong class="text-dark">{{ number_format($forumStats['members'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Right Pane: Discussion Stream & Filter Toolbar -->
        <main class="col-lg-9">
            <!-- Filter & Search Toolbar -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <!-- Filter Tabs -->
                    <div class="btn-group rounded-pill p-1 bg-light border shadow-sm" role="group">
                        <a href="?filter=latest" class="btn btn-sm rounded-pill fw-bold px-3 forum-modern-filter-btn {{ ($currentFilter ?? 'latest') === 'latest' ? 'btn-primary' : 'btn-light' }}" data-filter="latest">
                            <i class="fa fa-clock-o me-1"></i> {{ __('messages.filter_latest') ?? 'Latest' }}
                        </a>
                        <a href="?filter=top" class="btn btn-sm rounded-pill fw-bold px-3 forum-modern-filter-btn {{ ($currentFilter ?? '') === 'top' ? 'btn-primary' : 'btn-light' }}" data-filter="top">
                            <i class="fa fa-fire me-1"></i> {{ __('messages.filter_top') ?? 'Top' }}
                        </a>
                        <a href="?filter=newest" class="btn btn-sm rounded-pill fw-bold px-3 forum-modern-filter-btn {{ ($currentFilter ?? '') === 'newest' ? 'btn-primary' : 'btn-light' }}" data-filter="newest">
                            <i class="fa fa-calendar me-1"></i> {{ __('messages.filter_newest') ?? 'Newest' }}
                        </a>
                        <a href="?filter=unanswered" class="btn btn-sm rounded-pill fw-bold px-3 forum-modern-filter-btn {{ ($currentFilter ?? '') === 'unanswered' ? 'btn-primary' : 'btn-light' }}" data-filter="unanswered">
                            <i class="fa fa-comment-o me-1"></i> {{ __('messages.filter_unanswered') ?? 'Unanswered' }}
                        </a>
                    </div>

                    <!-- Search Input -->
                    <div class="input-group" style="max-width: 280px;">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fa fa-search text-muted"></i></span>
                        <input type="search" id="forumSearchInput" class="form-control bg-light border-start-0 rounded-end-pill small" placeholder="{{ __('messages.search_forum') ?? 'Search in category...' }}" autocomplete="off">
                    </div>
                </div>
            </div>

            <!-- Discussion Stream Content -->
            <div id="forumStreamWrapper">
                @include('theme::forum.partials.discussion_stream', ['topics' => $topics])
            </div>
        </main>
    </div>

    <!-- Floating Forum Composer Drawer -->
    @include('theme::forum.partials.discussion_composer', ['categories' => $categories, 'selectedCategory' => $category])
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentFilter = '{{ $currentFilter ?? "latest" }}';
    let searchTimer = null;

    // Filter switching via Ajax
    document.querySelectorAll('.forum-modern-filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.forum-modern-filter-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-light');
            });
            this.classList.remove('btn-light');
            this.classList.add('btn-primary');
            currentFilter = this.dataset.filter;
            fetchDiscussions();
        });
    });

    // Live search debounced
    const searchInput = document.getElementById('forumSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                fetchDiscussions();
            }, 300);
        });
    }

    function fetchDiscussions() {
        const streamWrapper = document.getElementById('forumStreamWrapper');
        if (!streamWrapper) return;
        streamWrapper.style.opacity = '0.5';

        const query = searchInput ? searchInput.value.trim() : '';
        const params = new URLSearchParams();
        if (currentFilter) params.set('filter', currentFilter);
        if (query) params.set('q', query);

        const url = window.location.pathname + '?' + params.toString();

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            streamWrapper.innerHTML = data.html;
            streamWrapper.style.opacity = '1';
            window.history.pushState({}, '', url);
            bindLoadMore();
        })
        .catch(err => {
            streamWrapper.style.opacity = '1';
            console.error('Failed to load discussions:', err);
        });
    }

    function bindLoadMore() {
        const loadMoreBtn = document.getElementById('forumLoadMoreBtn');
        if (!loadMoreBtn) return;

        loadMoreBtn.addEventListener('click', function() {
            const nextUrl = this.dataset.nextUrl;
            if (!nextUrl) return;

            loadMoreBtn.disabled = true;
            loadMoreBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Loading...';

            fetch(nextUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                const newItems = tempDiv.querySelectorAll('.forum-modern-discussion-card');
                const list = document.getElementById('forumDiscussionList');
                newItems.forEach(item => list.appendChild(item));

                if (data.has_more) {
                    loadMoreBtn.dataset.nextUrl = data.next_page_url;
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = '<i class="fa fa-chevron-down me-1"></i> Load More Discussions';
                } else {
                    loadMoreBtn.remove();
                }
            })
            .catch(err => {
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = '<i class="fa fa-exclamation-triangle me-1"></i> Try Again';
            });
        });
    }

    bindLoadMore();
});
</script>
@endsection
