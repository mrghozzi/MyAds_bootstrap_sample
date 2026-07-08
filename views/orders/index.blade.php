@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap position-relative z-1">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                    <i class="fa fa-briefcase fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-black mb-1 text-white">{{ $pageTitle ?? __('messages.order_requests') }}</h1>
                    <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ $pageSubtitle ?? __('messages.browse_latest_orders') }}</p>
                </div>
            </div>
            @if(($showCreateCta ?? false) && auth()->check())
                <a href="{{ route('orders.create') }}" class="btn btn-light btn-lg fw-black shadow-sm mt-3 mt-md-0 px-4 rounded-pill">
                    <i class="fa fa-plus me-2 text-primary"></i> {{ __('messages.post_new_order') }}
                </a>
            @endif
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-handshake fa-10x"></i>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Sidebar Left -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.filters') }}</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $filterAction ?? route('orders.index') }}" method="GET" class="row g-3">
                        <div class="col-12">
                            <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.search') }}</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-0"><i class="fa fa-search text-muted opacity-50"></i></span>
                                <input type="text" name="search" class="form-control form-control-sm bg-light border-0" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('messages.order_search_placeholder') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.category') }}</label>
                            <select name="category" class="form-select form-select-sm bg-light border-0">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($categories as $categoryOption)
                                    <option value="{{ $categoryOption->slug }}" @selected(($filters['category'] ?? '') === $categoryOption->slug)>{{ $categoryOption->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select form-select-sm bg-light border-0">
                                @foreach(['all', 'open', 'under_review', 'awarded', 'in_progress', 'delivered', 'completed', 'closed', 'cancelled'] as $statusOption)
                                    <option value="{{ $statusOption }}" @selected(($filters['status'] ?? 'all') === $statusOption)>{{ $statusOption === 'all' ? __('messages.all') : __('messages.order_status_' . $statusOption) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.sort') }}</label>
                            <select name="sort" class="form-select form-select-sm bg-light border-0">
                                <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>{{ __('messages.most_recent') }}</option>
                                <option value="active" @selected(($filters['sort'] ?? '') === 'active')>{{ __('messages.most_active') }}</option>
                                <option value="popular" @selected(($filters['sort'] ?? '') === 'popular')>{{ __('messages.order_sort_popular_offers') }}</option>
                                <option value="budget_high" @selected(($filters['sort'] ?? '') === 'budget_high')>{{ __('messages.order_sort_budget_high') }}</option>
                                <option value="budget_low" @selected(($filters['sort'] ?? '') === 'budget_low')>{{ __('messages.order_sort_budget_low') }}</option>
                            </select>
                        </div>
                        <div class="col-12 d-grid mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm transition-all hover-translate-y">{{ __('messages.filter') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <x-widget-column side="portal_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-6">
            <div class="orders-feed d-grid gap-3">
                @forelse($orders as $order)
                    @include('theme::orders.partials.card', ['order' => $order])
                @empty
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-50">
                        <div class="mb-3">
                            <i class="fa fa-briefcase fa-4x text-muted opacity-25"></i>
                        </div>
                        <h4 class="fw-black text-muted">{{ __('messages.no_orders_found') }}</h4>
                        <p class="text-muted small mb-0">{{ __('messages.order_empty_state_copy') }}</p>
                    </div>
                @endforelse
            </div>

            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
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
    .hover-translate-y:hover { transform: translateY(-3px); }
    .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2) !important; }
    .form-select-sm, .form-control-sm { border-radius: 0.5rem; }
    .input-group-text { border-radius: 0.5rem 0 0 0.5rem; }
    .input-group > .form-control { border-radius: 0 0.5rem 0.5rem 0; }
</style>
@endsection
