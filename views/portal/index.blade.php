@extends('theme::layouts.master')

@push('head')
<style>
    .news-page {
        --news-card-bg: #ffffff;
        --news-soft: #f6f7ff;
        --news-text: #3e3f5e;
        --news-muted: #8f91ac;
        --news-border: #eaeaf5;
        --news-accent: #615dfa;
        --news-shadow: 0 14px 30px rgba(97, 93, 250, 0.08);
    }

    [data-theme="css_d"] .news-page {
        --news-card-bg: #1d2333;
        --news-soft: #22293d;
        --news-text: #ffffff;
        --news-muted: #9aa4bf;
        --news-border: #2f3749;
        --news-accent: #7750f8;
        --news-shadow: 0 14px 30px rgba(0, 0, 0, 0.3);
    }

    .transition-up {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .transition-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .news-page .simple-tab-items {
        display: flex;
        gap: 8px;
        border-bottom: 1px solid var(--news-border);
        padding-bottom: 0;
        margin: 24px 0;
        overflow-x: auto;
        scrollbar-width: none;
        align-items: center;
    }

    .news-page .simple-tab-items::-webkit-scrollbar {
        display: none;
    }

    .news-page .simple-tab-item {
        height: auto;
        padding: 12px 24px;
        color: var(--news-muted);
        font-size: 0.94rem;
        font-weight: 800;
        opacity: 0.8;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border-bottom: 4px solid transparent;
        margin-right: 0 !important;
        white-space: nowrap;
        border-radius: 12px 12px 0 0;
    }

    .news-page .simple-tab-item:hover {
        color: var(--news-accent);
        opacity: 1;
        background: rgba(97, 93, 250, 0.06);
    }

    .news-page .simple-tab-item.active {
        color: var(--news-text);
        opacity: 1;
        background: linear-gradient(180deg, rgba(97, 93, 250, 0.04) 0%, rgba(97, 93, 250, 0.01) 100%);
        border-bottom-color: var(--news-accent);
    }

    .smaller { font-size: 0.75rem; }
</style>
@endpush

@section('content')
<!-- SECTION BANNER -->
<div class="section-banner mb-4 py-5 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #615dfa 0%, #23d2e2 100%); color: #fff;">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">{{ __('messages.community') }}</h1>
        <p class="mb-0 opacity-75">{{ __('messages.latest_updates') }}</p>
    </div>
</div>

<div class="row g-4 news-page">
    <!-- LEFT SIDEBAR -->
    <div class="col-lg-3">
        <x-widget-column side="portal_left" />
    </div>

    <!-- MAIN FEED -->
    <div class="col-lg-6">
        @if(!empty($search))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0">{{ __('messages.search') }}: <span class="text-primary">"{{ $search }}"</span></h2>
                <a href="{{ route('portal.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa fa-times me-1"></i> {{ __('messages.clear') ?? 'Clear' }}
                </a>
            </div>

            <!-- Users Search Results -->
            @if(isset($searchedUsers) && $searchedUsers->count() > 0)
                <h5 class="fw-bold mb-3 mt-4 text-muted small text-uppercase">{{ __('messages.members') }}</h5>
                <div class="row g-3 mb-5">
                    @foreach($searchedUsers as $sUser)
                        <div class="col-6 col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center h-100 transition-up">
                                <div class="bg-primary bg-opacity-10 py-4">
                                    <a href="{{ route('profile.show', $sUser->username) }}" class="d-inline-block position-relative">
                                        <img src="{{ $sUser->avatarUrl() }}" alt="" class="rounded-circle border border-4 border-white shadow-sm" width="80" height="80">
                                        @if($sUser->isOnline())
                                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-2 border-white rounded-circle"></span>
                                        @endif
                                    </a>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-1 text-truncate">
                                        <a href="{{ route('profile.show', $sUser->username) }}" class="text-dark text-decoration-none">{{ $sUser->username }}</a>
                                    </h6>
                                    <p class="text-muted smaller mb-0">{{ $sUser->posts_count ?? 0 }} {{ __('messages.Posts') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Groups Search Results -->
            @if(isset($searchedGroups) && $searchedGroups->count() > 0)
                <h5 class="fw-bold mb-3 mt-4 text-muted small text-uppercase">{{ __('messages.groups_title') }}</h5>
                <div class="row g-3 mb-5">
                    @foreach($searchedGroups as $sGroup)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-up">
                                <div style="height: 80px; background: url({{ $sGroup->coverUrl() }}) center center / cover no-repeat;"></div>
                                <div class="card-body p-3 text-center position-relative">
                                    <div class="position-absolute top-0 start-50 translate-middle">
                                        <img src="{{ $sGroup->avatarUrl() }}" alt="" class="rounded-circle border border-3 border-white shadow-sm" width="60" height="60">
                                    </div>
                                    <div class="pt-4 mt-2">
                                        <h6 class="fw-bold mb-1 text-truncate">
                                            <a href="{{ route('groups.show', $sGroup) }}" class="text-dark text-decoration-none">{{ $sGroup->name }}</a>
                                        </h6>
                                        <p class="text-muted smaller mb-0">{{ $sGroup->members_count }} {{ __('messages.members') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Posts Search Results -->
            @if(isset($searchedStatuses) && $searchedStatuses->count() > 0)
                <h5 class="fw-bold mb-3 mt-4 text-muted small text-uppercase">{{ __('messages.posts') }}</h5>
                <div class="d-grid gap-3 mb-5">
                    @foreach($searchedStatuses as $activity)
                        @include('theme::partials.activity.render', ['activity' => $activity])
                    @endforeach
                </div>
            @endif

            <!-- Comments Search Results -->
            @if((isset($searchedCommentsForum) && $searchedCommentsForum->count() > 0) || (isset($searchedCommentsDir) && $searchedCommentsDir->count() > 0))
                <h5 class="fw-bold mb-3 mt-4 text-muted small text-uppercase">{{ __('messages.comments') }}</h5>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                    <div class="list-group list-group-flush">
                        <!-- Forum Comments -->
                        @if(isset($searchedCommentsForum))
                            @foreach($searchedCommentsForum as $fComment)
                                <div class="list-group-item p-4">
                                    <div class="d-flex gap-3">
                                        <img src="{{ $fComment->user ? $fComment->user->avatarUrl() : asset('upload/_avatar.png') }}" class="rounded-circle" width="40" height="40">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold mb-0">{{ $fComment->user->username ?? 'Unknown' }}</h6>
                                                <small class="text-muted smaller">{{ \Carbon\Carbon::createFromTimestamp($fComment->date)->diffForHumans() }}</small>
                                            </div>
                                            <p class="text-muted smaller mb-2">
                                                <i class="fa fa-comments me-1"></i> {{ __('messages.on_forum_topic') ?? 'on Forum Topic' }} 
                                                <a href="{{ route('forum.topic', $fComment->tid) }}" class="text-decoration-none">#{{ $fComment->tid }}</a>
                                            </p>
                                            <div class="p-3 bg-light rounded-3 small text-secondary">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($fComment->txt), 250) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <!-- Directory Comments -->
                        @if(isset($searchedCommentsDir))
                            @foreach($searchedCommentsDir as $dComment)
                                <div class="list-group-item p-4">
                                    <div class="d-flex gap-3">
                                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                            <i class="fa fa-comment"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ __('messages.directory_comment') ?? 'Directory Comment' }}</h6>
                                            <p class="text-muted smaller mb-2">
                                                <i class="fa fa-sitemap me-1"></i> {{ __('messages.on_directory') ?? 'on Directory' }} 
                                                <a href="{{ route('directory.show', $dComment->o_parent) }}" class="text-decoration-none">#{{ $dComment->o_parent }}</a>
                                            </p>
                                            <div class="p-3 bg-light rounded-3 small text-secondary">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($dComment->o_valuer), 250) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            @if(
                (!isset($searchedUsers) || $searchedUsers->count() == 0) &&
                (!isset($searchedStatuses) || $searchedStatuses->count() == 0) &&
                (!isset($searchedCommentsForum) || $searchedCommentsForum->count() == 0) &&
                (!isset($searchedCommentsDir) || $searchedCommentsDir->count() == 0)
            )
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                    <i class="fa fa-search fa-4x mb-4 opacity-10"></i>
                    <h5 class="fw-bold mb-1">{{ __('messages.no_results_found') ?? 'No results found.' }}</h5>
                    <p class="small">{{ __('messages.try_different_keywords') ?? 'Try searching with different keywords.' }}</p>
                </div>
            @endif

        @else
            @include('theme::partials.status.add_post')
            
            <!-- TABS -->
            @auth
            <div class="simple-tab-items">
                <a href="{{ route('portal.index', ['filter' => 'all']) }}" class="simple-tab-item {{ $filter == 'all' ? 'active' : '' }} text-decoration-none">{{ __('messages.all_updates') }}</a>
                <a href="{{ route('portal.index', ['filter' => 'me']) }}" class="simple-tab-item {{ $filter == 'me' ? 'active' : '' }} text-decoration-none">{{ __('messages.following') }}</a>
                @if(\App\Support\GroupSettings::isEnabled())
                <a href="{{ route('portal.index', ['filter' => 'groups']) }}" class="simple-tab-item {{ $filter == 'groups' ? 'active' : '' }} text-decoration-none">{{ __('messages.groups_title') }}</a>
                @endif
            </div>
            @endauth

            <!-- ACTIVITY LIST -->
            <div id="infinite-scroll-container" class="d-grid gap-3">
                @foreach($activities as $activity)
                    @include('theme::partials.activity.render', ['activity' => $activity])
                @endforeach
                
                @include('theme::partials.ajax.infinite_scroll', ['paginator' => $activities->appends(['filter' => $filter])])
            </div>
        @endif
    </div>

    <!-- RIGHT SIDEBAR -->
    <div class="col-lg-3">
        <x-widget-column side="portal_right" />
    </div>
</div>
@endsection
