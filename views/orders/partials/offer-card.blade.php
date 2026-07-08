@php
    $isAwarded = (int) optional($order->awardedOffer)->id === (int) $offer->id;
    $canAward = auth()->check()
        && (int) auth()->id() === (int) $order->uid
        && $order->workflow_status === \App\Models\OrderRequest::WORKFLOW_OPEN
        && $offer->status === \App\Models\OrderOffer::STATUS_ACTIVE;
    $canWithdraw = auth()->check()
        && (int) auth()->id() === (int) $offer->user_id
        && $offer->isEditable();
@endphp

<div class="list-group-item border-0 p-4 {{ $isAwarded ? 'bg-success bg-opacity-10 border-start border-4 border-success' : 'border-bottom' }}" id="offer-{{ $offer->id }}">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ $offer->user->avatarUrl() }}" alt="" class="rounded-circle me-3" width="42" height="42">
            <div>
                <h6 class="fw-bold mb-0">
                    <a href="{{ route('profile.show', $offer->user->username) }}" class="text-dark text-decoration-none hover-primary">{{ $offer->user->username }}</a>
                    @if($isAwarded) <span class="badge bg-success rounded-pill ms-2 small fw-normal">{{ __('messages.awarded') }}</span> @endif
                </h6>
                <small class="text-muted smaller fw-bold">{{ $offer->created_at?->diffForHumans() }}</small>
            </div>
        </div>
        <div class="text-end">
            <h5 class="fw-bold text-success mb-0">{{ $offer->displayQuote() }}</h5>
            <small class="text-muted smaller fw-bold">{{ $offer->displayDelivery() }} • {{ __('messages.order_pricing_model_' . $offer->pricing_model) }}</small>
        </div>
    </div>

    <div class="p-3 bg-light rounded-4 mb-3 small lh-lg">
        {{ $offer->message }}
    </div>

    @if($offer->client_rating)
        <div class="d-flex align-items-center mb-3">
            <div class="text-warning me-2">
                @for($i = 1; $i <= 5; $i++) <i class="fa{{ $i <= $offer->client_rating ? 's' : 'r' }} fa-star fs-6"></i> @endfor
            </div>
            <span class="small text-muted fw-bold">{{ __('messages.client_review') }}</span>
        </div>
        @if($offer->client_review)
            <p class="small text-muted italic mb-3 ps-3 border-start">"{{ $offer->client_review }}"</p>
        @endif
    @endif

    <div class="d-flex justify-content-end gap-2">
        @if($canAward)
            <form action="{{ route('orders.award', $order) }}" method="POST">
                @csrf
                <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">{{ __('messages.order_award_offer') }}</button>
            </form>
        @endif

        @if($canWithdraw)
            <form action="{{ route('orders.offers.destroy', $offer) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">{{ __('messages.withdraw') }}</button>
            </form>
        @endif
    </div>
</div>
