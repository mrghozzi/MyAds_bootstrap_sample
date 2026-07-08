@extends('theme::layouts.master')

@section('content')
@php
    $listingImage = $listing->o_valuer ?: theme_asset('img/error_plug.png');
    $owner = $listing->user;
    $commentCount = \App\Models\Option::where('o_type', 'd_coment')->where('o_parent', $listing->id)->count();
    $reportKey = 'dir' . $listing->id;
@endphp

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 px-4 rounded-pill shadow-sm border">
            <li class="breadcrumb-item"><a href="{{ route('directory.index') }}" class="text-decoration-none text-info fw-bold"><i class="fa fa-sitemap"></i></a></li>
            @if($listing->category)
                <li class="breadcrumb-item"><a href="{{ route('directory.category', $listing->category->id) }}" class="text-decoration-none text-muted small">{{ $listing->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active text-truncate small fw-bold" aria-current="page" style="max-width: 300px;">{{ $listing->name }}</li>
        </ol>
    </nav>

    @include('theme::partials.ads', ['id' => 5])

    <div class="row g-4 mt-2 post{{ $listing->id }}">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                        <div class="d-flex gap-4 align-items-center">
                            <div class="bg-white p-2 rounded-4 shadow-sm border d-flex align-items-center justify-content-center transition-all hover-shadow-lg" style="width: 100px; height: 100px;">
                                <img src="{{ $listingImage }}" class="img-fluid rounded-3" alt="{{ $listing->name }}" style="max-height: 80px;">
                            </div>
                            <div>
                                <h1 class="fw-black mb-1 text-dark h2">{{ $listing->name }}</h1>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 rounded-pill px-3 py-1 smaller fw-bold">
                                        <i class="fa fa-globe me-1"></i> {{ __('messages.website') }}
                                    </span>
                                    <a href="{{ $listing->url }}" target="_blank" class="text-muted small text-decoration-none hover-primary">
                                        <i class="fa fa-link me-1"></i> {{ parse_url($listing->url, PHP_URL_HOST) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown" style="width: 36px; height: 36px; padding: 0;"><i class="fa fa-ellipsis-v"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                <li><button class="dropdown-item py-2 small fw-bold" onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa fa-copy me-2 text-muted"></i> {{ __('messages.copy_link') }}</button></li>
                                @if(auth()->check() && (auth()->id() == $listing->uid || auth()->user()->isAdmin()))
                                    <li><a class="dropdown-item py-2 small fw-bold" href="{{ route('directory.edit', $listing->id) }}"><i class="fa fa-edit me-2 text-muted"></i> {{ __('messages.edit') }}</a></li>
                                    <li><button class="dropdown-item py-2 small fw-bold text-danger" onclick="deletePost({{ $listing->id }}, 'directory', '.post{{ $listing->id }}')"><i class="fa fa-trash me-2"></i> {{ __('messages.delete') }}</button></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item py-2 small fw-bold text-muted" onclick="reportPost({{ $listing->id }}, 'directory')"><i class="fa fa-flag me-2"></i> {{ __('messages.report') }}</button></li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-light bg-opacity-50 p-4 rounded-4 mb-5 border">
                        <div class="lh-lg text-dark fs-5" style="white-space: pre-line;">{{ $listing->txt }}</div>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ $listing->url }}" target="_blank" class="btn btn-info btn-lg text-white rounded-pill px-5 fw-black shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-external-link-alt me-2"></i> {{ __('messages.visit_website') }}
                        </a>
                        <button class="btn btn-outline-info btn-lg rounded-pill px-5 fw-black shadow-sm transition-all hover-translate-y" onclick="toggleReaction({{ $listing->id }}, 'directory', 'like')" id="reaction-btn-{{ $listing->id }}">
                            <i class="fa fa-thumbs-up me-2"></i> {{ __('messages.like') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.comments') }}</h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3">{{ $commentCount }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="post-comment-list post-comment-list-{{ $listing->id }}" id="directory-comments-{{ $listing->id }}">
                        <div class="text-center py-5">
                            <div class="spinner-border text-info" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Website Stats Widget -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.website_info') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center justify-content-between py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded-3 me-3 text-muted">
                                    <i class="fa fa-calendar-alt"></i>
                                </div>
                                <span class="small text-muted fw-bold">{{ __('messages.added_on') }}</span>
                            </div>
                            <span class="small fw-black text-dark">{{ \Carbon\Carbon::createFromTimestamp($listing->date)->format('M d, Y') }}</span>
                        </div>
                        <div class="list-group-item d-flex align-items-center justify-content-between py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded-3 me-3 text-muted">
                                    <i class="fa fa-eye"></i>
                                </div>
                                <span class="small text-muted fw-bold">{{ __('messages.visits') }}</span>
                            </div>
                            <span class="badge bg-info text-white rounded-pill px-3">{{ number_format($listing->visits ?? 0) }}</span>
                        </div>
                        @if($listing->category)
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3 border-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-2 rounded-3 me-3 text-muted">
                                        <i class="fa fa-tag"></i>
                                    </div>
                                    <span class="small text-muted fw-bold">{{ __('messages.category') }}</span>
                                </div>
                                <a href="{{ route('directory.category', $listing->category->id) }}" class="small fw-black text-info text-decoration-none">{{ $listing->category->name }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Owner Card -->
            @if($owner)
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden text-center">
                    <div class="bg-info bg-opacity-10 py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-uppercase smaller text-info letter-spacing-1">{{ __('messages.added_by') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="d-block mb-3 position-relative">
                            <img src="{{ $owner->avatarUrl() }}" alt="" class="rounded-circle border border-4 border-white shadow-sm transition-all hover-scale" width="100" height="100" style="object-fit: cover;">
                        </a>
                        <h5 class="fw-black mb-1">
                            <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="text-dark text-decoration-none">{{ $owner->username }}</a>
                        </h5>
                        <p class="smaller text-muted mb-3">@ {{ $owner->username }}</p>
                        <div class="d-grid">
                            <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="btn btn-outline-info rounded-pill fw-bold shadow-sm transition-all hover-bg-info hover-text-white">{{ __('messages.view_profile') }}</a>
                        </div>
                    </div>
                </div>
            @endif

            <x-widget-column side="directory_sidebar" />
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-3px); }
    .hover-shadow-lg:hover { box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important; }
    .hover-scale:hover { transform: scale(1.05); }
    .hover-bg-info:hover { background-color: #0dcaf0 !important; }
    .hover-text-white:hover { color: #fff !important; }
    .post-comment-list img { max-width: 100%; border-radius: 12px; }
    .breadcrumb-item + .breadcrumb-item::before { color: #dee2e6; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof loadComments === 'function') {
            loadComments({{ $listing->id }}, 'directory');
        }
    });
</script>
@endsection
