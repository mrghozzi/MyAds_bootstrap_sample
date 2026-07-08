@extends('theme::layouts.master')
@include('theme::directory._assets')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white" style="background: linear-gradient(135deg, #1e1b4b, #4f46e5);">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="me-4 d-none d-md-block">
                <i class="fa-solid fa-magnifying-glass-chart fa-4x opacity-50"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-2">{{ __('messages.seo_checker') }}</h2>
                <p class="mb-0 opacity-75">{{ __('messages.seo_analysis_complete', ['url' => $results['url']]) }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 fw-bold">{{ __('Navigation') }}</div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('seo_checker.index') }}" class="btn btn-primary rounded-pill">
                            <i class="fa fa-arrow-left"></i> {{ __('messages.seo_new_check') }}
                        </a>
                        <a href="{{ route('directory.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fa fa-home"></i> {{ __('messages.directory') }}
                        </a>
                    </div>
                </div>
            </div>
            <x-widget-column side="directory_left" />
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-0 overflow-hidden">
                @include('theme::seo_checker._results_content', ['results' => $results, 'settings' => $settings, 'userRole' => $userRole])
            </div>
        </div>

        <div class="col-lg-3">
            <x-widget-column side="directory_right" />
        </div>
    </div>
</div>
@endsection
