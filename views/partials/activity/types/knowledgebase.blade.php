@php
    $status = $activity;
    $statusUser = $status->user;
    $statusUserProfileUrl = $statusUser ? route('profile.show', $statusUser->username) : '#';
    $statusUserName = $statusUser?->username ?? __('messages.unknown_user');
    $statusUserAvatar = $statusUser ? $statusUser->avatarUrl() : asset('upload/_avatar.png');
    $statusUserPresence = $statusUser?->isOnline() ? 'online' : 'offline';
    $statusUserIsAdmin = $statusUser?->isAdmin() ?? false;
    $statusUserHasVerifiedBadge = $statusUser?->hasVerifiedBadge() ?? false;
    $article = $status->related_content;
    $product = $article?->productItem;
    $articleAuthor = $article?->authorUser;
    $productSlug = $product?->name ?? $article?->o_mode ?? '';
    $productName = $productSlug !== '' ? $productSlug : __('messages.knowledgebase');
    $knowledgebaseUrl = ($productSlug !== '' && $article?->name)
        ? route('kb.show', ['name' => $productSlug, 'article' => $article->name])
        : '#';
    $reportKey = 'kbfeed' . $status->id;
    $notifyKey = 'kbnotif' . $status->id;
    $rawSummary = html_entity_decode(strip_tags((string) ($article?->o_valuer ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $rawSummary = trim((string) preg_replace('/[#>*_`~\\[\\]\\(\\)\r\n]+/u', ' ', $rawSummary));
    $summary = \Illuminate\Support\Str::limit((string) preg_replace('/\s+/u', ' ', $rawSummary), 240);
    $repostExcerpt = \Illuminate\Support\Str::limit(trim(($article?->name ?? '') . ' ' . $summary), 80);
    $repostAuthorName = addslashes($statusUserName);
    $canDeleteStatus = auth()->check() && (auth()->id() == $status->uid || auth()->user()->isAdmin());
    $canReportTopic = auth()->check() && !$canDeleteStatus;
    $canReportAuthor = $canReportTopic && $articleAuthor && auth()->id() != $articleAuthor->id;
@endphp

@if($article)
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $status->id }} activity-post-card" id="community-post-{{ $status->id }}">
    <!-- Header -->
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ $statusUserProfileUrl }}" class="me-3 position-relative">
                <img src="{{ $statusUserAvatar }}" class="rounded-circle border border-2 border-white shadow-sm" width="48" height="48" alt="{{ $statusUserName }}">
                @if($statusUserPresence == 'online')
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                @endif
            </a>
            <div>
                <h6 class="fw-bold mb-0">
                    <a href="{{ $statusUserProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $statusUserName }}</a>
                    @if($statusUserHasVerifiedBadge)
                        <svg class="verified-icon ms-1" viewBox="0 0 24 24" width="14" height="14" fill="#23d2e2" style="vertical-align: middle;"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1.9 14.7L6 12.6l1.5-1.5 2.6 2.6 6.4-6.4 1.5 1.5-7.9 7.9z"/></svg>
                    @endif
                </h6>
                <small class="text-muted smaller fw-bold"><i class="fa-regular fa-clock me-1 opacity-50"></i> {{ $status->date_formatted }}</small>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                @auth
                                        @if(auth()->id() == $activity->uid)
                        <li>
                            <button class="dropdown-item py-2" onclick="togglePinPost({{ $activity->id }}, {{ $activity->is_pinned ? 'true' : 'false' }}, {{ isset($hasPinnedPost) && $hasPinnedPost ? 'true' : 'false' }})">
                                <i class="fa fa-thumb-tack me-2"></i> {{ $activity->is_pinned ? __('messages.unpin_post') : __('messages.pin_post') }}
                            </button>
                        </li>
                    @endif
@if($canDeleteStatus)
                        <li><button class="dropdown-item py-2 text-danger" onclick="deletePost({{ $status->id }}, 205, '.post{{ $status->id }}')"><i class="fa-regular fa-trash-can me-2"></i> {{ __('messages.delete') }}</button></li>
                    @endif
                    @if($canReportTopic)
                        <li><button class="dropdown-item py-2" onclick="reportPost({{ $article->id }}, 205)"><i class="fa-regular fa-flag me-2"></i> {{ __('messages.report_topic') }}</button></li>
                    @endif
                    @if($canReportAuthor)
                        <li><button class="dropdown-item py-2" onclick="reportUser({{ $articleAuthor->id }})"><i class="fa-regular fa-user me-2"></i> {{ __('messages.report_publisher') }}</button></li>
                    @endif
                @endauth
                <li><a class="dropdown-item py-2" href="{{ $knowledgebaseUrl }}"><i class="fa-regular fa-eye me-2"></i> {{ __('messages.preview') }}</a></li>
                <li><button class="dropdown-item py-2" onclick="navigator.clipboard.writeText('{{ $knowledgebaseUrl }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa-regular fa-copy me-2"></i> {{ __('messages.copy_link') }}</button></li>
            </ul>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-4">
        @include('theme::partials.activity.promotion_badge', ['activity' => $activity])

        <div class="post-content">
            <div class="card border border-light-subtle rounded-4 overflow-hidden shadow-sm bg-light bg-opacity-50 hover-translate-y">
                <div class="card-body p-4">
                    <div class="d-flex gap-2 flex-wrap mb-3">
                        <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold text-uppercase">{{ __('messages.knowledgebase') }}</span>
                        <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold">{{ $productName }}</span>
                        <span class="badge bg-success rounded-pill px-3 py-2 fw-bold">{{ __('messages.published') }}</span>
                    </div>

                    <h5 class="fw-black mb-3">
                        <a href="{{ $knowledgebaseUrl }}" class="text-dark text-decoration-none hover-primary">{{ $article->name }}</a>
                    </h5>

                    @if($summary !== '')
                        <p class="text-secondary small mb-4 lh-lg">{{ $summary }}</p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 pt-3 border-top border-light">
                        <div class="d-flex gap-3 text-muted smaller fw-bold flex-wrap">
                            <span><i class="fa fa-hashtag me-1"></i> {{ __('messages.topic') }} #{{ $article->id }}</span>
                            <span><i class="fa fa-user me-1"></i> {{ __('messages.publisher') }}: {{ $articleAuthor?->username ?? __('messages.guest') }}</span>
                        </div>
                        <a href="{{ $knowledgebaseUrl }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-black">
                            {{ __('messages.preview') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div id="report{{ $reportKey }}"></div>
        <div id="{{ $notifyKey }}"></div>
    </div>

    <!-- Footer -->
    <div class="card-footer bg-white border-top py-3">
        @php
            $kbReactionType = defined('\App\Services\KnowledgebaseCommunityService::REACTION_TYPE') ? \App\Services\KnowledgebaseCommunityService::REACTION_TYPE : 2;
        @endphp
        @include('theme::partials.activity.post_footer_shared', [
            'activity' => $activity,
            'repostAuthorName' => $repostAuthorName,
            'repostExcerpt' => $repostExcerpt,
            'detailUrl' => $knowledgebaseUrl,
            'commentType' => 'knowledgebase',
            'reactionType' => $kbReactionType,
            'reactionCategory' => 'knowledgebase',
            'targetId' => $status->id
        ])
    </div>
</div>
@endif
