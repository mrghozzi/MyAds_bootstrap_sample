@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                    <i class="fa fa-comments fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-bold mb-1">{{ __('messages.forum') }}</h1>
                    <p class="mb-0 text-white-50 small">{{ __('messages.forum_description') }}</p>
                </div>
            </div>
            @auth
                <a href="{{ route('forum.create') }}" class="btn btn-light btn-lg fw-bold shadow-sm mt-3 mt-md-0">
                    <i class="fa fa-plus me-2"></i> {{ __('messages.add') }}
                </a>
            @endauth
        </div>
    </div>

    @include('theme::partials.ads', ['id' => 4])

    <div class="row g-4 mt-2">
        <div class="col-lg-12">
            <!-- Forum Categories -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-dark">{{ __('messages.cat_s') }}</h5>
                    <span class="badge bg-light text-muted border fw-bold rounded-pill px-3">{{ $categories->count() }} {{ __('messages.categories') }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light bg-opacity-50 border-bottom text-muted smaller text-uppercase fw-bold letter-spacing-1">
                                <tr>
                                    <th class="ps-4 py-3">{{ __('messages.forum_board') }}</th>
                                    <th class="text-center py-3" style="width: 120px;">{{ __('messages.topics') }}</th>
                                    <th class="text-center py-3" style="width: 120px;">{{ __('messages.replies') ?? 'المساهمات' }}</th>
                                    <th class="pe-4 py-3" style="width: 350px;">{{ __('messages.latest_post') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    @php
                                        $topicCount = \App\Models\ForumTopic::where('cat', $category->id)->where('statu', 1)->count();
                                        $commentsCount = \App\Models\ForumComment::whereHas('topic', function($q) use ($category) { $q->where('cat', $category->id); })->count();
                                        $latestTopic = \App\Models\ForumTopic::where('cat', $category->id)->where('statu', 1)->orderBy('id', 'desc')->first();
                                        $latestDate = "";
                                        if ($latestTopic) {
                                            $status = \App\Models\Status::where('tp_id', $latestTopic->id)->where('s_type', 2)->first();
                                            if ($status) {
                                                $latestDate = \Carbon\Carbon::createFromTimestamp($status->date)->diffForHumans();
                                            }
                                        }
                                    @endphp
                                    <tr class="transition-all">
                                        <td class="ps-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3 shadow-sm border border-primary border-opacity-10" style="width: 58px; height: 58px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa {{ $category->icons ?: 'fa-comments' }} fs-3"></i>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="mb-1 fw-black">
                                                        <a href="{{ route('forum.category', $category->id) }}" class="text-dark text-decoration-none hover-primary">
                                                            {{ $category->name }}
                                                        </a>
                                                    </h5>
                                                    <p class="text-muted smaller mb-0 text-truncate" style="max-width: 500px;">{!! strip_tags($category->txt) !!}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="bg-light bg-opacity-75 rounded-4 p-2 shadow-sm border border-white">
                                                <div class="fw-black text-primary fs-5">{{ $topicCount }}</div>
                                                <div class="smaller text-muted text-uppercase fw-bold">{{ __('messages.topics') }}</div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="bg-light bg-opacity-75 rounded-4 p-2 shadow-sm border border-white">
                                                <div class="fw-black text-info fs-5">{{ $commentsCount }}</div>
                                                <div class="smaller text-muted text-uppercase fw-bold">{{ __('messages.replies') ?? 'المساهمات' }}</div>
                                            </div>
                                        </td>
                                        <td class="pe-4">
                                            @if($latestTopic)
                                                <div class="card border-0 bg-light bg-opacity-25 p-2 rounded-3">
                                                    <div class="d-flex align-items-center">
                                                        @if($latestTopic->user)
                                                            <img src="{{ $latestTopic->user->avatarUrl() }}" class="rounded-circle me-2 border border-2 border-white shadow-sm" width="32" height="32">
                                                        @endif
                                                        <div class="overflow-hidden">
                                                            <a href="{{ route('forum.topic', $latestTopic->id) }}" class="text-dark fw-bold text-decoration-none text-truncate d-block smaller mb-0">
                                                                {{ $latestTopic->name }}
                                                            </a>
                                                            <div class="d-flex align-items-center gap-2 smaller text-muted">
                                                                <span class="text-primary fw-bold">{{ $latestTopic->user?->username ?? __('messages.unknown_user') }}</span>
                                                                <span class="opacity-50">&middot;</span>
                                                                <span>{{ $latestDate }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center py-2">
                                                    <span class="text-muted small opacity-50">-</span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- FORUM STATS SUPERDESIGN -->
    @php
        $totalTopics = \App\Models\ForumTopic::count();
        $totalComments = \App\Models\ForumComment::count();
        $totalMembers = \App\Models\User::count();
        $latestMember = \App\Models\User::orderBy('id', 'desc')->first();
    @endphp
    <div class="d-flex align-items-center mt-5 mb-4">
        <h4 class="fw-bold mb-0 text-dark"><i class="fa fa-line-chart me-2"></i> إحصائيات المنتدى</h4>
    </div>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm text-center p-4" style="background: linear-gradient(135deg, #615dfa, #23d2e2); color: white;">
                <i class="fa fa-folder-open fa-3x mb-3 text-white opacity-75"></i>
                <h3 class="fw-black mb-1">{{ $totalTopics }}</h3>
                <div class="small fw-bold">عدد المواضيع</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm text-center p-4" style="background: linear-gradient(135deg, #fd4350, #ff8c42); color: white;">
                <i class="fa fa-comments fa-3x mb-3 text-white opacity-75"></i>
                <h3 class="fw-black mb-1">{{ $totalComments }}</h3>
                <div class="small fw-bold">عدد المساهمات</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm text-center p-4" style="background: linear-gradient(135deg, #1bc8db, #00d2ff); color: white;">
                <i class="fa fa-users fa-3x mb-3 text-white opacity-75"></i>
                <h3 class="fw-black mb-1">{{ $totalMembers }}</h3>
                <div class="small fw-bold">الأعضاء المسجلين</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 rounded-4 shadow-sm text-center p-4" style="background: linear-gradient(135deg, #44cc56, #28a745); color: white;">
                <i class="fa fa-user-plus fa-3x mb-3 text-white opacity-75"></i>
                <h3 class="fw-black mb-1 text-truncate">
                    @if($latestMember)
                        <a href="{{ route('profile.show', $latestMember->username) }}" class="text-white text-decoration-none hover-white">{{ $latestMember->username }}</a>
                    @else
                        -
                    @endif
                </h3>
                <div class="small fw-bold">أحدث عضو مسجل</div>
            </div>
        </div>
    </div>
    <!-- /FORUM STATS SUPERDESIGN -->
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .table-hover tbody tr:hover {
        background-color: rgba(97, 93, 250, 0.03);
    }
    .hover-primary:hover {
        color: #615dfa !important;
    }
</style>
@endsection
