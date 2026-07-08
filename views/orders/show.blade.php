@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 px-4 rounded-pill shadow-sm border">
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-primary fw-bold"><i class="fa fa-briefcase"></i></a></li>
            <li class="breadcrumb-item active text-truncate small fw-bold" aria-current="page" style="max-width: 300px;">{{ $order->title }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar: Client Info -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4 p-md-5 mb-4 overflow-hidden position-relative">
                <div class="bg-primary bg-opacity-10 position-absolute top-0 start-0 w-100 py-2 border-bottom">
                    <span class="fw-black smaller text-uppercase text-primary letter-spacing-1">{{ __('messages.client') }}</span>
                </div>
                <div class="mt-4 mb-3 position-relative d-inline-block">
                    <img src="{{ $order->user->avatarUrl() }}" class="rounded-circle border border-4 border-white shadow-sm transition-all hover-scale" width="100" height="100" style="object-fit: cover;">
                    @if($order->user->isOnline())
                        <span class="position-absolute bottom-0 end-0 bg-success border border-4 border-white rounded-circle" style="width: 20px; height: 20px;"></span>
                    @endif
                </div>
                <h5 class="fw-black mb-1">
                    <a href="{{ route('profile.short', $order->user->publicRouteIdentifier()) }}" class="text-dark text-decoration-none hover-primary">{{ $order->user->username }}</a>
                </h5>
                <p class="smaller text-muted mb-4 fw-bold">@ {{ $order->user->username }}</p>

                <div class="list-group list-group-flush text-start mb-4 bg-light bg-opacity-50 rounded-4 p-2 border">
                    <div class="list-group-item bg-transparent border-0 px-2 py-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted fw-bold">{{ __('messages.status') }}</small>
                        <span class="small fw-black text-dark">{{ $order->displayWorkflowStatus() }}</span>
                    </div>
                    <div class="list-group-item bg-transparent border-0 px-2 py-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted fw-bold">{{ __('messages.offers') }}</small>
                        <span class="badge bg-primary rounded-pill px-2">{{ $order->offers_count }}</span>
                    </div>
                    <div class="list-group-item bg-transparent border-0 px-2 py-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted fw-bold">{{ __('messages.budget') }}</small>
                        <span class="small fw-black text-success">{{ $order->displayBudget() }}</span>
                    </div>
                    <div class="list-group-item bg-transparent border-0 px-2 py-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted fw-bold">{{ __('messages.delivery') }}</small>
                        <span class="small fw-black text-dark text-truncate ms-2">{{ $order->displayDeliveryWindow() }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    @auth
                        @if((int) auth()->id() !== (int) $order->uid)
                            <a href="{{ url('/messages/' . \App\Models\Message::encodeConversationRouteKey(auth()->user(), $order->uid)) }}" class="btn btn-primary rounded-pill fw-black py-2 shadow-sm transition-all hover-translate-y">
                                <i class="fa fa-envelope me-2"></i> {{ __('messages.contact_client') }}
                            </a>
                        @endif
                        @if((int) auth()->id() === (int) $order->uid && in_array($order->workflow_status, ['open', 'closed'], true))
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline-dark rounded-pill fw-black py-2 shadow-sm transition-all hover-bg-dark hover-text-white">
                                <i class="fa fa-edit me-2"></i> {{ __('messages.edit') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
            <x-widget-column side="portal_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-6">
            <!-- Order Details Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex flex-wrap gap-2">
                        @include('theme::orders.partials.status-pill', ['status' => $order->derived_workflow_status])
                        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill smaller fw-black">
                            <i class="fa fa-tag me-1 text-primary"></i> {{ $order->displayCategory() }}
                        </span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-ellipsis-v"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                            @auth
                                @if((int) auth()->id() === (int) $order->uid && $order->workflow_status === \App\Models\OrderRequest::WORKFLOW_OPEN)
                                    <li><form action="{{ route('orders.close', $order) }}" method="POST">@csrf<button type="submit" class="dropdown-item py-2 small fw-black text-danger"><i class="fa fa-times-circle me-2"></i> {{ __('messages.close_order') }}</button></form></li>
                                @endif
                            @endauth
                            <li><button class="dropdown-item py-2 small fw-black" onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa fa-copy me-2 text-muted"></i> {{ __('messages.copy_link') }}</button></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <h2 class="fw-black mb-3 text-dark">{{ $order->title }}</h2>
                    <div class="d-flex align-items-center gap-3 mb-4 text-muted smaller fw-bold opacity-75">
                        <span class="d-flex align-items-center"><i class="fa fa-user me-2"></i> {{ $order->user->username }}</span>
                        <span class="d-flex align-items-center"><i class="fa fa-calendar me-2"></i> {{ $order->date_formatted }}</span>
                    </div>
                    
                    <div class="order-description lh-lg p-4 bg-light rounded-4 mb-4 border-start border-4 border-primary shadow-sm fs-5 text-dark" style="white-space: pre-line;">
                        {{ $order->description }}
                    </div>

                    @if($order->contract)
                        <div class="card border-0 bg-primary bg-opacity-10 rounded-4 p-3 mb-4 border border-primary border-opacity-10 shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-4 p-3 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fa fa-file-contract fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-black mb-1 text-primary">{{ __('messages.order_active_contract') }}</h6>
                                    <p class="mb-0 smaller text-muted fw-bold">
                                        {{ __('messages.provider') }}: <span class="text-dark">{{ optional($order->contract->provider)->username }}</span> 
                                        <span class="mx-2">|</span> 
                                        {{ __('messages.status') }}: <span class="badge bg-primary bg-opacity-25 text-primary rounded-pill px-2">{{ $order->contract->displayStatus() }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Workflow Actions -->
                    <div class="d-flex flex-wrap gap-2 pt-4 border-top">
                        @auth
                            @if((int) auth()->id() === (int) $order->uid && in_array($order->workflow_status, [\App\Models\OrderRequest::WORKFLOW_AWARDED, \App\Models\OrderRequest::WORKFLOW_IN_PROGRESS, \App\Models\OrderRequest::WORKFLOW_DELIVERED], true))
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-outline-danger rounded-pill px-4 fw-black transition-all hover-translate-y">{{ __('messages.order_cancel_action') }}</button></form>
                            @endif
                            @if($order->contract && (int) auth()->id() === (int) $order->contract->provider_user_id && $order->workflow_status === \App\Models\OrderRequest::WORKFLOW_AWARDED)
                                <form action="{{ route('orders.start', $order) }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-success text-white rounded-pill px-5 fw-black shadow transition-all hover-translate-y">{{ __('messages.order_start_action') }}</button></form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Forms Section -->
            @auth
                <!-- Offer Form -->
                @if((int) auth()->id() !== (int) $order->uid && in_array($order->workflow_status, [\App\Models\OrderRequest::WORKFLOW_OPEN], true))
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-primary bg-gradient text-white py-3 px-4 border-bottom-0 fw-black">
                            <i class="fa fa-plus-circle me-2"></i> {{ $viewerOffer ? __('messages.order_offer_edit_title') : __('messages.order_offer_form_title') }}
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ $viewerOffer ? route('orders.offers.update', $viewerOffer) : route('orders.offers.store', $order) }}" method="POST">
                                @csrf @if($viewerOffer) @method('PATCH') @endif
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.pricing') }}</label>
                                        <select class="form-select bg-light border-0 rounded-3 py-2" name="pricing_model">
                                            @foreach(['fixed', 'hourly', 'negotiable'] as $pricingModel)
                                                <option value="{{ $pricingModel }}" @selected(old('pricing_model', optional($viewerOffer)->pricing_model ?? 'fixed') === $pricingModel)>{{ __('messages.order_pricing_model_' . $pricingModel) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.price') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" class="form-control bg-light border-0 rounded-start-3 py-2" name="quoted_amount" value="{{ old('quoted_amount', optional($viewerOffer)->quoted_amount) }}" required>
                                            <select class="form-select bg-light border-0 border-start rounded-end-3 py-2" style="max-width: 100px;" name="currency_code">
                                                @foreach($currencies as $currencyCode => $currencyLabel)
                                                    <option value="{{ $currencyCode }}" @selected(old('currency_code', optional($viewerOffer)->currency_code ?? $order->budget_currency ?? 'USD') === $currencyCode)>{{ $currencyCode }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.delivery_days') }}</label>
                                        <div class="input-group">
                                            <input type="number" min="1" class="form-control bg-light border-0 rounded-start-3 py-2" name="delivery_days" value="{{ old('delivery_days', optional($viewerOffer)->delivery_days) }}" required>
                                            <span class="input-group-text bg-light border-0 border-start rounded-end-3">{{ __('messages.days') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.message_to_client') }}</label>
                                    <textarea class="form-control bg-light border-0 rounded-4 p-3" name="message" rows="5" required placeholder="{{ __('messages.order_offer_message_placeholder') ?? 'Describe how you can help...' }}">{{ old('message', optional($viewerOffer)->message) }}</textarea>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-black shadow-sm transition-all hover-translate-y">{{ $viewerOffer ? __('messages.save_changes') : __('messages.order_submit_offer') }}</button>
                                    @if($viewerOffer && $viewerOffer->isEditable())
                                        <button type="button" class="btn btn-link text-danger text-decoration-none smaller fw-bold" onclick="if(confirm('{{ __('messages.confirm_withdraw_offer') }}')) document.getElementById('withdraw-offer-form').submit()">{{ __('messages.order_withdraw_offer') }}</button>
                                    @endif
                                </div>
                            </form>
                            @if($viewerOffer && $viewerOffer->isEditable())
                                <form id="withdraw-offer-form" action="{{ route('orders.offers.destroy', $viewerOffer) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Completion/Delivery Forms -->
                @if((int) auth()->id() === (int) $order->uid && (string) $order->workflow_status === \App\Models\OrderRequest::WORKFLOW_DELIVERED)
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden border-start border-4 border-success">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-black mb-2 text-success">{{ __('messages.order_complete_action') }}</h4>
                            <p class="text-muted smaller fw-bold mb-4">{{ __('messages.order_complete_help') }}</p>
                            <form action="{{ route('orders.complete', $order) }}" method="POST">
                                @csrf
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.rating') }}</label>
                                        <select class="form-select bg-light border-0 rounded-3 py-2" name="rating">
                                            @for($i = 5; $i >= 1; $i--) <option value="{{ $i }}">{{ $i }}/5 Stars</option> @endfor
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.review') }}</label>
                                        <textarea class="form-control bg-light border-0 rounded-4 p-3" name="review" rows="4" placeholder="{{ __('messages.order_review_placeholder') ?? 'Share your experience...' }}"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success text-white rounded-pill px-5 fw-black shadow-sm transition-all hover-translate-y">{{ __('messages.order_complete_action') }}</button>
                            </form>
                        </div>
                    </div>
                @endif
                
                @if($order->contract && (int) auth()->id() === (int) $order->contract->provider_user_id && (string) $order->workflow_status === \App\Models\OrderRequest::WORKFLOW_IN_PROGRESS)
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden border-start border-4 border-primary">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-black mb-2 text-primary">{{ __('messages.order_deliver_action') }}</h4>
                            <p class="text-muted smaller fw-bold mb-4">{{ __('messages.order_deliver_help') ?? 'Submit your work to the client.' }}</p>
                            <form action="{{ route('orders.deliver', $order) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.delivery_note') }}</label>
                                    <textarea class="form-control bg-light border-0 rounded-4 p-3" name="delivery_note" rows="5" placeholder="{{ __('messages.order_delivery_note_placeholder') ?? 'Enter details about the delivery...' }}"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-black shadow-sm transition-all hover-translate-y">{{ __('messages.order_deliver_action') }}</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Offers List -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-black mb-0 text-dark small text-uppercase letter-spacing-1">{{ __('messages.order_offers_title') }}</h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3">{{ $order->offers_count }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($order->offers as $offer)
                            @include('theme::orders.partials.offer-card', ['offer' => $offer, 'order' => $order])
                        @empty
                            <div class="p-5 text-center text-muted bg-light bg-opacity-25">
                                <div class="mb-3">
                                    <i class="fa fa-handshake fa-4x opacity-10"></i>
                                </div>
                                <h6 class="fw-black mb-1 text-muted">{{ __('messages.order_no_offers_title') }}</h6>
                                <p class="mb-0 smaller fw-bold opacity-75">{{ __('messages.order_no_offers_copy') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-3">
            <x-widget-column side="portal_right" />
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .hover-scale:hover { transform: scale(1.05); }
    .hover-primary:hover { color: #615dfa !important; }
    .hover-bg-dark:hover { background-color: #212529 !important; }
    .bg-opacity-10 { background-color: rgba(97, 93, 250, 0.1) !important; }
    .breadcrumb-item + .breadcrumb-item::before { color: #dee2e6; }
    .list-group-item { border-color: rgba(0,0,0,0.05); }
    .form-select, .form-control { border-radius: 0.75rem; }
</style>
@endsection
