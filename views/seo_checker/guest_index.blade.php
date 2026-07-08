@php
    $seo = (object) [
        'title' => __('messages.seo_checker'),
        'robots' => 'index, follow'
    ];
@endphp
@extends('theme::layouts.oauth')

@section('title', __('messages.seo_checker'))

@section('content')
<div class="card border-0 shadow-lg rounded-4 overflow-hidden">
    <div class="card-body p-5 text-center">
        <h1 class="fw-bold mb-3">{{ __('messages.seo_checker') }}</h1>
        <p class="text-muted mb-4">{{ __('messages.seo_checker_desc') }}</p>

        <form action="{{ route('seo_checker.analyze') }}" method="POST" class="mx-auto" style="max-width: 500px;">
            @csrf
            <div class="mb-4 text-start">
                <input type="url" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" name="url" placeholder="https://example.com" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(135deg, #615dfa, #23d2e2); border: none;">
                <i class="fa-solid fa-magnifying-glass me-2"></i> {{ __('messages.seo_analyze_now') }}
            </button>
        </form>
    </div>
</div>
@endsection
