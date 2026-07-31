@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- HERO BANNER -->
    <div class="card border-0 shadow-lg mb-4 text-white rounded-4 overflow-hidden position-relative" 
         style="background: linear-gradient(135deg, #615dfa 0%, #4338ca 60%, #1e1b4b 100%);">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap position-relative z-1">
            <div class="d-flex align-items-center me-3 mb-3 mb-md-0">
                <div class="bg-white bg-opacity-15 p-3 p-md-4 rounded-4 me-3 border border-white border-opacity-20 shadow-sm backdrop-blur">
                    <i class="fa fa-shopping-cart fa-3x text-white"></i>
                </div>
                <div>
                    <h1 class="h2 fw-black mb-1 text-white tracking-tight">{{ __('messages.store') }}</h1>
                    <p class="mb-2 opacity-85 small fw-medium">{{ __('messages.store_banner_desc') ?? 'Discover themes, scripts, and plugins.' }}</p>
                    @auth
                        <div class="d-inline-flex align-items-center bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 px-3 py-1-5 rounded-pill shadow-xs">
                            <i class="fa fa-coins me-2 text-warning"></i>
                            <span class="smaller fw-bold">{{ __('messages.pts') }}: {{ number_format((float) auth()->user()->pts, 2) }} PTS</span>
                        </div>
                    @endauth
                </div>
            </div>
            @auth
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('store.discounts.index') }}" class="btn btn-outline-light btn-md fw-bold shadow-sm px-3.5 py-2 rounded-pill backdrop-blur hover-lift">
                        <i class="fa fa-ticket me-1.5"></i> {{ __('messages.discount_codes') ?? 'Discount Codes' }}
                    </a>
                    <a href="{{ route('store.create') }}" class="btn btn-success btn-md fw-bold shadow-sm px-3.5 py-2 rounded-pill hover-lift" style="background-color: #10b981; border-color: #10b981;">
                        <i class="fa fa-plus me-1.5"></i> {{ __('messages.add_product') }}
                    </a>
                </div>
            @endauth
        </div>
        <!-- Decorative Ambient Graphic -->
        <div class="position-absolute top-0 end-0 p-5 opacity-10 pointer-events-none d-none d-lg-block">
            <i class="fa fa-layer-group" style="font-size: 14rem;"></i>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="fa fa-check-circle me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- CATEGORIES HEADER -->
    <div class="d-flex justify-content-between align-items-end mb-3 pb-2 border-bottom">
        <div>
            <span class="text-uppercase text-primary fw-bold smaller tracking-wider d-block mb-1">
                <a href="{{ route('store.index') }}" class="text-decoration-none text-primary">
                    {{ __('messages.search_what_you_want') ?? 'Search what you want!' }}
                </a>
            </span>
            <h2 class="h4 fw-black mb-0">
                <a href="{{ route('store.index') }}" class="text-decoration-none text-body">
                    {{ __('messages.market_categories') ?? 'Market Categories' }}
                </a>
            </h2>
        </div>
    </div>

    <!-- CATEGORIES GRID -->
    @php
        $isScriptSpecific = isset($scriptName) && $scriptName !== 'all';
        $allCategories = \App\Support\StoreCategoryCatalog::selectable();
        
        $categoryMeta = [
            'script' => ['name' => 'script', 'icon' => 'fa-code', 'bg' => 'linear-gradient(135deg, #615dfa 0%, #4338ca 100%)'],
            'themes' => ['name' => 'themes', 'icon' => 'fa-paint-brush', 'bg' => 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)'],
            'plugins' => ['name' => 'plugins', 'icon' => 'fa-plug', 'bg' => 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)'],
            'graphics' => ['name' => 'graphics', 'icon' => 'fa-palette', 'bg' => 'linear-gradient(135deg, #db2777 0%, #be185d 100%)'],
            'audio' => ['name' => 'audio', 'icon' => 'fa-music', 'bg' => 'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)'],
            'video' => ['name' => 'video', 'icon' => 'fa-video', 'bg' => 'linear-gradient(135deg, #ea580c 0%, #c2410c 100%)'],
            'ebooks' => ['name' => 'ebooks', 'icon' => 'fa-book', 'bg' => 'linear-gradient(135deg, #059669 0%, #047857 100%)'],
            'software' => ['name' => 'software', 'icon' => 'fa-laptop-code', 'bg' => 'linear-gradient(135deg, #d97706 0%, #b45309 100%)'],
            'courses' => ['name' => 'courses', 'icon' => 'fa-graduation-cap', 'bg' => 'linear-gradient(135deg, #0891b2 0%, #0e7490 100%)'],
        ];
    @endphp

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 mb-5">
        @foreach($allCategories as $catKey)
            @php
                $meta = $categoryMeta[$catKey] ?? ['name' => $catKey, 'icon' => 'fa-folder', 'bg' => 'linear-gradient(135deg, #4b5563 0%, #1f2937 100%)'];
                $catUrl = $isScriptSpecific 
                    ? route('store.script_category', [$scriptName, $catKey]) 
                    : route('store.index', ['category' => $catKey]);
                $isActive = ($category ?? '') === $catKey;
                $count = $categoryCounts[$catKey] ?? 0;
            @endphp
            <div class="col">
                <a href="{{ $catUrl }}" 
                   class="card border-0 shadow-sm rounded-4 text-white p-3 h-100 text-decoration-none transition-all hover-translate-y position-relative overflow-hidden cat-card {{ $isActive ? 'ring-active' : '' }}" 
                   style="background: {{ $meta['bg'] }};">
                    <div class="d-flex justify-content-between align-items-start mb-3 position-relative z-1">
                        <div class="bg-white bg-opacity-20 p-2-5 rounded-3 shadow-xs">
                            <i class="fa {{ $meta['icon'] }} fs-4"></i>
                        </div>
                        <span class="badge bg-black bg-opacity-30 text-white rounded-pill px-2.5 py-1 border border-white border-opacity-20 fw-bold smaller">
                            {{ number_format($count) }}
                        </span>
                    </div>
                    <div class="position-relative z-1 mt-auto">
                        <h3 class="h6 fw-black mb-0 text-white leading-tight">
                            {{ __('messages.' . $catKey) != 'messages.' . $catKey ? __('messages.' . $catKey) : ucfirst($catKey) }}
                        </h3>
                        <span class="smaller opacity-80 fw-medium d-block mt-0.5">{{ __('messages.products') ?? 'Products' }}</span>
                    </div>
                    <!-- Background Icon Watermark -->
                    <div class="position-absolute end-0 bottom-0 p-2 opacity-15 pointer-events-none">
                        <i class="fa {{ $meta['icon'] }}" style="font-size: 4rem; transform: rotate(-10deg) translate(10px, 10px);"></i>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- PRODUCTS SECTION HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom flex-wrap gap-2">
        <div>
            @php
                $pretitleUrl = $isScriptSpecific ? route('store.script_category', [$scriptName, 'all']) : route('store.index');
                $titleUrl = $isScriptSpecific 
                    ? ($category ? route('store.script_category', [$scriptName, $category]) : route('store.script_category', [$scriptName, 'all']))
                    : ($category ? route('store.index', ['category' => $category]) : route('store.index'));
            @endphp
            <span class="text-uppercase text-muted fw-bold smaller tracking-wider d-block mb-0.5">
                <a href="{{ $pretitleUrl }}" class="text-decoration-none text-muted">
                    {{ __('messages.see_whats_new') ?? "See what's new!" }}
                </a>
            </span>
            <h2 class="h3 fw-black mb-0 text-body">
                <a href="{{ $titleUrl }}" class="text-decoration-none text-body">
                    @if($category ?? false)
                        {{ __('messages.' . $category) != 'messages.' . $category ? __('messages.' . $category) : ucfirst($category) }}
                    @else
                        {{ __('messages.latest_items') ?? 'Latest Items' }}
                    @endif
                </a>
            </h2>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($category ?? false)
                @php
                    $allUrl = (isset($scriptName) && $scriptName !== 'all') 
                        ? route('store.script_category', ['script' => $scriptName, 'category' => 'all'])
                        : route('store.index');
                @endphp
                <a href="{{ $allUrl }}" class="btn btn-light rounded-pill px-3 py-1.5 fw-bold shadow-xs border text-body text-decoration-none">
                    <i class="fa fa-th me-1 text-primary"></i> {{ __('messages.all') ?? 'All' }}
                </a>
            @endif
            @auth
                <a href="{{ route('store.discounts.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 fw-bold shadow-xs d-none d-sm-inline-flex align-items-center">
                    <i class="fa fa-tags me-1.5 text-primary"></i> {{ __('messages.discount_codes') ?? 'Discount Codes' }}
                </a>
                <a href="{{ route('store.create') }}" class="btn btn-primary rounded-pill px-3 py-1.5 fw-bold shadow-xs d-inline-flex align-items-center">
                    <i class="fa fa-plus me-1.5"></i> {{ __('messages.add_product') }}
                </a>
            @endauth
        </div>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        @forelse($products as $product)
            @php
                $latestFile = \App\Models\ProductFile::where('o_parent', $product->id)->orderBy('id', 'desc')->first();
                $owner = $product->user;
                $ownerAvatar = $owner ? $owner->avatarUrl() : asset('upload/_avatar.png');
                $productImage = $product->product_image ?: theme_asset('img/error_plug.png');
                $prodScript = $product->associated_script_name;
                $catName = $product->type ? $product->type->name : null;
                $categoryLink = '#';
                if ($catName) {
                    $targetScript = $isScriptSpecific ? $scriptName : $prodScript;
                    $categoryLink = $targetScript 
                        ? route('store.script_category', [$targetScript, $catName])
                        : route('store.index', ['category' => $catName]);
                }
            @endphp
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card transition-all hover-shadow-lg hover-translate-y bg-body">
                    <!-- Image & Badges Container -->
                    <a href="{{ route('store.show', $product->name) }}" class="position-relative d-block overflow-hidden bg-body-tertiary" style="padding-top: 62.5%;">
                        <img src="{{ $productImage }}" 
                             class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover transition-all prod-img" 
                             alt="{{ $product->name }}"
                             onerror="this.src='{{ theme_asset('img/error_plug.png') }}'">
                        
                        <!-- Badges Overlay -->
                        <div class="position-absolute top-0 start-0 p-2-5 d-flex flex-wrap gap-1.5 z-2">
                            @if($product->o_order > 0)
                                @if($product->sale && $product->sale->is_active)
                                    <span class="badge bg-danger text-white fw-black rounded-pill px-2.5 py-1.5 shadow-sm d-inline-flex align-items-center gap-1">
                                        <i class="fa fa-tags"></i>
                                        <span class="text-decoration-line-through opacity-75 smaller">{{ number_format($product->o_order) }}</span>
                                        <span>{{ number_format($product->sale->sale_price) }} PTS</span>
                                    </span>
                                @else
                                    <span class="badge bg-primary text-white fw-black rounded-pill px-2.5 py-1.5 shadow-sm">
                                        {{ number_format($product->o_order) }} PTS
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-success text-white fw-black rounded-pill px-2.5 py-1.5 shadow-sm">
                                    {{ __('messages.free') }}
                                </span>
                            @endif

                            @if($product->is_suspended)
                                <span class="badge bg-dark text-white fw-black rounded-pill px-2.5 py-1.5 shadow-sm text-uppercase">
                                    {{ __('messages.suspended') }}
                                </span>
                            @endif
                        </div>
                    </a>

                    <!-- Details -->
                    <div class="card-body p-3.5 d-flex flex-column">
                        @if($catName)
                            <div class="mb-1.5">
                                <a href="{{ $categoryLink }}" class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 text-decoration-none fw-bold smaller text-uppercase">
                                    {{ __('messages.' . $catName) != 'messages.' . $catName ? __('messages.' . $catName) : ucfirst($catName) }}
                                </a>
                            </div>
                        @endif

                        <h3 class="h6 card-title fw-black mb-1.5 leading-snug">
                            <a href="{{ route('store.show', $product->name) }}" class="text-body text-decoration-none hover-primary d-block text-truncate-2">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p class="text-muted smaller mb-3 text-truncate-2 opacity-80" style="min-height: 38px; line-height: 1.45;">
                            {{ strip_tags($product->o_valuer) }}
                        </p>
                        
                        <!-- Footer / Author & Version -->
                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2.5 border-top">
                            <div class="d-flex align-items-center text-truncate me-2">
                                <a href="{{ $owner ? route('profile.show', $owner->username) : '#' }}" class="me-2 text-decoration-none flex-shrink-0">
                                    <img src="{{ $ownerAvatar }}" alt="" class="rounded-circle border shadow-xs" width="24" height="24" style="object-fit: cover;">
                                </a>
                                <span class="smaller text-muted fw-bold text-truncate">
                                    @if($owner)
                                        <a href="{{ route('profile.show', $owner->username) }}" class="text-muted text-decoration-none hover-primary">
                                            {{ $owner->username }}
                                        </a>
                                    @else
                                        {{ __('messages.unknown') }}
                                    @endif
                                </span>
                            </div>
                            @if($latestFile)
                                <span class="badge bg-body-tertiary text-secondary border rounded-pill px-2 py-1 fw-bold smaller flex-shrink-0" title="Latest Version">
                                    {{ $latestFile->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-body-tertiary">
                    <div class="mb-3">
                        <i class="fa fa-box-open fa-4x text-muted opacity-30"></i>
                    </div>
                    <h4 class="fw-bold text-body mb-2">{{ __('messages.no_products_found') ?? 'No products found' }}</h4>
                    <p class="text-muted small mb-3">{{ __('messages.try_adjusting_filters') ?? 'Try selecting a different category or search term.' }}</p>
                    @if($category ?? false)
                        <div>
                            <a href="{{ route('store.index') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-xs">
                                <i class="fa fa-refresh me-1.5"></i> {{ __('messages.all') ?? 'View All Products' }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4 mb-3">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<style>
    .fw-black { font-weight: 800; }
    .tracking-tight { letter-spacing: -0.02em; }
    .tracking-wider { letter-spacing: 0.05em; }
    .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .hover-translate-y:hover { transform: translateY(-4px); }
    .hover-lift:hover { transform: translateY(-2px); }
    .hover-shadow-lg:hover { box-shadow: 0 1rem 2.5rem rgba(0,0,0,.12) !important; }
    .hover-primary:hover { color: #615dfa !important; }
    .backdrop-blur { backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); }
    
    .ring-active {
        box-shadow: 0 0 0 3px rgba(97, 93, 250, 0.35), 0 8px 20px rgba(0,0,0,0.15) !important;
        transform: translateY(-2px);
    }
    
    .cat-card {
        min-height: 120px;
        display: flex;
        flex-direction: column;
    }
    
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-card:hover .prod-img {
        transform: scale(1.06);
    }
    
    .shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .p-1-5 { padding: 0.375rem; }
    .p-2-5 { padding: 0.625rem; }
    .p-3-5 { padding: 0.875rem; }
    .py-1-5 { padding-top: 0.375rem; padding-bottom: 0.375rem; }
    .px-3-5 { padding-left: 0.875rem; padding-right: 0.875rem; }
</style>
@endsection
