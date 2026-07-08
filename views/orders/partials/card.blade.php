<div class="card border-0 shadow-sm rounded-4 mb-3 transition-up overflow-hidden">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex flex-wrap gap-2">
                @include('theme::orders.partials.status-pill', ['status' => $order->derived_workflow_status])
                <span class="badge bg-light text-muted border px-3 py-2 rounded-pill small fw-bold">
                    <i class="fa fa-tag me-1"></i> {{ $order->displayCategory() }}
                </span>
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small fw-bold">
                    <i class="fa fa-coins me-1"></i> {{ $order->displayBudget() }}
                </span>
            </div>
            <span class="small text-muted fw-bold">
                <i class="fa fa-handshake me-1"></i> {{ $order->offers_count ?? 0 }} {{ __('messages.offers') }}
            </span>
        </div>

        <h4 class="fw-bold mb-2">
            <a href="{{ route('orders.show', $order) }}" class="text-dark text-decoration-none hover-primary">
                {{ $order->title }}
            </a>
        </h4>

        <div class="d-flex align-items-center mb-3 small text-muted">
            <img src="{{ $order->user->avatarUrl() }}" alt="" class="rounded-circle me-2" width="20" height="20">
            <span>{{ __('messages.posted_by') }} <a href="{{ route('profile.show', $order->user->username) }}" class="text-primary text-decoration-none fw-bold">{{ $order->user->username }}</a></span>
            <span class="mx-2">•</span>
            <span><i class="fa fa-clock me-1"></i> {{ $order->date_formatted }}</span>
        </div>

        <p class="text-muted small mb-3">
            {{ \Illuminate\Support\Str::limit(trim(strip_tags($order->description)), 200) }}
        </p>

        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3">
            <div class="small text-muted fw-bold">
                <i class="fa fa-truck me-1"></i> {{ __('messages.delivery') }}: <span class="text-dark">{{ $order->displayDeliveryWindow() }}</span>
            </div>
            <div class="d-flex gap-2">
                @auth
                    @if((int) auth()->id() === (int) $order->uid && in_array($order->workflow_status, ['open', 'closed'], true))
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="fa fa-edit me-1"></i> {{ __('messages.edit') }}
                        </a>
                    @endif
                @endauth
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                    {{ __('messages.view_details') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.transition-up {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.transition-up:hover {
    transform: translateY(-4px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}
.hover-primary:hover {
    color: var(--bs-primary) !important;
}
</style>
