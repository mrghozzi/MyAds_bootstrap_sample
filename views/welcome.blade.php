@extends('theme::layouts.master')

@section('title', __('messages.welcome_title') . ' - ' . ($site_settings->titer ?? 'MyAds'))
@section('skip_footer_ad', '1')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="p-5 mb-4 bg-body-tertiary rounded-3 shadow-sm border">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4 fw-bold text-primary mb-3">
                {{ __('messages.landing_hero_title') }}
                <span class="text-secondary d-block">{{ __('messages.landing_hero_title_highlight') }}</span>
            </h1>
            <p class="col-md-8 mx-auto fs-5 text-muted mb-5">
                {{ __('messages.landing_hero_subtitle') }}
            </p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="{{ url('/register') }}" class="btn btn-primary btn-lg px-4 gap-3">
                    <i class="fa-solid fa-rocket me-2"></i> {{ __('messages.landing_hero_cta') }}
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> {{ __('messages.landing_hero_cta_login') }}
                </a>
            </div>
        </div>
    </div>

    @include('theme::partials.ads', ['id' => 1])

    <!-- Features Section -->
    <div class="row g-4 py-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary bg-gradient text-white fs-2 mb-3 rounded-3" style="width: 4rem; height: 4rem;">
                        <i class="fa-solid fa-rectangle-ad"></i>
                    </div>
                    <h3 class="fs-4 fw-bold">{{ __('messages.landing_feature_banners_title') }}</h3>
                    <p class="text-muted">{{ __('messages.landing_feature_banners_desc') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-info bg-gradient text-white fs-2 mb-3 rounded-3" style="width: 4rem; height: 4rem;">
                        <i class="fa-solid fa-align-left"></i>
                    </div>
                    <h3 class="fs-4 fw-bold">{{ __('messages.landing_feature_textads_title') }}</h3>
                    <p class="text-muted">{{ __('messages.landing_feature_textads_desc') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-success bg-gradient text-white fs-2 mb-3 rounded-3" style="width: 4rem; height: 4rem;">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                    <h3 class="fs-4 fw-bold">{{ __('messages.landing_feature_visits_title') }}</h3>
                    <p class="text-muted">{{ __('messages.landing_feature_visits_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Logic --}}
    @php
        try {
            $activeMembers = \App\Models\User::count();
            $activeAds = (\Illuminate\Support\Facades\Schema::hasTable('banner') ? \DB::table('banner')->where('statu', 1)->count() : 0)
                + (\Illuminate\Support\Facades\Schema::hasTable('link') ? \DB::table('link')->where('statu', 1)->count() : 0)
                + (\Illuminate\Support\Facades\Schema::hasTable('smart_ads') ? \DB::table('smart_ads')->where('statu', 1)->count() : 0)
                + (\Illuminate\Support\Facades\Schema::hasTable('visit') ? \DB::table('visit')->where('statu', 1)->count() : 0);
            $totalPosts = \App\Models\Status::where('statu', 1)->count()
                + \App\Models\ForumTopic::where('statu', 1)->count()
                + \App\Models\News::count()
                + \App\Models\Directory::where('statu', 1)->count()
                + \App\Models\Product::count()
                + \App\Models\OrderRequest::count();
            $totalEngagement = \App\Models\ForumComment::count()
                + \App\Models\Option::whereIn('o_type', ['d_coment', 's_coment', 'order_comment'])->count()
                + \App\Models\Like::where('type', '!=', 1)->count();
        } catch (\Exception $e) {
            $activeMembers = 0; $activeAds = 0; $totalPosts = 0; $totalEngagement = 0;
        }
    @endphp

    <!-- Stats Section -->
    <div class="row row-cols-1 row-cols-md-4 g-4 py-5 text-center bg-dark text-white rounded-3 shadow-lg">
        <div class="col">
            <h2 class="fw-bold mb-0">{{ number_format($activeMembers) }}</h2>
            <p class="text-secondary small text-uppercase">{{ __('messages.landing_stats_members') }}</p>
        </div>
        <div class="col">
            <h2 class="fw-bold mb-0">{{ number_format($activeAds) }}</h2>
            <p class="text-secondary small text-uppercase">{{ __('messages.landing_stats_ads') }}</p>
        </div>
        <div class="col">
            <h2 class="fw-bold mb-0">{{ number_format($totalPosts) }}</h2>
            <p class="text-secondary small text-uppercase">{{ __('messages.landing_stats_posts') }}</p>
        </div>
        <div class="col">
            <h2 class="fw-bold mb-0">{{ number_format($totalEngagement) }}</h2>
            <p class="text-secondary small text-uppercase">{{ __('messages.landing_stats_interaction') }}</p>
        </div>
    </div>

    <!-- How It Works -->
    <div class="py-5">
        <h2 class="text-center fw-bold mb-5">{{ __('messages.landing_how_title') }}</h2>
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="badge rounded-pill bg-primary fs-5 mb-3">1</div>
                <h4 class="fw-bold">{{ __('messages.landing_how_step1_title') }}</h4>
                <p class="text-muted">{{ __('messages.landing_how_step1_desc') }}</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="badge rounded-pill bg-primary fs-5 mb-3">2</div>
                <h4 class="fw-bold">{{ __('messages.landing_how_step2_title') }}</h4>
                <p class="text-muted">{{ __('messages.landing_how_step2_desc') }}</p>
            </div>
            <div class="col-md-4 text-center">
                <div class="badge rounded-pill bg-primary fs-5 mb-3">3</div>
                <h4 class="fw-bold">{{ __('messages.landing_how_step3_title') }}</h4>
                <p class="text-muted">{{ __('messages.landing_how_step3_desc') }}</p>
            </div>
        </div>
    </div>

    <!-- Final CTA -->
    <div class="py-5 text-center bg-primary bg-gradient text-white rounded-3 shadow mb-5">
        <h2 class="fw-bold mb-3">{{ __('messages.landing_cta_title') }}</h2>
        <p class="mb-4">{{ __('messages.landing_cta_subtitle') }}</p>
        <a href="{{ url('/register') }}" class="btn btn-light btn-lg px-5 fw-bold">
            <i class="fa-solid fa-user-plus me-2"></i> {{ __('messages.landing_cta_button') }}
        </a>
    </div>

</div>
@endsection
