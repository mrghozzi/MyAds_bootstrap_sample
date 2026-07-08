<section class="card border-0 shadow-sm rounded-4 mb-4 position-relative directory-detail-shell post{{ $card['listing']->id }}">
    <div class="position-absolute top-0 end-0 p-3 z-3">
        @include('theme::directory.partials.site_action_menu', ['card' => $card, 'activity' => $activity ?? null])
    </div>

    <div class="card-body p-4 p-md-5">
        <div class="directory-detail-hero">
            @once
                @include('theme::partials.directory.lazy_image_script')
            @endonce
            <div class="directory-detail-brand">
                <div class="directory-detail-media">
                    <img src="{{ $card['listing']->prominent_image ?: theme_asset('img/dir_image.png') }}" data-lazy-fetch-url="{{ route('directory.image.fetch', $card['listing']->id) }}" alt="{{ $card['title'] }}">
                </div>

                <div class="directory-detail-copy">
                    <p class="directory-listing-domain text-muted mb-1">{{ $card['display_domain'] }}</p>
                    <h2 class="directory-detail-title h3 fw-bold mb-3">{{ $card['title'] }}</h2>

                    <div class="directory-detail-meta d-flex flex-wrap gap-2">
                        @if($card['category_url'])
                            <a class="badge bg-light text-primary text-decoration-none border rounded-pill py-2 px-3" href="{{ $card['category_url'] }}">
                                <i class="fa fa-folder-open me-1" aria-hidden="true"></i>
                                {{ $card['category_name'] }}
                            </a>
                        @endif

                        <span class="badge bg-light text-secondary border rounded-pill py-2 px-3">
                            <i class="fa fa-clock-o me-1" aria-hidden="true"></i>
                            {{ $card['published_diff'] }}
                        </span>

                        <span class="badge bg-light text-secondary border rounded-pill py-2 px-3">
                            <i class="fa fa-eye me-1" aria-hidden="true"></i>
                            {{ $card['views'] }} {{ __('messages.visits') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="directory-detail-cta d-flex gap-2 flex-wrap">
                <a class="btn btn-outline-primary rounded-pill px-4 fw-bold" href="{{ $card['visit_url'] }}" target="_blank" rel="noopener">
                    {{ __('messages.visit_site') }}
                </a>

                @if($card['can_manage'])
                    <a class="btn btn-primary rounded-pill px-4 fw-bold" href="{{ route('directory.edit', $card['listing']->id) }}">
                        {{ __('messages.edit') }}
                    </a>
                @endif
            </div>
        </div>

        <div id="notif{{ $card['listing']->id }}"></div>
        <div id="report{{ $card['listing']->id }}"></div>
    </div>
</section>
