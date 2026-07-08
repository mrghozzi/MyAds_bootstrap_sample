@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-tags fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.discount_codes') ?? 'Discount Codes' }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.manage_store_discounts_desc') ?? 'Create and manage promo codes for your products.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-tag fa-10x"></i>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h4 class="fw-black mb-0 text-dark">{{ __('messages.my_discounts') ?? 'My Discount Codes' }}</h4>
        <a href="{{ route('store.discounts.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
            <i class="fa fa-plus me-1"></i>
            {{ __('messages.create_discount') ?? 'Create Coupon' }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-4" role="alert">
            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                <i class="fa fa-check-circle text-success fs-4"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-1">{{ __('messages.success') ?? 'Success' }}</div>
                <div class="small text-muted fw-bold">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-6 letter-spacing-1">
                        <tr>
                            <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.coupon_name') ?? 'Name' }}</th>
                            <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.coupon_code') ?? 'Code' }}</th>
                            <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.coupon_value') ?? 'Value' }}</th>
                            <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.applies_to') ?? 'Applies To' }}</th>
                            <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.coupon_uses') ?? 'Uses' }}</th>
                            <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.coupon_dates') ?? 'Validity Period' }}</th>
                            <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.status') ?? 'Status' }}</th>
                            <th class="border-0 px-4 py-3 fw-black text-muted text-end">{{ __('messages.actions') ?? 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discounts as $discount)
                            <tr>
                                <td class="px-4 py-3 border-light">
                                    <span class="fw-bold text-dark">{{ $discount->name }}</span>
                                </td>
                                <td class="px-4 py-3 border-light">
                                    <code class="bg-light text-primary border rounded px-2 py-1 fw-bold fs-6">{{ $discount->code }}</code>
                                </td>
                                <td class="px-4 py-3 border-light">
                                    @if($discount->discount_type === 'percent')
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 border border-primary border-opacity-10">{{ $discount->discount_value }}%</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 border border-success border-opacity-10">{{ $discount->discount_value }} PTS</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-light text-muted small fw-bold">
                                    @if($discount->applies_to === 'all')
                                        <span><i class="fa fa-globe me-1 opacity-50"></i>{{ __('messages.all_my_products') ?? 'All my products' }}</span>
                                    @elseif($discount->applies_to === 'product')
                                        @php
                                            $targetProd = \App\Models\Product::withoutGlobalScope('store')->find($discount->target_value);
                                        @endphp
                                        @if($targetProd)
                                            <a href="{{ route('store.show', $targetProd->name) }}" class="text-primary text-decoration-none fw-bold" target="_blank">
                                                <i class="fa fa-shopping-bag me-1 opacity-50"></i>{{ $targetProd->name }}
                                            </a>
                                        @else
                                            <span class="text-danger"><i class="fa fa-exclamation-circle me-1"></i>{{ __('messages.unknown_product') ?? 'Deleted Product' }}</span>
                                        @endif
                                    @else
                                        <span>{{ $discount->applies_to }} ({{ $discount->target_value }})</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-light small fw-bold">
                                    <span class="text-dark">{{ $discount->uses }}</span>
                                    <span class="text-muted">/</span>
                                    @if($discount->max_uses)
                                        <span class="text-muted">{{ $discount->max_uses }}</span>
                                    @else
                                        <span class="text-muted">&infin;</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-light text-muted small fw-bold">
                                    @if($discount->start_date || $discount->end_date)
                                        <div>
                                            @if($discount->start_date)
                                                <div class="mb-1"><span class="badge bg-light text-muted border py-1">{{ __('messages.from') ?? 'From' }}</span> {{ $discount->start_date->format('Y-m-d H:i') }}</div>
                                            @endif
                                            @if($discount->end_date)
                                                <div><span class="badge bg-light text-muted border py-1">{{ __('messages.to') ?? 'To' }}</span> {{ $discount->end_date->format('Y-m-d H:i') }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <span>&infin; {{ __('messages.always_valid') ?? 'Always valid' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-light">
                                    @if(!$discount->is_active)
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 border border-danger border-opacity-10">{{ __('messages.inactive') ?? 'Inactive' }}</span>
                                    @elseif($discount->end_date && $discount->end_date->isPast())
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 border border-secondary border-opacity-10">{{ __('messages.expired') ?? 'Expired' }}</span>
                                    @elseif($discount->max_uses && $discount->uses >= $discount->max_uses)
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 border border-warning border-opacity-10">{{ __('messages.limit_reached') ?? 'Limit Reached' }}</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 border border-success border-opacity-10">{{ __('messages.active') ?? 'Active' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-light text-end">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('store.discounts.edit', $discount->id) }}" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center p-2 shadow-sm transition-all hover-translate-y" style="width: 32px; height: 32px;" title="{{ __('messages.edit') ?? 'Edit' }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('store.discounts.destroy', $discount->id) }}" onclick="return confirm('{{ __('messages.confirm_delete') ?? 'Are you sure you want to delete this?' }}')" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center p-2 shadow-sm transition-all hover-translate-y" style="width: 32px; height: 32px;" title="{{ __('messages.delete') ?? 'Delete' }}">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="rounded-circle bg-light p-4 d-inline-flex mb-4">
                                        <i class="fa fa-tags fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <h4 class="fw-black text-dark">{{ __('messages.no_discounts_found') ?? 'No discounts found' }}</h4>
                                    <p class="text-muted small mb-0 fw-bold">{{ __('messages.no_discounts_found_desc') ?? 'You have not created any discount codes yet.' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(method_exists($discounts, 'links'))
        <div class="mt-4 d-flex justify-content-center">
            {!! $discounts->links() !!}
        </div>
    @endif
</div>
@endsection
