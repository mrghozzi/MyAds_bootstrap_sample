@php
    $latestOrders = \App\Models\OrderRequest::query()
        ->with('user')
        ->withCount([
            'offers as offers_count' => fn ($query) => $query->marketplaceVisible(),
        ])
        ->orderBy('date', 'desc')
        ->limit(5)
        ->get();
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name ?? __('messages.latest_orders') }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @forelse($latestOrders as $order)
                @php
                    $orderUser = $order->user;
                    $orderUserProfileUrl = $orderUser ? route('profile.show', $orderUser->username) : '#';
                    $orderUserAvatar = $orderUser ? $orderUser->avatarUrl() : asset('upload/_avatar.png');
                    $orderUserPresence = $orderUser?->isOnline() ? 'online' : 'offline';
                @endphp
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ $orderUserProfileUrl }}" class="position-relative">
                        <img src="{{ $orderUserAvatar }}" class="rounded-circle border" width="38" height="38" alt="{{ $orderUser?->username ?? '' }}">
                        @if($orderUserPresence == 'online')
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                        @endif
                    </a>
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="mb-0 fw-bold small text-truncate">
                            <a href="{{ route('orders.show', $order) }}" class="text-dark text-decoration-none hover-primary">{{ $order->title }}</a>
                        </h6>
                        <small class="text-muted smaller d-block text-truncate">
                            {{ $order->displayCategory() }} &bull; {{ $order->displayBudget() }} &bull; {{ __('messages.offers') }}: {{ $order->offers_count }}
                        </small>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted small my-3">{{ __('messages.no_orders_found') }}</p>
            @endforelse
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-3 fw-bold">
            {{ __('messages.view_all_orders') }}
        </a>
    </div>
</div>
