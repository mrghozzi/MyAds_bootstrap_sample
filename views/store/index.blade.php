@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-dark bg-gradient text-white rounded-4 overflow-hidden position-relative">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap position-relative z-1">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-10 p-3 rounded-4 me-3 border border-white border-opacity-25 shadow-sm">
                    <i class="fa fa-shopping-cart fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-black mb-1">{{ __('messages.store') }}</h1>
                    @auth
                        <div class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill mt-1">
                            <i class="fa fa-gift me-2"></i> <span class="smaller fw-bold">{{ __('messages.pts') }}: {{ number_format(auth()->user()->pts) }} PTS</span>
                        </div>
                    @endauth
                </div>
            </div>
            @auth
                <a href="{{ route('store.create') }}" class="btn btn-primary btn-lg fw-black shadow px-4 rounded-pill mt-3 mt-md-0">
                    <i class="fa fa-plus me-2"></i> {{ __('messages.add_product') }}
                </a>
            @endauth
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-shopping-bag fa-10x"></i>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm border-0" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Categories Grid -->
    <div class="row g-4 mb-5">
        @php
            $isScriptSpecific = isset($scriptName) && $scriptName !== 'all';
            $categories_list = [
                ['slug' => 'script', 'name' => 'script', 'icon' => 'fa-code', 'color' => '#615dfa'],
                ['slug' => 'themes', 'name' => 'themes', 'icon' => 'fa-paint-brush', 'color' => '#417ae1'],
                ['slug' => 'plugins', 'name' => 'plugins', 'icon' => 'fa-plug', 'color' => '#2ebfef'],
            ];
        @endphp
        
        @foreach($categories_list as $cat_item)
            <div class="col-md-4">
                <a href="{{ $isScriptSpecific ? route('store.script_category', [$scriptName, $cat_item['slug']]) : route('store.index', ['category' => $cat_item['slug']]) }}" 
                   class="card border-0 shadow-sm rounded-4 text-white p-4 h-100 text-decoration-none text-center transition-all hover-translate-y" 
                   style="background: {{ $cat_item['color'] }}; background: linear-gradient(135deg, {{ $cat_item['color'] }} 0%, rgba(255,255,255,0.2) 100%);">
                    <div class="mb-3 d-inline-flex bg-white bg-opacity-25 p-3 rounded-circle shadow-sm">
                        <i class="fa {{ $cat_item['icon'] }} fa-3x"></i>
                    </div>
                    <h4 class="fw-black mb-1">{{ __('messages.' . $cat_item['name']) }}</h4>
                    <p class="mb-0 opacity-75 small fw-bold">{{ $categoryCounts[$cat_item['slug']] ?? 0 }} {{ __('messages.products') }}</p>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Latest Items Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="h3 fw-black mb-1 text-dark">
                @if($category ?? false)
                    {{ __('messages.' . $category) }}
                @else
                    {{ __('messages.latest_items') }}
                @endif
            </h2>
            <p class="text-muted small mb-0 fw-bold">{{ __('messages.see_whats_new') }}</p>
        </div>
        @if($category ?? false)
            <a href="{{ (isset($scriptName) && $scriptName !== 'all') ? route('store.script_category', ['script' => $scriptName, 'category' => 'all']) : route('store.index') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm border">
                <i class="fa fa-th me-2 text-primary"></i> {{ __('messages.all') }}
            </a>
        @endif
    </div>

    <!-- Products Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
        @forelse($products as $product)
            @php
                $latestFile = \App\Models\ProductFile::where('o_parent', $product->id)->orderBy('id', 'desc')->first();
                $owner = $product->user;
                $productImage = $product->product_image ?: theme_asset('img/error_plug.png');
            @endphp
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card transition-all hover-shadow-lg hover-translate-y">
                    <a href="{{ route('store.show', $product->name) }}" class="position-relative d-block overflow-hidden" style="height: 200px;">
                        <img src="{{ $productImage }}" class="card-img-top h-100 w-100 object-fit-cover transition-all" alt="{{ $product->name }}">
                        <div class="position-absolute top-0 end-0 p-3">
                            @if($product->o_order > 0)
                                <span class="badge bg-primary fw-black rounded-pill px-3 py-2 shadow-sm">{{ number_format($product->o_order) }} PTS</span>
                            @else
                                <span class="badge bg-success fw-black rounded-pill px-3 py-2 shadow-sm">{{ __('messages.free') }}</span>
                            @endif
                        </div>
                        @if($product->is_suspended)
                            <div class="position-absolute top-0 start-0 p-3">
                                <span class="badge bg-danger fw-black rounded-pill px-3 py-2 shadow-sm text-uppercase">{{ __('messages.suspended') }}</span>
                            </div>
                        @endif
                    </a>
                    <div class="card-body p-4 d-flex flex-column">
                        <h6 class="card-title fw-black mb-2 fs-5">
                            <a href="{{ route('store.show', $product->name) }}" class="text-dark text-decoration-none d-block text-truncate hover-primary">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <p class="text-muted smaller mb-4 text-truncate-2 opacity-75" style="height: 40px; line-height: 1.5;">{{ strip_tags($product->o_valuer) }}</p>
                        
                        <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                            <div class="d-flex align-items-center">
                                <img src="{{ $owner ? $owner->avatarUrl() : asset('upload/_avatar.png') }}" alt="" class="rounded-circle me-2 border shadow-sm" width="28" height="28" style="object-fit: cover;">
                                <span class="smaller text-muted fw-bold">{{ $owner?->username ?? __('messages.unknown') }}</span>
                            </div>
                            @if($latestFile)
                                <span class="badge bg-light text-primary border rounded-pill px-2 fw-bold smaller">{{ $latestFile->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-50">
                    <div class="mb-3">
                        <i class="fa fa-shopping-basket fa-4x text-muted opacity-25"></i>
                    </div>
                    <h5 class="fw-bold text-muted">{{ __('messages.sieanpr') }}</h5>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<style>
    .fw-black { font-weight: 900; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .hover-shadow-lg:hover { box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important; }
    .hover-primary:hover { color: #615dfa !important; }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-card:hover .card-img-top { transform: scale(1.1); }
    .object-fit-cover { object-fit: cover; }
    .letter-spacing-1 { letter-spacing: 1px; }
</style>
@endsection
