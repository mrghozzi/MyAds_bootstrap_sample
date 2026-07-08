@php
    $listing = $card['listing'];
    $activity = $card['activity'];
@endphp

<article class="card border-0 shadow-sm rounded-4 mb-4 p-0 directory-listing-card post{{ $activity?->id ?? $listing->id }} overflow-hidden">
    <div class="position-absolute top-0 end-0 p-3 z-3 bg-white bg-opacity-75 rounded-bottom-start-4 shadow-sm">
        @include('theme::directory.partials.site_action_menu', ['card' => $card])
    </div>

    <div class="card-body p-4">
        <!-- Header: User Info -->
        <div class="d-flex align-items-center mb-4">
            @if($card['owner'])
                <a href="{{ $card['owner_url'] }}" class="text-decoration-none me-3">
                    <img src="{{ $card['owner_avatar'] }}" class="rounded-circle shadow-sm border border-2" width="48" height="48" alt="{{ $card['owner_name'] }}" style="border-color: {{ $card['owner']->profileBadgeColor() ?: '#dee2e6' }} !important;">
                </a>
            @else
                <div class="me-3">
                    <img src="{{ $card['owner_avatar'] }}" class="rounded-circle shadow-sm border border-2 opacity-50" width="48" height="48" alt="{{ $card['owner_name'] }}">
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
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-light text-secondary rounded-pill border"><i class="fa fa-globe me-1"></i> {{ $card['display_domain'] }}</span>
                    @if($card['category_url'])
                        <a href="{{ $card['category_url'] }}" class="badge bg-info bg-opacity-10 text-info text-decoration-none rounded-pill border border-info border-opacity-25 hover-bg-info hover-text-white transition-all">
                            <i class="fa fa-folder-open me-1"></i> {{ $card['category_name'] }}
                        </a>
                    @endif
                </div>

                <h3 class="h4 fw-bold mb-3">
                    <a href="{{ $card['detail_url'] }}" class="text-dark text-decoration-none hover-text-primary transition-all">{{ $card['title'] }}</a>
                </h3>

                @if($card['excerpt'])
                    <p class="text-muted mb-4" dir="auto" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ $card['excerpt'] }}</p>
                @endif

                @if(!empty($card['tags']))
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach(array_slice($card['tags'], 0, 4) as $tag)
                            <span class="badge bg-light text-muted rounded-pill px-3 py-2 border shadow-sm">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <a href="{{ $card['visit_url'] }}" target="_blank" rel="noopener" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <span>{{ __('messages.visit_site') }}</span> <i class="fa fa-external-link"></i>
                    </a>
                    
                    <a href="{{ $card['detail_url'] }}" class="btn btn-light btn-lg rounded-pill px-4 fw-bold shadow-sm text-dark border">
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
        <div class="d-flex gap-4 text-muted small fw-bold">
            <span class="d-flex align-items-center gap-2"><i class="fa fa-eye fs-5 opacity-75"></i> {{ $card['views'] }}</span>
            <span class="d-flex align-items-center gap-2 text-warning"><i class="fa fa-bolt fs-5 opacity-75"></i> {{ $card['reactions_count'] }}</span>
            <span class="d-flex align-items-center gap-2 text-info"><i class="fa fa-comments fs-5 opacity-75"></i> {{ $card['comments_count'] }}</span>
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
