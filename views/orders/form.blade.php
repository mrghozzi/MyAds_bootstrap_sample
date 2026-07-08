@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                <i class="fa {{ $isEditing ? 'fa-edit' : 'fa-briefcase' }} fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-bold mb-1">{{ $isEditing ? __('messages.order_edit_title') : __('messages.post_new_order') }}</h1>
                <p class="mb-0 text-white-50 small">{{ $isEditing ? __('messages.order_edit_subtitle') : __('messages.fill_order_details') }}</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.order_form_title') }}</h6>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('errMSG'))
                        <div class="alert alert-danger rounded-4 mb-4">{{ session('errMSG') }}</div>
                    @endif

                    <form action="{{ $isEditing ? route('orders.update', $order) : route('orders.store') }}" method="POST">
                        @csrf
                        @if($isEditing)
                            @method('PATCH')
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-bold" for="title">{{ __('messages.title') }}</label>
                            <input class="form-control form-control-lg rounded-3" id="title" type="text" name="title" value="{{ old('title', $order->title) }}" required placeholder="{{ __('messages.title') }}...">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" for="description">{{ __('messages.description') }}</label>
                            <textarea class="form-control rounded-4 p-3" id="description" name="description" rows="6" required placeholder="{{ __('messages.description') }}...">{{ old('description', $order->description) }}</textarea>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="category">{{ __('messages.category') }}</label>
                                <select class="form-select rounded-3" id="category" name="category">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" @selected(old('category', $order->category ?: 'uncategorized') === $category->slug)>{{ $category->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="pricing_model">{{ __('messages.pricing') }}</label>
                                <select class="form-select rounded-3" id="pricing_model" name="pricing_model">
                                    @foreach(['fixed', 'range', 'negotiable'] as $pricingModel)
                                        <option value="{{ $pricingModel }}" @selected(old('pricing_model', $order->pricing_model ?: 'fixed') === $pricingModel)>{{ __('messages.order_pricing_model_' . $pricingModel) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="budget_min">{{ __('messages.order_budget_min') }}</label>
                                <input class="form-control rounded-3" id="budget_min" type="number" step="0.01" min="0" name="budget_min" value="{{ old('budget_min', $order->budget_min) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="budget_max">{{ __('messages.order_budget_max') }}</label>
                                <input class="form-control rounded-3" id="budget_max" type="number" step="0.01" min="0" name="budget_max" value="{{ old('budget_max', $order->budget_max) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="budget_currency">{{ __('messages.currency') }}</label>
                                <select class="form-select rounded-3" id="budget_currency" name="budget_currency">
                                    @foreach($currencies as $currencyCode => $currencyLabel)
                                        <option value="{{ $currencyCode }}" @selected(old('budget_currency', $order->budget_currency ?: 'USD') === $currencyCode)>{{ $currencyLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="delivery_window_days">{{ __('messages.delivery') }} ({{ __('messages.days') }})</label>
                                <input class="form-control rounded-3" id="delivery_window_days" type="number" min="1" max="365" name="delivery_window_days" value="{{ old('delivery_window_days', $order->delivery_window_days) }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-4 border-top">
                            <a href="{{ $isEditing ? route('orders.show', $order) : route('orders.index') }}" class="btn btn-light rounded-pill px-4 fw-bold">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fa fa-paper-plane me-2"></i> {{ $isEditing ? __('messages.save_changes') : __('messages.publish_order') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top" style="top: 2rem;">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.preview') }}</h6>
                </div>
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-2" id="preview-title">{{ old('title', $order->title ?: __('messages.order_preview_title')) }}</h5>
                    <p class="text-muted small mb-4">{{ __('messages.order_preview_copy') }}</p>
                    
                    <div class="list-group list-group-flush border rounded-4 overflow-hidden">
                        <div class="list-group-item bg-light p-3 d-flex justify-content-between align-items-center border-0">
                            <span class="text-muted small fw-bold">{{ __('messages.category') }}</span>
                            <span class="badge bg-primary rounded-pill">{{ \App\Support\OrderCategoryOptions::label(old('category', $order->category ?: 'uncategorized')) }}</span>
                        </div>
                        <div class="list-group-item p-3 d-flex justify-content-between align-items-center border-0">
                            <span class="text-muted small fw-bold">{{ __('messages.pricing') }}</span>
                            <span class="small fw-bold">{{ __('messages.order_pricing_model_' . old('pricing_model', $order->pricing_model ?: 'fixed')) }}</span>
                        </div>
                        <div class="list-group-item bg-light p-3 d-flex justify-content-between align-items-center border-0">
                            <span class="text-muted small fw-bold">{{ __('messages.budget') }}</span>
                            <span class="small fw-bold text-success">
                                @php
                                    $previewMin = old('budget_min', $order->budget_min);
                                    $previewMax = old('budget_max', $order->budget_max);
                                    $previewCurrency = old('budget_currency', $order->budget_currency ?: 'USD');
                                    $previewPricing = old('pricing_model', $order->pricing_model ?: 'fixed');
                                @endphp
                                @if($previewPricing === 'negotiable' || ($previewMin === null && $previewMax === null))
                                    {{ __('messages.order_budget_negotiable') }}
                                @elseif((float) $previewMin === (float) $previewMax)
                                    {{ $previewCurrency }} {{ number_format((float) $previewMin, 2) }}
                                @else
                                    {{ __('messages.order_budget_range_value', ['currency' => $previewCurrency, 'min' => number_format((float) $previewMin, 2), 'max' => number_format((float) $previewMax, 2)]) }}
                                @endif
                            </span>
                        </div>
                        <div class="list-group-item p-3 d-flex justify-content-between align-items-center border-0">
                            <span class="text-muted small fw-bold">{{ __('messages.delivery') }}</span>
                            <span class="small fw-bold">
                                @if(old('delivery_window_days', $order->delivery_window_days))
                                    {{ __('messages.order_delivery_days_value', ['days' => old('delivery_window_days', $order->delivery_window_days)]) }}
                                @else
                                    {{ __('messages.order_delivery_flexible') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <x-widget-column side="portal_right" />
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const previewTitle = document.getElementById('preview-title');
    
    if (titleInput && previewTitle) {
        titleInput.addEventListener('input', function() {
            previewTitle.textContent = this.value || '{{ __('messages.order_preview_title') }}';
        });
    }
});
</script>
@endsection
