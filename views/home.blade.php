@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Dashboard Header -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-8">
            <h1 class="h3 fw-bold mb-1">{{ __('messages.board') }}</h1>
            <p class="text-muted mb-0">{{ __('messages.welcome_back') }}, <span class="fw-bold text-primary">{{ $user->username }}</span></p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="bg-white p-2 rounded-pill shadow-sm d-inline-flex align-items-center px-4 border">
                <i class="fa fa-coins text-warning me-2 fs-4"></i>
                <span class="fw-bold fs-5">{{ $user->pts }}</span>
                <small class="text-muted ms-2 fw-bold text-uppercase">PTS</small>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('errMSG'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i> {{ session('errMSG') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('MSG'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('MSG') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="row g-3 mb-5">
        <!-- Banner Ads -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                            <i class="fa fa-image text-primary fs-4"></i>
                        </div>
                        <span class="badge bg-primary rounded-pill px-3">{{ $user->nvu }}</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $bannerStats['vu'] }}</h3>
                    <p class="text-muted small fw-bold text-uppercase mb-0">{{ __('messages.bannads') }} {{ __('messages.Views') }}</p>
                </div>
            </div>
        </div>
        <!-- Text Ads -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 border-start border-4 border-info">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-info bg-opacity-10 p-3 rounded-4">
                            <i class="fa fa-align-left text-info fs-4"></i>
                        </div>
                        <span class="badge bg-info rounded-pill px-3">{{ $user->nlink }}</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $linkStats['clik'] }}</h3>
                    <p class="text-muted small fw-bold text-uppercase mb-0">{{ __('messages.textads') }} {{ __('messages.Click') }}</p>
                </div>
            </div>
        </div>
        <!-- Visits -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-success bg-opacity-10 p-3 rounded-4">
                            <i class="fa fa-mouse-pointer text-success fs-4"></i>
                        </div>
                        <span class="badge bg-success rounded-pill px-3">{{ $user->vu }}</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $visitStats['vu'] }}</h3>
                    <p class="text-muted small fw-bold text-uppercase mb-0">{{ __('messages.visits') }} {{ __('messages.received') }}</p>
                </div>
            </div>
        </div>
        <!-- Smart Ads -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-dark text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary p-3 rounded-4">
                            <i class="fa fa-bolt text-white fs-4"></i>
                        </div>
                        <span class="badge bg-primary rounded-pill px-3">PRO</span>
                    </div>
                    <h3 class="fw-bold mb-1">{{ $smartAdStats['impressions'] }}</h3>
                    <p class="text-white-50 small fw-bold text-uppercase mb-0">{{ __('messages.smart_ads') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Tools & Detailed Sections -->
    <div class="row g-4">
        <!-- Left: Ad Management -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fa fa-bullhorn text-primary me-2"></i> {{ __('messages.manage_advertising') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0 pb-4 mb-4 border-bottom">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-1">{{ __('messages.bannads') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('messages.banner_ads_desc') ?? 'Manage your graphical banner advertisements and track performance.' }}</p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <div class="btn-group">
                                        <a href="{{ url('/b_list') }}" class="btn btn-sm btn-primary rounded-pill px-3">{{ __('messages.list') }}</a>
                                        <a href="{{ url('/b_code') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 ms-2"><i class="fa fa-code"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 pb-4 mb-4 border-bottom">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-1">{{ __('messages.textads') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('messages.text_ads_desc') ?? 'Manage your text-based link advertisements and boost CTR.' }}</p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <div class="btn-group">
                                        <a href="{{ url('/l_list') }}" class="btn btn-sm btn-info text-white rounded-pill px-3">{{ __('messages.list') }}</a>
                                        <a href="{{ url('/l_code') }}" class="btn btn-sm btn-outline-info rounded-pill px-3 ms-2"><i class="fa fa-code"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-1 text-primary">{{ __('messages.smart_ads') }}</h6>
                                    <p class="text-muted small mb-0">{{ __('messages.smart_ads_pitch') ?? 'Contextual native advertising for higher quality traffic.' }}</p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <div class="btn-group">
                                        <a href="{{ route('ads.smart.index') }}" class="btn btn-sm btn-dark rounded-pill px-3">{{ __('messages.manage') }}</a>
                                        <a href="{{ route('ads.smart.create') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 ms-2"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visits Exchange -->
            <div class="card border-0 shadow-sm rounded-4 bg-primary bg-gradient text-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-2">{{ __('messages.exvisit') }}</h4>
                            <p class="text-white-50 mb-4">{{ __('messages.visit_exchange_pitch') ?? 'Earn points by visiting other websites or drive traffic to your own listings.' }}</p>
                            <div class="d-flex gap-2">
                                <button class="btn bg-white rounded-pill fw-bold px-4 shadow-sm" onclick="window.open('{{ url('/visits?id=' . $user->id) }}');"><i class="fa fa-play me-2 text-primary"></i> {{ __('messages.start_surfing') }}</button>
                                <a href="{{ url('/v_list') }}" class="btn btn-outline-light rounded-pill px-4">{{ __('messages.my_sites') }}</a>
                            </div>
                        </div>
                        <div class="col-md-4 d-none d-md-block text-center">
                            <i class="fa fa-globe-americas fa-5x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- YouTube Views Exchange -->
            <div class="card border-0 shadow-sm rounded-4 bg-danger bg-gradient text-white mt-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-2">{{ __('messages.yt_exchange_title') ?? 'YouTube Views Exchange' }}</h4>
                            <p class="text-white-50 mb-4">{{ __('messages.yt_exchange_desc') ?? 'Watch YouTube videos to earn points, or promote your own videos.' }}</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('youtube.exchange.index') }}" class="btn bg-white text-danger rounded-pill fw-bold px-4 shadow-sm"><i class="fa-brands fa-youtube me-2"></i> {{ __('messages.watch_videos') ?? 'Watch Videos' }}</a>
                                <a href="{{ route('youtube.advertiser.index') }}" class="btn btn-outline-light rounded-pill px-4">{{ __('messages.yt_campaigns') }}</a>
                            </div>
                        </div>
                        <div class="col-md-4 d-none d-md-block text-center">
                            <i class="fa-brands fa-youtube fa-5x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Ads -->
            <div class="card border-0 shadow-sm rounded-4 text-white mt-4" style="background: linear-gradient(135deg, rgba(30,27,75,0.98) 0%, rgba(79,70,229,0.96) 52%, rgba(139,92,246,0.92) 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-2">{{ __('messages.custom_ads') }}</h4>
                            <p class="text-white-50 mb-4">{{ __('messages.custom_ads_desc') }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ url('/ads/custom') }}" class="btn bg-white text-primary rounded-pill fw-bold px-4 shadow-sm">{{ __('messages.custom_ads_dashboard') }}</a>
                                <a href="{{ url('/ads/custom/placements/create') }}" class="btn btn-outline-light rounded-pill px-4">{{ __('messages.custom_ads_create') }}</a>
                                <a href="{{ url('/ads/custom/marketplace') }}" class="btn btn-outline-light border-0 opacity-75 rounded-pill px-4"><i class="fa-solid fa-shop"></i> {{ __('messages.custom_ads_marketplace') }}</a>
                            </div>
                        </div>
                        <div class="col-md-4 d-none d-md-block text-center">
                            <i class="fa-solid fa-bullseye fa-5x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Checker -->
            <div class="card border-0 shadow-sm rounded-4 text-white mt-4 bg-gradient" style="background: linear-gradient(135deg, rgba(6,78,59,0.98) 0%, rgba(5,150,105,0.96) 52%, rgba(16,185,129,0.92) 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <span class="badge bg-white bg-opacity-25 text-white mb-2 text-uppercase fw-bold"><i class="fa-solid fa-chart-line me-1"></i> {{ __('messages.seo_checker') }}</span>
                            <h4 class="fw-bold mb-2">{{ __('messages.seo_checker') }}</h4>
                            <p class="text-white-50 small mb-0">{{ __('messages.seo_checker_desc') }}</p>
                        </div>
                        <div class="col-md-5 mt-3 mt-md-0">
                            <form action="{{ route('seo_checker.analyze') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="url" name="url" placeholder="https://example.com" required class="form-control rounded-pill border-0 bg-white shadow-sm px-3" style="font-size: 0.9rem;">
                                <button type="submit" class="btn btn-light rounded-pill fw-bold text-success shadow-sm px-3 flex-shrink-0">
                                    {{ __('messages.seo_analyze_now') ?? 'Analyze' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Points Operations (Transfer, Vouchers) -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="fa fa-coins text-warning me-2"></i> {{ __('messages.points_services') ?? 'Points Services' }}</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" id="ptsToolsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold" id="transfer-tab-btn" data-bs-toggle="tab" data-bs-target="#transfer-tab-pane" type="button" role="tab">{{ __('messages.transfer_pts') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="generate-tab-btn" data-bs-toggle="tab" data-bs-target="#generate-tab-pane" type="button" role="tab">{{ __('messages.generate_voucher') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="claim-tab-btn" data-bs-toggle="tab" data-bs-target="#claim-tab-pane" type="button" role="tab">{{ __('messages.claim_voucher') }}</button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="ptsToolsTabsContent">
                        <!-- Transfer Tab -->
                        <div class="tab-pane fade show active" id="transfer-tab-pane" role="tabpanel">
                            <form action="{{ route('dashboard.pts.transfer') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="transfer_username" class="form-label fw-bold small text-muted">{{ __('messages.username') }}</label>
                                        <input type="text" id="transfer_username" name="username" class="form-control bg-light border-0 rounded-3" required placeholder="username">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="transfer_amount" class="form-label fw-bold small text-muted">{{ __('messages.Points') }}</label>
                                        <div class="input-group">
                                            <input type="number" id="transfer_amount" name="amount" min="1" step="0.01" class="form-control bg-light border-0 rounded-start-3" required placeholder="0.00">
                                            <span class="input-group-text bg-light border-0 rounded-end-3 fw-bold">PTS</span>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4 py-2 shadow-sm">
                                            <i class="fa fa-paper-plane me-2"></i> {{ __('messages.send') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Generate Voucher Tab -->
                        <div class="tab-pane fade" id="generate-tab-pane" role="tabpanel">
                            <form action="{{ route('dashboard.pts.voucher.generate') }}" method="POST">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label for="voucher_amount" class="form-label fw-bold small text-muted">{{ __('messages.Points') }}</label>
                                        <div class="input-group">
                                            <input type="number" id="voucher_amount" name="amount" min="1" step="0.01" class="form-control bg-light border-0 rounded-start-3" required placeholder="0.00">
                                            <span class="input-group-text bg-light border-0 rounded-end-3 fw-bold">PTS</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-secondary w-100 rounded-pill fw-bold py-2 shadow-sm">
                                            <i class="fa fa-magic me-2"></i> {{ __('messages.generate') }}
                                        </button>
                                    </div>
                                </div>
                            </form>

                            @if(isset($vouchers) && $vouchers->count() > 0)
                                <div class="table-responsive mt-4 rounded-3 border">
                                    <table class="table table-hover align-middle mb-0 text-center">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="small fw-bold text-muted">{{ __('messages.code') }}</th>
                                                <th class="small fw-bold text-muted">{{ __('messages.amount') }}</th>
                                                <th class="small fw-bold text-muted">{{ __('messages.status') }}</th>
                                                <th class="small fw-bold text-muted">{{ __('messages.date') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($vouchers as $voucher)
                                                <tr>
                                                    <td><code class="bg-light px-2 py-1 rounded text-primary">{{ $voucher->code }}</code></td>
                                                    <td class="fw-bold">{{ number_format($voucher->amount, 2) }} PTS</td>
                                                    <td>
                                                        @if($voucher->is_used)
                                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3">{{ __('messages.used') }} <small>({{ $voucher->claimer->username ?? '?' }})</small></span>
                                                        @else
                                                            <span class="badge bg-success-subtle text-success rounded-pill px-3">{{ __('messages.unused') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="small text-muted">{{ $voucher->created_at->format('Y-m-d') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Claim Voucher Tab -->
                        <div class="tab-pane fade" id="claim-tab-pane" role="tabpanel">
                            <form action="{{ route('dashboard.pts.voucher.claim') }}" method="POST">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-8">
                                        <label for="claim_code" class="form-label fw-bold small text-muted">{{ __('messages.code') }}</label>
                                        <input type="text" id="claim_code" name="code" class="form-control bg-light border-0 rounded-3" required placeholder="XXXX-XXXX-XXXX">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow-sm">
                                            <i class="fa fa-gift me-2"></i> {{ __('messages.claim') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Points & Conversion -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom fw-bold text-muted small text-uppercase"><i class="fa fa-exchange-alt me-2 text-warning"></i> {{ __('messages.point_conversion') }}</div>
                <div class="card-body p-4">
                    <form action="{{ url('/home') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">{{ __('messages.points_to_convert') }}</label>
                            <div class="input-group">
                                <input type="number" class="form-control bg-light border-0" name="pts" placeholder="0" min="1" max="{{ $user->pts }}">
                                <span class="input-group-text bg-light border-0 fw-bold">PTS</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">{{ __('messages.convert_to') }}</label>
                            <select class="form-select bg-light border-0" name="to">
                                <option value="link">{{ __('messages.text_ad_credits') }}</option>
                                <option value="banners">{{ __('messages.banner_ad_credits') }}</option>
                                <option value="exchv">{{ __('messages.visit_credits') }}</option>
                                <option value="smartads">{{ __('messages.smart_ad_credits') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold py-2 shadow-sm" name="bt_pts" value="bt_pts">
                            {{ __('messages.Conversion') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Referral Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fa fa-users me-2 text-primary"></i> {{ __('messages.referral_program') }}</h6>
                    <p class="small text-muted mb-4">{{ __('messages.referral_pitch') ?? 'Invite your friends and earn points for every signup and activity.' }}</p>
                    <div class="d-grid gap-2">
                        <a href="{{ url('/referral') }}" class="btn btn-outline-dark btn-sm rounded-pill fw-bold">{{ __('messages.referral_list') }}</a>
                        <a href="{{ url('/r_code') }}" class="btn btn-dark btn-sm rounded-pill fw-bold shadow-sm">{{ __('messages.get_referral_link') }}</a>
                    </div>
                </div>
            </div>

            <x-widget-column side="portal_right" />
        </div>
    </div>
</div>

<style>
.bg-white { background-color: #fff !important; }
.hover-primary:hover { color: var(--bs-primary) !important; }
.bg-gradient-primary { background: linear-gradient(45deg, var(--bs-primary), #615dfa); }
</style>
@endsection
