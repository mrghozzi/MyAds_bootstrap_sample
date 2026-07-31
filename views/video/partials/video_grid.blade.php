@if($videos->count() > 0)
    <div class="row g-4" id="videoGridItems">
        @foreach($videos as $status)
            @php
                $topic = $status->forumTopic;
                $user = $status->user;
                $watchUrl = $topic ? route('forum.topic', $topic->id) : url('/portal');
                
                // Multi-Source Resolved Thumbnail & Title
                $thumbUrl = $status->resolved_thumbnail ?: \App\Http\Controllers\VideoHubController::resolveVideoThumbnailUrl($status);
                $videoTitle = \Illuminate\Support\Str::limit($status->resolved_title ?: \App\Http\Controllers\VideoHubController::resolveVideoTitle($status), 60);

                // Views / Reactions / Date
                $viewsCount = $topic ? (int) ($topic->vu ?? 0) : (int) ($status->views_count ?? 0);
                $reactionsCount = (int) ($status->reactions_count ?? 0);
                $timeAgo = $status->date_formatted ?: ($status->created_at ? $status->created_at->diffForHumans() : '');
                $isClip = (int) $status->s_type === 14;
            @endphp

            <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                <div class="card h-100 border-0 rounded-4 shadow-sm yt-video-card transition-all overflow-hidden" data-id="{{ $status->id }}">
                    <!-- 16:9 Thumbnail Stage -->
                    <a href="{{ $watchUrl }}" class="yt-thumb-stage d-block text-decoration-none position-relative">
                        <div class="ratio ratio-16x9 rounded-top-4 overflow-hidden bg-dark">
                            <img src="{{ $thumbUrl }}" alt="{{ $videoTitle }}" class="yt-thumb-img w-100 h-100 object-fit-cover lazyload" onError="this.onerror=null;this.src='{{ theme_asset('img/avatar.jpg') }}';">
                            <div class="yt-thumb-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <span class="yt-play-btn-circle d-flex align-items-center justify-content-center rounded-circle shadow-lg">
                                    <i class="fa-solid fa-play text-white fs-5 ms-1"></i>
                                </span>
                            </div>
                            @if($isClip)
                                <span class="badge position-absolute bottom-0 end-0 m-2 px-2.5 py-1 bg-danger text-white rounded-2 fs-7 fw-bold shadow-xs">
                                    <i class="fa-solid fa-bolt me-1"></i> CLIP
                                </span>
                            @else
                                <span class="badge position-absolute bottom-0 end-0 m-2 px-2.5 py-1 bg-dark bg-opacity-75 text-white rounded-2 fs-7 fw-bold shadow-xs">
                                    HD
                                </span>
                            @endif
                        </div>
                    </a>

                    <!-- Video Body & Publisher Details -->
                    <div class="card-body p-3 d-flex align-items-start gap-2">
                        @if($user)
                            <a href="{{ route('profile.short', $user->publicRouteIdentifier()) }}" class="user-avatar small no-outline flex-shrink-0" style="width: 36px; height: 40px; text-decoration: none;">
                                <div class="user-avatar-content">
                                    <div class="hexagon-image-30-32" data-src="{{ $user->img ? url($user->img) : theme_asset('img/avatar.jpg') }}"></div>
                                </div>
                                <div class="user-avatar-progress-border">
                                    <div class="hexagon-border-40-44" data-line-color="{{ $user->profileBadgeColor() }}"></div>
                                </div>
                            </a>
                        @endif

                        <div class="yt-card-meta flex-grow-1 min-w-0">
                            <h3 class="h6 mb-1 text-truncate-2" style="line-height: 1.4;">
                                <a href="{{ $watchUrl }}" class="text-reset text-decoration-none fw-bold hover-primary">
                                    {{ $videoTitle }}
                                </a>
                            </h3>

                            @if($user)
                                <div class="small text-muted d-flex align-items-center gap-1 mb-1">
                                    <a href="{{ route('profile.short', $user->publicRouteIdentifier()) }}" class="text-muted text-decoration-none fw-semibold">
                                        {{ $user->username }}
                                    </a>
                                    @if($user->verified)
                                        <i class="fa-solid fa-circle-check text-primary fs-7" title="{{ __('messages.verified') }}"></i>
                                    @endif
                                </div>
                            @endif

                            <div class="small text-muted d-flex align-items-center gap-2 flex-wrap fs-8">
                                <span>{{ __('messages.video_views_count', ['count' => number_format($viewsCount)]) }}</span>
                                <span>•</span>
                                <span>{{ $timeAgo }}</span>
                                @if($reactionsCount > 0)
                                    <span>•</span>
                                    <span class="text-primary fw-medium"><i class="fa-solid fa-heart me-1"></i>{{ $reactionsCount }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="yt-pagination-wrap mt-5 d-flex justify-content-center">
        {{ $videos->links() }}
    </div>
@else
    <div class="text-center py-5 px-3 rounded-4 shadow-sm border bg-body-tertiary">
        <div class="mb-3 text-primary display-4">
            <i class="fa-solid fa-video-slash"></i>
        </div>
        <h4 class="fw-bold mb-2">{{ __('messages.no_videos_found') }}</h4>
        <p class="text-muted mb-4 max-w-md mx-auto">{{ __('messages.no_videos_desc') }}</p>
        @auth
            <a href="{{ url('/share') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">
                <i class="fa-solid fa-cloud-arrow-up me-2"></i> {{ __('messages.create_video') }}
            </a>
        @endauth
    </div>
@endif
