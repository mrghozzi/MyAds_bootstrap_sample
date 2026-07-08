@php
    $status = $activity;
    $order = $status->related_content;
    $activityUser = $status->user;
    $activityUserProfileUrl = $activityUser ? route('profile.show', $activityUser->username) : '#';
    $activityUserName = $activityUser?->username ?? __('messages.unknown_user');
    $activityUserAvatar = $activityUser ? $activityUser->avatarUrl() : asset('upload/_avatar.png');
    $activityUserPresence = $activityUser?->isOnline() ? 'online' : 'offline';
    $activityUserIsAdmin = $activityUser?->isAdmin() ?? false;
    $activityUserHasVerifiedBadge = $activityUser?->hasVerifiedBadge() ?? false;
    $orderUrl = $order ? route('orders.show', $order) : '#';
    $offersCount = $order?->offers_count ?? $status->comments_count ?? 0;
    $reportKey = 'orderfeed' . $status->id;
    $notifyKey = 'ordernotif' . $status->id;
    $canDeleteOrder = $order
        && auth()->check()
        && ((int) $order->uid === (int) auth()->id() || auth()->user()->isAdmin());
    $canEditOrder = $order
        && auth()->check()
        && (int) auth()->id() === (int) $order->uid
        && !$order->isManagedWorkflow()
        && (string) $order->workflow_status !== \App\Models\OrderRequest::WORKFLOW_COMPLETED;
    $canReportOrder = auth()->check() && !$canDeleteOrder;
    $canReportAuthor = $order && auth()->check() && (int) auth()->id() !== (int) $order->uid;
@endphp

@if($order)
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $status->id }} activity-post-card">
    <!-- Header -->
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ $activityUserProfileUrl }}" class="me-3 position-relative">
                <img src="{{ $activityUserAvatar }}" class="rounded-circle border border-2 border-white shadow-sm" width="48" height="48" alt="{{ $activityUserName }}">
                @if($activityUserPresence == 'online')
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                @endif
            </a>
            <div>
                <h6 class="fw-bold mb-0">
                    <a href="{{ $activityUserProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $activityUserName }}</a>
                    @if($activity->is_pinned)
                        <span class="badge ms-2" style="background-color: var(--bs-primary); color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;"><i class="fa fa-thumb-tack"></i> {{ __('messages.pinned_post') ?? 'Pinned Post' }}</span>
                    @endif
                    @if($activityUserHasVerifiedBadge)
                        <svg class="verified-icon ms-1" viewBox="0 0 24 24" width="14" height="14" fill="#23d2e2" style="vertical-align: middle;"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1.9 14.7L6 12.6l1.5-1.5 2.6 2.6 6.4-6.4 1.5 1.5-7.9 7.9z"/></svg>
                    @endif
                    <small class="text-muted fw-normal ms-1">{{ __('messages.order_request') }}</small>
                </h6>
                <small class="text-muted smaller fw-bold"><i class="fa-regular fa-clock me-1 opacity-50"></i> {{ $status->date_formatted }}</small>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                @if($canEditOrder)
                    <li><a class="dropdown-item py-2" href="{{ route('orders.edit', $order) }}"><i class="fa-regular fa-pen-to-square me-2"></i> {{ __('messages.edit') }}</a></li>
                @endif
                @if($canDeleteOrder)
                    <li><button class="dropdown-item py-2 text-danger" onclick="deletePost({{ $order->id }}, 6, '.post{{ $status->id }}')"><i class="fa-regular fa-trash-can me-2"></i> {{ __('messages.delete') }}</button></li>
                @endif
                @auth
                                        @if(auth()->id() == $activity->uid)
                        <li>
                            <button class="dropdown-item py-2" onclick="togglePinPost({{ $activity->id }}, {{ $activity->is_pinned ? 'true' : 'false' }}, {{ isset($hasPinnedPost) && $hasPinnedPost ? 'true' : 'false' }})">
                                <i class="fa fa-thumb-tack me-2"></i> {{ $activity->is_pinned ? __('messages.unpin_post') : __('messages.pin_post') }}
                            </button>
                        </li>
                    @endif
@include('theme::partials.activity.promotion_link', ['activity' => $activity])
                    @if($canReportOrder)
                        <li><button class="dropdown-item py-2" onclick="reportPost({{ $order->id }}, 6)"><i class="fa-regular fa-flag me-2"></i> {{ __('messages.report') }}</button></li>
                    @endif
                    @if($canReportAuthor)
                        <li><button class="dropdown-item py-2" onclick="reportUser({{ $order->uid }})"><i class="fa-regular fa-user me-2"></i> {{ __('messages.report_author') }}</button></li>
                    @endif
                @endauth
                <li><a class="dropdown-item py-2" href="{{ $orderUrl }}"><i class="fa-regular fa-eye me-2"></i> {{ __('messages.view_details') }}</a></li>
                <li><button class="dropdown-item py-2" onclick="navigator.clipboard.writeText('{{ $orderUrl }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa-regular fa-copy me-2"></i> {{ __('messages.copy_link') }}</button></li>
            </ul>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-4">
        @include('theme::partials.activity.promotion_badge', ['activity' => $activity])

        <div class="post-content">
            <div class="card border border-light-subtle rounded-4 overflow-hidden shadow-sm bg-light bg-opacity-25 hover-translate-y">
                <div class="card-body p-4">
                    <div class="d-flex gap-2 flex-wrap mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold">{{ $order->displayCategory() }}</span>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">{{ $order->displayBudget() }}</span>
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-bold">{{ __('messages.offers') }}: {{ $offersCount }}</span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-bold">{{ $order->displayWorkflowStatus() }}</span>
                    </div>

                    <h5 class="fw-black mb-3">
                        <a href="{{ $orderUrl }}" class="text-dark text-decoration-none hover-primary">{{ $order->title }}</a>
                    </h5>

                    <p class="text-secondary small mb-3 lh-lg">{{ \Illuminate\Support\Str::limit(trim(strip_tags($order->description)), 240) }}</p>
                </div>
            </div>
        </div>

        <div id="report{{ $reportKey }}"></div>
        <div id="{{ $notifyKey }}"></div>
    </div>

    <!-- Footer -->
    <div class="card-footer bg-white border-top py-3">
        <div class="d-flex justify-content-between align-items-center py-2 border-top border-bottom mb-3">
            <div class="d-flex gap-3">
                <div class="d-flex align-items-center text-muted small fw-bold">
                    @include('theme::partials.activity.reaction-list', ['activity' => $activity])
                    <span class="ms-1">{{ $status->reactions_count }}</span>
                </div>
                <div class="d-flex align-items-center text-muted small fw-bold">
                    <a href="{{ $orderUrl }}" class="text-decoration-none text-muted">
                        <i class="fa-solid fa-briefcase me-1"></i> {{ $offersCount }} {{ __('messages.offers') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-around align-items-center py-1">
            @auth
                                    @if(auth()->id() == $activity->uid)
                        <li>
                            <button class="dropdown-item py-2" onclick="togglePinPost({{ $activity->id }}, {{ $activity->is_pinned ? 'true' : 'false' }}, {{ isset($hasPinnedPost) && $hasPinnedPost ? 'true' : 'false' }})">
                                <i class="fa fa-thumb-tack me-2"></i> {{ $activity->is_pinned ? __('messages.unpin_post') : __('messages.pin_post') }}
                            </button>
                        </li>
                    @endif
<div class="dropdown">
                    <button class="btn btn-link text-decoration-none text-muted fw-bold d-flex align-items-center gap-1 dropdown-toggle py-1 px-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="reaction-btn-{{ $order->id }}">
                        <i class="fa-regular fa-thumbs-up"></i>
                        <span>{{ __('messages.react') }}</span>
                    </button>
                    <ul class="dropdown-menu shadow border-0 p-2" aria-labelledby="reaction-btn-{{ $order->id }}">
                        <li class="d-flex gap-1 justify-content-between">
                            @foreach(['like', 'love', 'dislike', 'happy', 'funny', 'wow', 'angry', 'sad'] as $reaction)
                                <button class="btn btn-link p-1 border-0 bg-transparent text-decoration-none" onclick="toggleReaction({{ $order->id }}, 'order', '{{ $reaction }}')" title="{{ $reaction }}">
                                    <img src="{{ theme_asset('img/reaction/'.$reaction.'.png') }}" width="24" height="24" alt="reaction-{{ $reaction }}">
                                </button>
                            @endforeach
                        </li>
                    </ul>
                </div>
            @endauth

            <a class="btn btn-link text-decoration-none text-muted fw-bold d-flex align-items-center gap-1 py-1 px-2 border-0 bg-transparent" href="{{ $orderUrl }}">
                <i class="fa-regular fa-folder-open"></i>
                <span>{{ __('messages.view_details') }}</span>
            </a>

            <div class="dropdown">
                <button class="btn btn-link text-decoration-none text-muted fw-bold d-flex align-items-center gap-1 dropdown-toggle py-1 px-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-share-nodes"></i>
                    <span>{{ __('messages.share') }}</span>
                </button>
                <ul class="dropdown-menu shadow border-0 p-2">
                    <li class="d-flex gap-2 align-items-center">
                        @foreach(['facebook', 'twitter', 'linkedin', 'telegram'] as $social)
                            <button class="btn btn-link p-1 border-0 bg-transparent text-decoration-none" onclick="sharePost('{{ $social }}', '{{ $orderUrl }}', '{{ $order->title }}')" title="{{ $social }}">
                                <img src="{{ theme_asset('img/icons/'.$social.'-icon.png') }}" width="24" height="24">
                            </button>
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
