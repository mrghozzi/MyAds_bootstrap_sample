@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-info bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                    <i class="fa fa-sitemap fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-bold mb-1">{{ __('messages.directory') }}</h1>
                    <p class="mb-0 text-white-50 small">{{ __('messages.landing_community_directory_desc') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('directory.create') }}" class="btn btn-light btn-lg fw-bold shadow-sm rounded-pill px-4">
                    <i class="fa fa-plus me-2 text-info"></i> {{ __('messages.addWebsite') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Left -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.categories') }}</h6>
                </div>
                <div class="card-body p-0">
                    @include('theme::directory.partials.category_board', ['categoryBoard' => $categoryBoard])
                </div>
            </div>
            <x-widget-column side="directory_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-6">
            <!-- Search & Filters Control Center -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <!-- Live Search -->
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" id="directory-search-input" class="form-control bg-light border-start-0 rounded-end-pill py-2" placeholder="{{ __('messages.directory_search_placeholder') ?? 'Search websites, tags, or domains...' }}" value="{{ $filters['search'] ?? '' }}">
                        </div>
                    </div>

                    <!-- Sort Nav Tabs -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button type="button" class="btn btn-sm {{ ($filters['sort'] ?? 'latest') === 'trending' ? 'btn-warning' : 'btn-outline-secondary' }} rounded-pill dir-sort-btn" data-sort="trending">
                            <i class="fa fa-fire me-1"></i> {{ __('messages.trending') ?? 'Trending' }}
                        </button>
                        <button type="button" class="btn btn-sm {{ ($filters['sort'] ?? 'latest') === 'latest' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill dir-sort-btn" data-sort="latest">
                            <i class="fa fa-clock-o me-1"></i> {{ __('messages.latest') ?? 'Latest' }}
                        </button>
                        <button type="button" class="btn btn-sm {{ ($filters['sort'] ?? 'latest') === 'popular' ? 'btn-info text-white' : 'btn-outline-secondary' }} rounded-pill dir-sort-btn" data-sort="popular">
                            <i class="fa fa-star me-1"></i> {{ __('messages.most_visited') ?? 'Most Visited' }}
                        </button>
                        <button type="button" class="btn btn-sm {{ ($filters['sort'] ?? 'latest') === 'featured' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill dir-sort-btn" data-sort="featured">
                            <i class="fa fa-gem me-1"></i> {{ __('messages.featured') ?? 'Featured' }}
                        </button>
                    </div>

                    <!-- Pricing Chips -->
                    <div class="d-flex flex-wrap align-items-center gap-1 border-top pt-3">
                        <small class="text-muted fw-bold me-2"><i class="fa fa-tags me-1"></i> {{ __('messages.pricing') ?? 'Pricing' }}:</small>
                        <button type="button" class="btn btn-xs rounded-pill {{ empty($filters['pricing']) || $filters['pricing'] === 'all' ? 'btn-dark' : 'btn-light border' }} dir-pricing-btn" data-pricing="">
                            {{ __('messages.all') ?? 'All' }}
                        </button>
                        <button type="button" class="btn btn-xs rounded-pill {{ ($filters['pricing'] ?? '') === 'free' ? 'btn-success' : 'btn-light border' }} dir-pricing-btn" data-pricing="free">
                            {{ __('messages.pricing_free') ?? 'Free' }}
                        </button>
                        <button type="button" class="btn btn-xs rounded-pill {{ ($filters['pricing'] ?? '') === 'freemium' ? 'btn-primary' : 'btn-light border' }} dir-pricing-btn" data-pricing="freemium">
                            {{ __('messages.pricing_freemium') ?? 'Freemium' }}
                        </button>
                        <button type="button" class="btn btn-xs rounded-pill {{ ($filters['pricing'] ?? '') === 'paid' ? 'btn-danger' : 'btn-light border' }} dir-pricing-btn" data-pricing="paid">
                            {{ __('messages.pricing_paid') ?? 'Paid' }}
                        </button>
                        <button type="button" class="btn btn-xs rounded-pill {{ ($filters['pricing'] ?? '') === 'open_source' ? 'btn-secondary' : 'btn-light border' }} dir-pricing-btn" data-pricing="open_source">
                            {{ __('messages.pricing_open_source') ?? 'Open Source' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Featured Sites Showcase -->
            @if(isset($featuredSites) && $featuredSites->isNotEmpty() && empty($filters['search']))
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h6 class="fw-bold mb-0 text-warning"><i class="fa fa-trophy me-2"></i> {{ __('messages.featured_sites_of_week') ?? 'Featured Sites of the Week' }}</h6>
                    </div>
                    <div class="card-body p-3 pt-0">
                        <div class="d-flex gap-3 overflow-auto pb-2">
                            @foreach($featuredSites as $featuredSite)
                                <a href="{{ route('directory.show', $featuredSite->id) }}" class="card text-decoration-none border shadow-xs rounded-3 flex-shrink-0" style="width: 140px;">
                                    <img src="{{ $featuredSite->prominent_image ?: theme_asset('img/dir_image.png') }}" class="card-img-top object-fit-cover" style="height: 70px;" alt="{{ $featuredSite->name }}">
                                    <div class="card-body p-2 text-center">
                                        <p class="small fw-bold text-dark mb-1 text-truncate">{{ $featuredSite->name }}</p>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill smaller">▲ {{ $featuredSite->upvotes_count }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div id="infinite-scroll-container">
                <div id="timeline-content">
                    @if($cards->isNotEmpty())
                        @include('theme::directory.partials.feed_items', ['cards' => $cards])
                    @else
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-5 text-center text-muted">
                                <i class="fa fa-search fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">{{ __('messages.no_listings_found') }}</p>
                            </div>
                        </div>
                    @endif
                    @include('theme::partials.ajax.infinite_scroll', ['paginator' => $activities])
                </div>
            </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.details') }}</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>{{ __('messages.latest_sites') }}</span>
                            <strong class="text-primary">{{ $directoryStats['listing_count'] }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>{{ __('messages.cat_s') }}</span>
                            <strong class="text-primary">{{ $directoryStats['category_count'] }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>{{ __('messages.subcategories') }}</span>
                            <strong class="text-primary">{{ $directoryStats['subcategory_count'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <x-widget-column side="directory_right" />
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Upvote Button Handler via delegation
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-directory-upvote]');
        if (!btn || btn.dataset.loading === '1') return;

        e.preventDefault();
        const listingId = btn.getAttribute('data-directory-upvote');
        if (!listingId) return;

        btn.dataset.loading = '1';

        fetch('/directory/' + listingId + '/upvote', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (res.status === 401) {
                window.location.href = '/login';
                return null;
            }
            return res.json();
        })
        .then(data => {
            btn.dataset.loading = '0';
            if (!data || !data.success) return;

            // Toggle styling
            if (data.upvoted) {
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-primary', 'is-upvoted');
            } else {
                btn.classList.remove('btn-primary', 'is-upvoted');
                btn.classList.add('btn-outline-primary');
            }

            // Update upvote counter in button
            const countEl = btn.querySelector('.directory-upvote-count');
            if (countEl) {
                countEl.textContent = data.upvotes_count;
            }

            // Update footer stat inside card
            const card = btn.closest('.directory-listing-card');
            if (card) {
                const statVal = card.querySelector('.upvote-stat-val');
                if (statVal) statVal.textContent = data.upvotes_count;
            }
        })
        .catch(err => {
            btn.dataset.loading = '0';
            console.error('Upvote error:', err);
        });
    });

    // Filter updates
    function updateUrlFilter(param, value) {
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(param, value);
        } else {
            url.searchParams.delete(param);
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    // Sort buttons
    document.querySelectorAll('.dir-sort-btn').forEach(b => {
        b.addEventListener('click', function() {
            updateUrlFilter('sort', this.dataset.sort);
        });
    });

    // Pricing buttons
    document.querySelectorAll('.dir-pricing-btn').forEach(b => {
        b.addEventListener('click', function() {
            updateUrlFilter('pricing', this.dataset.pricing);
        });
    });

    // Search input debounced
    const searchInput = document.getElementById('directory-search-input');
    if (searchInput) {
        let searchTimer = null;
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                updateUrlFilter('search', this.value.trim());
            }
        });
    }
});
</script>
@endpush
