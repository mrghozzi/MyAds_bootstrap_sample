@extends('theme::layouts.master')

@section('title', $page->title . ' - ' . ($site_settings->titer ?? 'MyAds'))

@section('content')
@php
    $hasLeftWidget = (bool) $page->widget_left;
    $hasRightWidget = (bool) $page->widget_right;
@endphp

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 px-4 rounded-pill shadow-sm border">
            <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-decoration-none text-primary fw-bold"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item active text-truncate small fw-bold" aria-current="page" style="max-width: 300px;">{{ $page->title }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        @if($hasLeftWidget)
            <div class="col-lg-3">
                <x-widget-column :side="$page->getLeftPlaceId()" />
            </div>
        @endif

        <div class="col-lg-{{ ($hasLeftWidget && $hasRightWidget) ? '6' : (($hasLeftWidget || $hasRightWidget) ? '9' : '12') }}">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4 p-md-5">
                    <header class="mb-5 pb-4 border-bottom">
                        <h1 class="fw-black text-dark display-5 mb-3">{{ $page->title }}</h1>
                        <div class="d-flex flex-wrap gap-2 align-items-center mt-3 text-muted smaller">
                            @if($page->updated_at)
                                <span class="badge bg-light text-muted border rounded-pill px-3 py-2 fw-bold">
                                    <i class="fa fa-clock me-1 text-primary"></i> {{ __('messages.updated') ?? 'Updated' }}: {{ $page->updated_at->format('M d, Y') }}
                                </span>
                            @endif
                            @if($page->meta_description)
                                <span class="ms-1 opacity-75">{{ \Illuminate\Support\Str::limit(strip_tags($page->meta_description), 150) }}</span>
                            @endif
                        </div>
                    </header>

                    <article class="page-content lh-lg text-dark fs-5">
                        {!! $page->content !!}
                    </article>
                </div>
            </div>
        </div>

        @if($hasRightWidget)
            <div class="col-lg-3">
                <x-widget-column :side="$page->getRightPlaceId()" />
            </div>
        @endif
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .page-content h2 { font-weight: 800; color: #1a1a1a; margin-top: 2.5rem; margin-bottom: 1.25rem; }
    .page-content h3 { font-weight: 800; color: #333; margin-top: 2rem; margin-bottom: 1rem; }
    .page-content p { margin-bottom: 1.5rem; }
    .page-content img { max-width: 100%; height: auto; border-radius: 1rem; margin: 2rem 0; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.05); }
    .page-content blockquote { padding: 1.5rem 2rem; background-color: #f8f9fa; border-left: 5px solid #615dfa; border-radius: 0.5rem; font-style: italic; margin: 2rem 0; }
    [dir="rtl"] .page-content blockquote { border-left: 0; border-right: 5px solid #615dfa; }
    .page-content ul, .page-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
    [dir="rtl"] .page-content ul, [dir="rtl"] .page-content ol { padding-left: 0; padding-right: 1.5rem; }
    .page-content li { margin-bottom: 0.5rem; }
    .page-content pre { background-color: #1e1f33; color: #fff; padding: 1.5rem; border-radius: 1rem; margin: 2rem 0; overflow-x: auto; }
    .page-content table { width: 100%; margin: 2rem 0; border-collapse: separate; border-spacing: 0; border: 1px solid #dee2e6; border-radius: 0.75rem; overflow: hidden; }
    .page-content th, .page-content td { padding: 1rem; border-bottom: 1px solid #dee2e6; }
    .page-content th { background-color: #f8f9fa; font-weight: 700; }
    .breadcrumb-item + .breadcrumb-item::before { color: #dee2e6; }
</style>
@endsection
