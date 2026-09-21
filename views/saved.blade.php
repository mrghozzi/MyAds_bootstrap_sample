@extends('theme::layouts.master')

@push('head')
<style>
    .saved-page {
        --saved-card-bg: var(--bs-card-bg, #ffffff);
        --saved-border: var(--bs-border-color, #eaeaf5);
    }

    .saved-banner {
        background: linear-gradient(135deg, #615dfa 0%, #23d2e2 100%);
        border-radius: 1rem;
        padding: 2rem 2.5rem;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 8px 24px rgba(97, 93, 250, 0.2);
    }

    .saved-banner-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #fff;
        flex-shrink: 0;
    }

    .saved-banner-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0;
        color: #fff;
        letter-spacing: -0.02em;
    }

    .saved-banner-desc {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.85);
        margin: 4px 0 0;
    }

    .saved-empty-card {
        background: var(--saved-card-bg);
        border: 1px dashed var(--saved-border);
        border-radius: 1rem;
        padding: 4rem 1.5rem;
        text-align: center;
    }

    .saved-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: rgba(97, 93, 250, 0.1);
        color: #615dfa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="saved-page py-3">
    <!-- HERO BANNER -->
    <div class="saved-banner">
        <div class="saved-banner-icon">
            <i class="fa-solid fa-bookmark"></i>
        </div>
        <div>
            <h1 class="saved-banner-title">{{ __('messages.saved_posts') }}</h1>
            <p class="saved-banner-desc">{{ __('messages.saved_posts_desc') }}</p>
        </div>
    </div>

    <!-- MAIN GRID LAYOUT -->
    <div class="row g-4">
        <!-- LEFT SIDEBAR -->
        <div class="col-lg-3">
            <x-widget-column side="portal_left" />
        </div>

        <!-- CENTER CONTENT -->
        <div class="col-lg-6">
            @if($statuses->isNotEmpty())
                <div id="infinite-scroll-container" class="d-grid gap-3">
                    @foreach($statuses as $activity)
                        @include('theme::partials.activity.render', ['activity' => $activity])
                    @endforeach

                    @include('theme::partials.ajax.infinite_scroll', ['paginator' => $statuses])
                </div>
            @else
                <div class="saved-empty-card shadow-sm">
                    <div class="saved-empty-icon">
                        <i class="fa-regular fa-bookmark"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ __('messages.no_saved_posts_title') }}</h4>
                    <p class="text-muted mb-4 small mx-auto" style="max-width: 420px;">{{ __('messages.no_saved_posts_desc') }}</p>
                    <a href="{{ route('portal.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-compass"></i> {{ __('messages.explore_community') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="col-lg-3">
            <x-widget-column side="portal_right" />
        </div>
    </div>
</div>
@endsection
