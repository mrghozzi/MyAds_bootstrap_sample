@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Section Header -->
    <div class="bg-body-tertiary p-4 rounded-3 mb-4 shadow-sm border">
        <h1 class="h3 fw-bold mb-0">
            <i class="fa fa-hashtag text-primary me-2"></i> {{ $tag }}
        </h1>
        <p class="text-muted mb-0 small">{{ __('messages.search_results') }} #{{ $tag }}</p>
    </div>

    <div class="row g-4">
        <!-- Topics Results -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title fw-bold mb-0">{{ __('messages.topics') }} ({{ $topics->total() }})</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($topics as $topic)
                            <a href="{{ route('forum.topic', $topic->id) }}" class="list-group-item list-group-item-action border-0 px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $topic->user ? $topic->user->avatarUrl() : asset('upload/_avatar.png') }}" alt="" class="rounded-circle me-3" width="40" height="40">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1 text-dark">{{ $topic->name }}</h6>
                                        <div class="text-muted small">
                                            {{ $topic->date_formatted }} {{ __('messages.by') }} {{ $topic->user->username ?? __('messages.unknown') }}
                                        </div>
                                    </div>
                                    <i class="fa fa-chevron-right text-muted small"></i>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fa fa-folder-open fa-2x mb-2 opacity-25"></i>
                                <p class="small mb-0">{{ __('messages.no_topics_found') }}</p>
                            </div>
                        @endforelse
                    </div>
                    
                    @if($topics->total() > 0)
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $topics->appends(['statuses_page' => $statuses->currentPage()])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activities Results -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title fw-bold mb-0">{{ __('messages.latest_updates') }} ({{ $statuses->total() }})</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($statuses as $status)
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex align-items-start">
                                    <a href="{{ route('profile.short', $status->user?->publicRouteIdentifier() ?? $status->uid) }}">
                                        <img src="{{ $status->user ? $status->user->avatarUrl() : asset('upload/_avatar.png') }}" alt="" class="rounded-circle me-3" width="40" height="40">
                                    </a>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <a href="{{ route('profile.short', $status->user?->publicRouteIdentifier() ?? $status->uid) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $status->user->username ?? __('messages.unknown') }}
                                            </a>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $status->date_formatted }}</small>
                                        </div>
                                        <div class="text-muted small mb-0">
                                            {!! \App\Support\ContentFormatter::linkifyHashtags(Str::limit($status->txt ?: $status->statu, 150)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fa fa-comment-slash fa-2x mb-2 opacity-25"></i>
                                <p class="small mb-0">{{ __('messages.no_activities') }}</p>
                            </div>
                        @endforelse
                    </div>

                    @if($statuses->total() > 0)
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $statuses->appends(['topics_page' => $topics->currentPage()])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
