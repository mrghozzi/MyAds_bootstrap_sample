@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                    <i class="fa {{ $category->icons ?: 'fa-comments' }} fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-black mb-1">{{ $category->name }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('forum.index') }}" class="text-white text-opacity-75 text-decoration-none small">{{ __('messages.forum') }}</a></li>
                            <li class="breadcrumb-item active text-white small" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            @auth
                <a href="{{ route('forum.create') }}?cat={{ $category->id }}" class="btn btn-light btn-lg fw-bold shadow-sm mt-3 mt-md-0">
                    <i class="fa fa-plus me-2"></i> {{ __('messages.w_new_tpc') }}
                </a>
            @endauth
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Left -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.board') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('forum.index') }}" class="list-group-item list-group-item-action py-3 border-0 transition-all">
                            <i class="fa fa-home me-2 text-primary opacity-50"></i> <span class="small fw-bold">{{ __('messages.forum') }}</span>
                        </a>
                        @auth
                            <a href="{{ route('forum.create') }}" class="list-group-item list-group-item-action py-3 border-0 transition-all">
                                <i class="fa fa-plus me-2 text-success opacity-50"></i> <span class="small fw-bold">{{ __('messages.w_new_tpc') }}</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            
            @include('theme::forum.partials.category_sidebar', ['sidebarCategories' => $sidebarCategories])
            <x-widget-column side="forum_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-6">
            <div class="d-grid gap-3">
                @forelse($statuses as $status)
                    @php
                        $topic = $topics->get($status->tp_id);
                        if(!$topic) continue;
                    @endphp
                    @include('theme::partials.forum.topic_card', ['topic' => $topic, 'status' => $status])
                @empty
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-50">
                        <div class="mb-3">
                            <i class="fa fa-comments fa-4x text-muted opacity-25"></i>
                        </div>
                        <h5 class="fw-bold text-muted">{{ __('messages.no_topics_found') }}</h5>
                        <p class="text-muted small mb-0">{{ __('messages.be_the_first_to_post') }}</p>
                    </div>
                @endforelse
            </div>

            @if($statuses->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $statuses->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-3">
            <x-widget-column side="forum_right" />
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .list-group-item-action:hover {
        background-color: rgba(97, 93, 250, 0.05);
        color: #615dfa;
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.5);
    }
</style>
@endsection
