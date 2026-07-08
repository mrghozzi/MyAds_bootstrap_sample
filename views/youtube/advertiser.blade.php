@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%; background-size: cover;">
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}" alt="overview-icon">
    <p class="section-banner-title">{{ __('messages.yt_advertiser') }}</p>
    <p class="section-banner-text">{{ __('messages.yt_manage_campaigns') }}</p>
</div>

<div class="d-flex flex-wrap gap-2 mt-4 mb-3">
    <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4"><i class="fa fa-arrow-left me-2"></i> {{ __('messages.back') }}</a>
    <a href="{{ route('youtube.exchange.index') }}" class="btn btn-primary rounded-pill fw-bold px-4"><i class="fa-brands fa-youtube me-2"></i> {{ __('messages.yt_watch_earn_btn') }}</a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm fw-bold"><i class="fa fa-check-circle me-2"></i> {{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm fw-bold">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <!-- Create Campaign Form -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-danger bg-opacity-10 py-3 px-4 border-bottom-0 d-flex align-items-center">
                <div class="bg-danger text-white rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="fa-brands fa-youtube fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">{{ __('messages.yt_create_campaign') }}</h5>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('youtube.advertiser.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="youtube_url" class="form-label small fw-bold">{{ __('messages.yt_video_url') }}</label>
                            <input type="url" id="youtube_url" name="youtube_url" class="form-control form-control-lg bg-light border-0" required placeholder="https://www.youtube.com/watch?v=..." value="{{ old('youtube_url') }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="duration_required" class="form-label small fw-bold">{{ __('messages.yt_duration_req') }}</label>
                            <input type="number" id="duration_required" name="duration_required" class="form-control form-control-lg bg-light border-0" required min="15" max="600" value="{{ old('duration_required', 30) }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="reward_points" class="form-label small fw-bold">{{ __('messages.yt_reward_per_view') }}</label>
                            <input type="number" id="reward_points" name="reward_points" class="form-control form-control-lg bg-light border-0" required step="0.01" min="0.01" value="{{ old('reward_points') }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="total_budget" class="form-label small fw-bold">{{ __('messages.yt_total_budget') }}</label>
                            <input type="number" id="total_budget" name="total_budget" class="form-control form-control-lg bg-light border-0" required step="0.01" min="1" value="{{ old('total_budget') }}">
                            <div class="form-text small fw-bold text-muted mt-2">
                                <i class="fa fa-info-circle me-1"></i> {{ __('messages.yt_available_pts', ['pts' => number_format((float)auth()->user()->pts, 2)]) }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa fa-plus me-2"></i> {{ __('messages.yt_add_campaign') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Active Campaigns List -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-4 px-4 border-bottom">
                <h5 class="fw-bold text-dark mb-0">{{ __('messages.yt_active_campaigns') }}</h5>
            </div>
            <div class="card-body p-0">
                @if($videos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small fw-bold text-uppercase">
                                <tr>
                                    <th class="px-4 py-3 border-0">{{ __('messages.yt_video') }}</th>
                                    <th class="py-3 border-0">{{ __('messages.yt_duration') }}</th>
                                    <th class="py-3 border-0">{{ __('messages.yt_reward') }}</th>
                                    <th class="py-3 border-0">{{ __('messages.yt_budget_remaining') }}</th>
                                    <th class="py-3 border-0">{{ __('messages.yt_status') }}</th>
                                    <th class="px-4 py-3 border-0 text-end">{{ __('messages.yt_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($videos as $video)
                                    <tr>
                                        <td class="px-4 py-3 border-bottom border-light">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $video->thumbnail_url }}" alt="thumb" class="rounded-3 shadow-sm object-fit-cover" style="width: 80px; height: 50px;">
                                                <a href="https://youtube.com/watch?v={{ $video->youtube_id }}" target="_blank" class="text-primary fw-bold text-decoration-none">
                                                    {{ $video->youtube_id }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="py-3 border-bottom border-light fw-bold text-dark">{{ $video->duration_required }}s</td>
                                        <td class="py-3 border-bottom border-light fw-bold text-success">+{{ $video->reward_points }} PTS</td>
                                        <td class="py-3 border-bottom border-light fw-bold text-dark">{{ $video->remaining_budget }} / {{ $video->total_budget }}</td>
                                        <td class="py-3 border-bottom border-light">
                                            @if($video->status == 'active')
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">{{ __('messages.active') }}</span>
                                            @elseif($video->status == 'paused')
                                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-bold">{{ __('messages.pending') }}</span>
                                            @elseif($video->status == 'completed')
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-bold">{{ __('messages.completed') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 border-bottom border-light text-end">
                                            @if($video->status == 'active')
                                                <form action="{{ route('youtube.advertiser.pause', $video->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    <button class="btn btn-warning btn-sm rounded-3 shadow-sm text-white" title="Pause">
                                                        <i class="fa fa-pause"></i>
                                                    </button>
                                                </form>
                                            @elseif($video->status == 'paused')
                                                <form action="{{ route('youtube.advertiser.resume', $video->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm rounded-3 shadow-sm" title="Resume">
                                                        <i class="fa fa-play"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa-brands fa-youtube display-3 text-muted opacity-25 mb-3"></i>
                        <p class="fw-bold text-muted mb-0">{{ __('messages.yt_no_campaigns') }}</p>
                    </div>
                @endif
            </div>
            @if($videos->hasPages())
                <div class="card-footer bg-white p-4 border-top">
                    {{ $videos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
