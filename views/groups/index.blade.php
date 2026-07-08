@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                    <i class="fa fa-users fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-bold mb-1">{{ __('messages.groups_title') }}</h1>
                    <p class="mb-0 text-white-50 small">{{ __('messages.groups_discover_description') }}</p>
                </div>
            </div>
            @auth
                @if($creationEligibility && $creationEligibility['allowed'])
                    <a href="{{ route('groups.create') }}" class="btn btn-light btn-lg fw-bold shadow-sm mt-3 mt-md-0">
                        <i class="fa fa-plus me-2"></i> {{ __('messages.groups_create_title') }}
                    </a>
                @endif
            @endauth
        </div>
    </div>

    @include('theme::partials.ads', ['id' => 4])

    <div class="row g-4 mt-2">
        <!-- Sidebar Left -->
        <div class="col-lg-3">
            <x-widget-column side="groups_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-6">
            <!-- Search & Intro -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h4 class="fw-bold mb-3">{{ __('messages.groups_find_your_corner') }}</h4>
                <p class="text-muted small mb-4">{{ __('messages.groups_index_intro') }}</p>
                
                <form method="GET" action="{{ route('groups.index') }}">
                    <div class="input-group input-group-lg">
                        <input type="search" name="search" class="form-control bg-light border-0" value="{{ $search }}" placeholder="{{ __('messages.groups_search_placeholder') }}">
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            @auth
                @if($myGroups->isNotEmpty())
                    <h5 class="fw-bold mb-3 px-1"><i class="fa fa-star text-warning me-2"></i>{{ __('messages.groups_my_groups') }}</h5>
                    <div class="row g-3 mb-5">
                        @foreach($myGroups as $group)
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-translate-y transition-all">
                                    <div class="position-relative" style="height: 100px; background: linear-gradient(135deg, #615dfa 0%, #23d2e2 100%);">
                                        @if($group->cover)
                                            <img src="{{ $group->coverUrl() }}" class="w-100 h-100 object-fit-cover opacity-50">
                                        @endif
                                        <div class="position-absolute top-100 start-50 translate-middle">
                                            <img src="{{ $group->avatarUrl() }}" class="rounded-circle border border-4 border-white shadow-sm" width="64" height="64">
                                        </div>
                                    </div>
                                    <div class="card-body pt-5 text-center">
                                        <h6 class="fw-bold mb-1"><a href="{{ route('groups.show', $group) }}" class="text-dark text-decoration-none">{{ $group->name }}</a></h6>
                                        <p class="text-muted smaller mb-3 text-truncate-2" style="height: 35px;">{{ $group->short_description ?: Str::limit(strip_tags((string) $group->description), 80) }}</p>
                                        
                                        <div class="d-flex justify-content-center gap-3 border-top pt-3">
                                            <div class="text-center">
                                                <div class="fw-bold small">{{ $group->members_count }}</div>
                                                <div class="smaller text-muted">{{ __('messages.members') }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="fw-bold small">{{ $group->posts_count }}</div>
                                                <div class="smaller text-muted">{{ __('messages.posts') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-0 p-3">
                                        <a href="{{ route('groups.show', $group) }}" class="btn btn-primary w-100 rounded-pill fw-bold btn-sm shadow-sm">{{ __('messages.groups_open_group') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endauth

            <h5 class="fw-bold mb-3 px-1">{{ $search !== '' ? __('messages.groups_search_results') : __('messages.groups_discover_groups') }}</h5>
            <div class="row g-4">
                @forelse($groups as $group)
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-translate-y transition-all">
                            <div class="p-3 d-flex align-items-center gap-3">
                                <img src="{{ $group->avatarUrl() }}" class="rounded-circle shadow-sm" width="50" height="50">
                                <div class="overflow-hidden">
                                    <h6 class="fw-bold mb-0 text-truncate"><a href="{{ route('groups.show', $group) }}" class="text-dark text-decoration-none">{{ $group->name }}</a></h6>
                                    <span class="smaller text-muted">
                                        <i class="fa {{ $group->privacy === \App\Models\Group::PRIVACY_PUBLIC ? 'fa-globe' : 'fa-lock' }} me-1"></i>
                                        {{ $group->privacy === \App\Models\Group::PRIVACY_PUBLIC ? __('messages.groups_public') : __('messages.groups_private') }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body py-2">
                                <p class="text-muted smaller mb-0 text-truncate-2" style="height: 35px;">{{ $group->short_description ?: Str::limit(strip_tags((string) $group->description), 100) }}</p>
                            </div>
                            <div class="card-footer bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                                <div class="smaller fw-bold text-muted">
                                    <span class="me-2"><i class="fa fa-users me-1 opacity-50"></i>{{ $group->members_count }}</span>
                                    <span><i class="fa fa-comment-alt me-1 opacity-50"></i>{{ $group->posts_count }}</span>
                                </div>
                                <a href="{{ route('groups.show', $group) }}" class="btn btn-outline-primary rounded-pill btn-sm px-3 fw-bold">{{ __('messages.groups_open_group') }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="bg-light rounded-4 p-5">
                            <i class="fa fa-users fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0 text-muted">{{ __('messages.groups_empty_state') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $groups->links() }}
            </div>
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-3">
            <x-widget-column side="groups_right" />
        </div>
    </div>
</div>

<style>
    .hover-translate-y { transition: transform 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .object-fit-cover { object-fit: cover; }
    .transition-all { transition: all 0.3s ease; }
</style>
@endsection
