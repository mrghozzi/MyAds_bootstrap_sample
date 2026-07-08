@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-tag fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ $discount->exists ? (__('messages.edit_discount') ?? 'Edit Discount Code') : (__('messages.create_discount') ?? 'Create Discount Code') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.manage_store_discounts_desc') ?? 'Create and manage promo codes for your products.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-tag fa-10x"></i>
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('store.discounts.index') }}" class="text-decoration-none fw-bold text-muted small d-inline-flex align-items-center gap-2">
            <i class="fa fa-arrow-left"></i>
            {{ __('messages.back_to_list') ?? 'Back to list' }}
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">
                        {{ $discount->exists ? (__('messages.edit_discount_details') ?? 'Edit Coupon Details') : (__('messages.create_new_discount') ?? 'Create New Coupon') }}
                    </h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-start p-4" role="alert">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fa fa-exclamation-circle text-danger fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-2">{{ __('messages.validation_errors') ?? 'Validation Errors' }}</div>
                                <ul class="small text-muted fw-bold mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form method="post" action="{{ $discount->exists ? route('store.discounts.update', $discount->id) : route('store.discounts.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-dark mb-2">{{ __('messages.coupon_name') ?? 'Coupon Name / Description' }} <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control rounded-3 p-3 shadow-sm border border-light" value="{{ old('name', $discount->name) }}" placeholder="e.g. 20% Winter Sale" required>
                        </div>

                        <!-- Code -->
                        <div class="mb-4">
                            <label for="code" class="form-label fw-bold text-dark mb-2">{{ __('messages.coupon_code') ?? 'Promo Code' }} <span class="text-danger">*</span></label>
                            <input type="text" id="code" name="code" class="form-control rounded-3 p-3 shadow-sm border border-light text-uppercase font-monospace fw-bold" value="{{ old('code', $discount->code) }}" placeholder="e.g. WINTER20" required>
                            <div class="form-text small text-muted mt-2">{{ __('messages.coupon_code_help') ?? 'The code customers enter at checkout (e.g. SAVE50). Only alphanumeric characters.' }}</div>
                        </div>

                        <div class="row g-4">
                            <!-- Type -->
                            <div class="col-md-6 mb-4">
                                <label for="discount_type" class="form-label fw-bold text-dark mb-2">{{ __('messages.coupon_type') ?? 'Discount Type' }} <span class="text-danger">*</span></label>
                                <select id="discount_type" name="discount_type" class="form-select rounded-3 p-3 shadow-sm border border-light" required>
                                    <option value="percent" {{ old('discount_type', $discount->discount_type) === 'percent' ? 'selected' : '' }}>{{ __('messages.percentage') ?? 'Percentage (%)' }}</option>
                                    <option value="fixed" {{ old('discount_type', $discount->discount_type) === 'fixed' ? 'selected' : '' }}>{{ __('messages.fixed_points') ?? 'Fixed Points (PTS)' }}</option>
                                </select>
                            </div>

                            <!-- Value -->
                            <div class="col-md-6 mb-4">
                                <label for="discount_value" class="form-label fw-bold text-dark mb-2">{{ __('messages.coupon_value') ?? 'Discount Value' }} <span class="text-danger">*</span></label>
                                <input type="number" id="discount_value" name="discount_value" class="form-control rounded-3 p-3 shadow-sm border border-light" value="{{ old('discount_value', $discount->discount_value) }}" min="1" required>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Scope -->
                            <div class="col-md-6 mb-4">
                                <label for="scope" class="form-label fw-bold text-dark mb-2">{{ __('messages.applies_to') ?? 'Applies To' }} <span class="text-danger">*</span></label>
                                <select id="scope" name="scope" class="form-select rounded-3 p-3 shadow-sm border border-light" required>
                                    <option value="all_my_products" {{ old('scope', $discount->applies_to === 'all' ? 'all_my_products' : 'all_my_products') === 'all_my_products' ? 'selected' : '' }}>{{ __('messages.all_my_products') ?? 'All My Products' }}</option>
                                    <option value="one_of_my_products" {{ old('scope', $discount->applies_to === 'product' ? 'one_of_my_products' : '') === 'one_of_my_products' ? 'selected' : '' }}>{{ __('messages.one_of_my_products') ?? 'Specific Product' }}</option>
                                </select>
                            </div>

                            <!-- Product selection -->
                            <div class="col-md-6 mb-4" id="product-select-wrapper" style="display: none;">
                                <label for="product_id" class="form-label fw-bold text-dark mb-2">{{ __('messages.select_product') ?? 'Select Product' }} <span class="text-danger">*</span></label>
                                <select id="product_id" name="product_id" class="form-select rounded-3 p-3 shadow-sm border border-light">
                                    <option value="">-- {{ __('messages.choose_product') ?? 'Choose Product' }} --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $discount->target_value) == $product->id ? 'selected' : '' }}>{{ $product->name }} ({{ $product->o_order }} PTS)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Start Date -->
                            <div class="col-md-6 mb-4">
                                <label for="start_date" class="form-label fw-bold text-dark mb-2">{{ __('messages.start_date') ?? 'Start Date' }}</label>
                                <input type="datetime-local" id="start_date" name="start_date" class="form-control rounded-3 p-3 shadow-sm border border-light" value="{{ old('start_date', $discount->start_date ? $discount->start_date->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            <!-- End Date -->
                            <div class="col-md-6 mb-4">
                                <label for="end_date" class="form-label fw-bold text-dark mb-2">{{ __('messages.end_date') ?? 'End Date' }}</label>
                                <input type="datetime-local" id="end_date" name="end_date" class="form-control rounded-3 p-3 shadow-sm border border-light" value="{{ old('end_date', $discount->end_date ? $discount->end_date->format('Y-m-d\TH:i') : '') }}">
                            </div>
                        </div>

                        <!-- Max Uses -->
                        <div class="mb-4">
                            <label for="max_uses" class="form-label fw-bold text-dark mb-2">{{ __('messages.max_uses') ?? 'Max Uses' }}</label>
                            <input type="number" id="max_uses" name="max_uses" class="form-control rounded-3 p-3 shadow-sm border border-light" value="{{ old('max_uses', $discount->max_uses) }}" min="1" placeholder="e.g. 100 (Leave blank for unlimited)">
                        </div>

                        <!-- Is Active (Only on Edit) -->
                        @if($discount->exists)
                            <div class="mb-4">
                                <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center gap-3">
                                    <input type="checkbox" id="is_active" name="is_active" class="form-check-input ms-0" value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }} style="cursor: pointer; width: 40px; height: 20px;">
                                    <label class="form-check-label fw-bold text-dark mb-0" for="is_active" style="cursor: pointer;">{{ __('messages.is_active') ?? 'Active and available for checkout' }}</label>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top border-light">
                            <a href="{{ route('store.discounts.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                                {{ __('messages.cancel') ?? 'Cancel' }}
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
                                {{ $discount->exists ? (__('messages.save_changes') ?? 'Save Changes') : (__('messages.create_discount') ?? 'Create Coupon') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scopeSelect = document.getElementById('scope');
    const productWrapper = document.getElementById('product-select-wrapper');
    const productIdInput = document.getElementById('product_id');
    const codeInput = document.getElementById('code');

    function toggleProductSelect() {
        if (scopeSelect.value === 'one_of_my_products') {
            productWrapper.style.display = 'block';
            productIdInput.required = true;
        } else {
            productWrapper.style.display = 'none';
            productIdInput.required = false;
            productIdInput.value = '';
        }
    }

    // Run on change
    scopeSelect.addEventListener('change', toggleProductSelect);

    // Run once on load
    toggleProductSelect();

    // Uppercase code input as typed
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
});
</script>
@endsection
