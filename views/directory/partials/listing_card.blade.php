@php
    $listing = $card['listing'];
    $activity = $card['activity'];
@endphp

<article class="card border-0 shadow-sm rounded-4 mb-4 p-0 directory-listing-card post{{ $activity?->id ?? $listing->id }} overflow-hidden">
    <div class="position-absolute top-0 end-0 p-3 z-3 bg-white bg-opacity-75 rounded-bottom-start-4 shadow-sm">
        @include('theme::directory.partials.site_action_menu', ['card' => $card])
    </div>

    <div class="card-body p-4">
        <!-- Header: User Info & Upvote Button -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div class="d-flex align-items-center">
                @if($card['owner'])
                    <a href="{{ $card['owner_url'] }}" class="text-decoration-none me-3">
                        <img src="{{ $card['owner_avatar'] }}" class="rounded-circle shadow-sm border border-2" width="48" height="48" alt="{{ $card['owner_name'] }}" style="border-color: {{ $card['owner']->profileBadgeColor() ?: '#dee2e6' }} !important; object-fit: cover;">
                    </a>
                @else
                    <div class="me-3">
                        <img src="{{ $card['owner_avatar'] }}" class="rounded-circle shadow-sm border border-2 opacity-50" width="48" height="48" alt="{{ $card['owner_name'] }}" style="object-fit: cover;">
                    </div>
                @endif

                <div>
                    <p class="mb-0 fw-bold">
                        @if($card['owner_url'])
                            <a href="{{ $card['owner_url'] }}" class="text-dark text-decoration-none">{{ $card['owner_name'] }}</a>
                        @else
                            <span class="text-dark">{{ $card['owner_name'] }}</span>
                        @endif
                    </p>
                    <small class="text-muted">
                        <i class="fa fa-clock-o me-1"></i>{{ $card['published_diff'] }}
                    </small>
                </div>
            </div>

            <!-- Upvote Button -->
            <div>
                <button type="button" 
                        class="btn btn-sm {{ $card['has_upvoted'] ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-3 py-1 directory-upvote-btn {{ $card['has_upvoted'] ? 'is-upvoted' : '' }}" 
                        data-directory-upvote="{{ $listing->id }}"
                        @guest onclick="window.location='{{ route('login') }}'" @endguest
                        aria-label="{{ __('messages.upvote') ?? 'Upvote' }}">
                    <span class="me-1">▲</span>
                    <span class="directory-upvote-count fw-bold" data-count="{{ $card['upvotes_count'] }}">{{ $card['upvotes_count'] }}</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4 align-items-center">
            <!-- Image / Media -->
            <div class="col-md-5 col-lg-4">
                @once
                    @include('theme::partials.directory.lazy_image_script')
                @endonce
                <a href="{{ $card['visit_url'] }}" target="_blank" rel="noopener" class="d-block rounded-4 overflow-hidden shadow-sm position-relative ratio ratio-16x9 bg-light">
                    <img src="{{ $card['listing']->prominent_image ?: theme_asset('img/dir_image.png') }}" data-lazy-fetch-url="{{ route('directory.image.fetch', $card['listing']->id) }}" alt="{{ $card['title'] }}" class="object-fit-cover w-100 h-100 position-absolute top-0 start-0 transition-all hover-scale">
                </a>
            </div>

            <!-- Details -->
            <div class="col-md-7 col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge bg-light text-secondary rounded-pill border"><i class="fa fa-globe me-1"></i> {{ $card['display_domain'] }}</span>
                    @if($card['is_verified'])
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill border border-success border-opacity-25" title="{{ __('messages.verified_site') ?? 'Verified Site' }}">
                            <i class="fa fa-check-circle me-1"></i> {{ __('messages.verified_site') ?? 'Verified' }}
                        </span>
                    @endif
                    @if($card['is_featured'])
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill border border-warning border-opacity-25" title="{{ __('messages.featured_site') ?? 'Featured Site' }}">
                            <i class="fa fa-star me-1"></i> {{ __('messages.featured_site') ?? 'Featured' }}
                        </span>
                    @endif
                    @if($card['category_url'])
                        <a href="{{ $card['category_url'] }}" class="badge bg-info bg-opacity-10 text-info text-decoration-none rounded-pill border border-info border-opacity-25 hover-bg-info hover-text-white transition-all">
                            <i class="fa fa-folder-open me-1"></i> {{ $card['category_name'] }}
                        </a>
                    @endif
                    <span class="badge rounded-pill {{ $card['pricing_type'] === 'free' ? 'bg-success' : ($card['pricing_type'] === 'paid' ? 'bg-danger' : ($card['pricing_type'] === 'open_source' ? 'bg-secondary' : 'bg-primary')) }}">
                        {{ $card['pricing_label'] }}
                    </span>
                </div>

                <h3 class="h4 fw-bold mb-2">
                    <a href="{{ $card['detail_url'] }}" class="text-dark text-decoration-none hover-text-primary transition-all">{{ $card['title'] }}</a>
                </h3>

                @if($card['excerpt'])
                    <p class="text-muted mb-3" dir="auto" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $card['excerpt'] }}</p>
                @endif

                @if(!empty($card['features']))
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach(array_slice($card['features'], 0, 3) as $feature)
                            <span class="badge bg-light text-dark rounded-pill px-2 py-1 border shadow-xs">
                                <i class="fa fa-check text-success me-1"></i> {{ $feature }}
                            </span>
                        @endforeach
                    </div>
                @endif

                @if(!empty($card['tags']))
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        @foreach(array_slice($card['tags'], 0, 4) as $tag)
                            <span class="badge bg-light text-muted rounded-pill px-2 py-1 border">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ $card['visit_url'] }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                        <span>{{ __('messages.visit_site') }}</span> <i class="fa fa-external-link"></i>
                    </a>
                    
                    <a href="{{ $card['detail_url'] }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold shadow-sm text-dark border">
                        {{ __('messages.details') }}
                    </a>
                </div>
            </div>
        </div>

        <div id="report{{ $listing->id }}" class="mt-3"></div>
        <div id="notif{{ $listing->id }}"></div>
    </div>

    <!-- Footer Stats & Actions -->
    <div class="card-footer bg-light border-top-0 p-3 d-flex justify-content-between align-items-center flex-wrap gap-2 rounded-bottom-4">
        <!-- Stats -->
        <div class="d-flex gap-3 text-muted small fw-bold">
            <span class="d-flex align-items-center gap-1 text-primary"><i class="fa fa-arrow-up"></i> <strong class="upvote-stat-val">{{ $card['upvotes_count'] }}</strong></span>
            <span class="d-flex align-items-center gap-1"><i class="fa fa-eye"></i> {{ $card['views'] }}</span>
            <span class="d-flex align-items-center gap-1 text-warning"><i class="fa fa-bolt"></i> {{ $card['reactions_count'] }}</span>
            <span class="d-flex align-items-center gap-1 text-info"><i class="fa fa-comments"></i> {{ $card['comments_count'] }}</span>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2">
            @include('theme::directory.partials.reaction_button', ['card' => $card])

            @auth
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle shadow-sm action-btn" data-directory-comment-toggle="{{ $listing->id }}" title="{{ __('messages.comment') }}" style="width: 36px; height: 36px; padding: 0;">
                    <i class="fa fa-comment"></i>
                </button>
            @endauth

            @include('theme::directory.partials.share_menu', ['shareUrl' => $card['detail_url'], 'shareTitle' => $card['title']])
        </div>
    </div>

    <div class="post-comment-list post-comment-list-{{ $listing->id }}"></div>
</article>

<style>
    .hover-scale { transition: transform 0.3s ease; }
    .hover-scale:hover { transform: scale(1.05); }
    .hover-bg-info:hover { background-color: #0dcaf0 !important; }
    .hover-text-white:hover { color: #fff !important; }
    .hover-text-primary:hover { color: #0d6efd !important; }
    .transition-all { transition: all 0.3s ease; }
    .action-btn { display: inline-flex; align-items: center; justify-content: center; }
    .action-btn:hover { background-color: #e9ecef; color: #495057; }
</style>
