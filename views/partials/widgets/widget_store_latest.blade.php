@php
    $products = \App\Models\Product::withoutGlobalScope('store')->where('o_type', 'store')->latest('id')->limit(5)->get();
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @forelse($products as $product)
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border" style="width: 38px; height: 38px; min-width: 38px;">
                        <img src="{{ theme_asset('img/marketplace/category/all-01.png') }}" width="20" height="20">
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="mb-0 fw-bold small text-truncate">
                            <a href="{{ route('store.show', $product->name) }}" class="text-dark text-decoration-none hover-primary">{{ $product->name }}</a>
                        </h6>
                        <small class="text-muted smaller d-block text-truncate">
                            @if($product->o_order > 0)
                                <span class="text-success fw-bold">{{ number_format($product->pts ?? $product->o_order) }} {{ __('messages.pts') }}</span>
                            @else
                                <span class="text-secondary fw-bold">{{ __('messages.free') }}</span>
                            @endif
                            &bull; {{ __('messages.seller') }}: {{ $product->user?->username ?? 'Admin' }}
                        </small>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted small my-3">{{ __('messages.no_products_found') }}</p>
            @endforelse
        </div>
        <a href="{{ route('store.index') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-3 fw-bold">
            {{ __('messages.see_whats_new') }}
        </a>
    </div>
</div>
