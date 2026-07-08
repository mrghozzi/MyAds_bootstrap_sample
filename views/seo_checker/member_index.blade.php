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
                <p class="mb-0 opacity-75">{{ __('messages.seo_checker_desc') }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 fw-bold">{{ __('Navigation') }}</div>
                <div class="card-body">
                    <a href="{{ route('directory.index') }}" class="btn btn-outline-primary w-100 rounded-pill">
                        <i class="fa fa-home"></i> {{ __('messages.directory') }}
                    </a>
                </div>
            </div>
            <x-widget-column side="directory_left" />
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4 p-md-5">
                <h4 class="fw-bold mb-4">{{ __('messages.seo_check_website') }}</h4>
                <form action="{{ route('seo_checker.analyze') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="url" class="form-control form-control-lg bg-light border-0" name="url" placeholder="https://example.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm fw-bold">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> {{ __('messages.seo_analyze_now') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-3">
            <x-widget-column side="directory_right" />
        </div>
    </div>
</div>
@endsection
