@php
    $status = $activity;
    $statusUser = $status->user;
    $statusUserProfileUrl = $statusUser ? route('profile.show', $statusUser->username) : '#';
    $statusUserName = $statusUser?->username ?? __('messages.unknown_user');
    $statusUserAvatar = $statusUser ? $statusUser->avatarUrl() : asset('upload/_avatar.png');
    $statusUserPresence = $statusUser?->isOnline() ? 'online' : 'offline';
    $statusUserIsAdmin = $statusUser?->isAdmin() ?? false;
    $statusUserHasVerifiedBadge = $statusUser?->hasVerifiedBadge() ?? false;
    $product = $activity->related_content;
    $description = \App\Support\ContentFormatter::format(\Illuminate\Support\Str::limit($product->o_valuer ?? '', 480));
    $productImage = $product->product_image ?? theme_asset('img/error_plug.png');
    $repostExcerpt = \Illuminate\Support\Str::limit(strip_tags($product->name ?? ($product->o_valuer ?? '')), 80);
    $repostAuthorName = addslashes($statusUserName);
@endphp

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $status->id }} activity-post-card">
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
                    <small class="text-muted fw-normal ms-1">
                        @if(isset($status->txt) && $status->txt == 'update')
                            {{ __('messages.updated_product') }}
                        @else
                            {{ __('messages.added_new_product') }}
                        @endif
                    </small>
                </h6>
                <small class="text-muted smaller fw-bold"><i class="fa-regular fa-clock me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($status->date)->diffForHumans() }}</small>
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
@if(auth()->id() == $status->uid || auth()->user()->isAdmin())
                        <li><button class="dropdown-item py-2" onclick="postEdit({{ $status->tp_id }}, 7867, '{{ addslashes($product->name) }}')"><i class="fa-regular fa-pen-to-square me-2"></i> {{ __('messages.edit') }}</button></li>
                        <li><button class="dropdown-item py-2 text-danger" onclick="deletePost({{ $status->tp_id }}, 7867, '.post{{ $status->id }}')"><i class="fa-regular fa-trash-can me-2"></i> {{ __('messages.delete') }}</button></li>
                    @endif
                    @include('theme::partials.activity.promotion_link', ['activity' => $activity])
                    <li><button class="dropdown-item py-2" onclick="reportPost({{ $status->tp_id }}, 7867)"><i class="fa-regular fa-flag me-2"></i> {{ __('messages.report') }}</button></li>
                    <li><button class="dropdown-item py-2" onclick="reportUser({{ $status->uid }})"><i class="fa-regular fa-user me-2"></i> {{ __('messages.report_author') }}</button></li>
                @endauth
                <li><a class="dropdown-item py-2" href="{{ route('store.show', $product->name) }}"><i class="fa-regular fa-eye me-2"></i> {{ __('messages.preview') }}</a></li>
                <li><button class="dropdown-item py-2" onclick="navigator.clipboard.writeText('{{ route('store.show', $product->name) }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa-regular fa-copy me-2"></i> {{ __('messages.copy_link') }}</button></li>
            </ul>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-4">
        @include('theme::partials.activity.promotion_badge', ['activity' => $activity])

        <div class="post-content post_text{{ $product->id }}">
            <div class="textpost mb-3 lh-lg" id="post_form{{ $product->id }}">
                <div class="card border border-light-subtle rounded-4 overflow-hidden shadow-sm hover-translate-y">
                    <div class="row g-0">
                        <div class="col-md-4 position-relative" style="min-height: 200px; background: url('{{ $productImage }}') center center / cover no-repeat;">
                            <!-- Type / Category Badge -->
                            @if($product->type)
                                <span class="position-absolute top-0 start-0 m-3 badge bg-primary py-2 px-3 rounded-pill fw-bold shadow-sm">
                                    {{ $product->type->name }}
                                </span>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                                        <h5 class="card-title fw-black mb-0">
                                            <a href="{{ route('store.show', $product->name) }}" class="text-dark text-decoration-none hover-primary">{{ $product->name }}</a>
                                        </h5>
                                        @if($product->o_order > 0)
                                            @if($product->sale && $product->sale->is_active)
                                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold fs-6">
                                                    <span class="text-decoration-line-through me-1 small opacity-75">{{ $product->o_order }}</span>
                                                    {{ $product->sale->sale_price }} {{ __('messages.points') }}
                                                </span>
                                            @else
                                                <span class="badge bg-success rounded-pill px-3 py-2 fw-bold fs-6">
                                                    {{ $product->o_order }} {{ __('messages.points') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold fs-6">
                                                {{ __('messages.free') }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($product->is_suspended)
                                        <div class="mb-2">
                                            <span class="badge bg-danger">{{ __('messages.suspended') }}</span>
                                        </div>
                                    @endif
                                    <p class="card-text text-secondary small line-clamp-3">{!! $description !!}</p>
                                </div>
                                <div class="d-flex justify-content-end align-items-center mt-3 pt-3 border-top border-light">
                                    <a href="{{ route('store.show', $product->name) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-black">
                                        <i class="fa fa-shopping-basket me-1"></i> {{ __('messages.preview') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="report{{ $product->id }}"></div>
            </div>
        </div>

        <div id="notif{{ $product->id }}"></div>
    </div>

    <!-- Footer -->
    <div class="card-footer bg-white border-top py-3">
        @include('theme::partials.activity.post_footer_shared', [
            'activity' => $activity,
            'repostAuthorName' => $repostAuthorName,
            'repostExcerpt' => $repostExcerpt,
            'detailUrl' => route('store.show', $product->name),
            'commentType' => 'store',
            'reactionType' => 3,
            'reactionCategory' => 'store'
        ])
    </div>
</div>
