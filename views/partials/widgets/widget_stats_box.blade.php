@php
    $config = [];
    if (!empty($widget?->o_valuer)) {
        if (is_array($widget->o_valuer)) {
            $config = $widget->o_valuer;
        } else {
            $decoded = json_decode((string) $widget->o_valuer, true);
            $config = is_array($decoded) ? $decoded : [];
        }
    }

    $showBanners = isset($config['show_banners']) ? (bool) $config['show_banners'] : true;
    $showLinks = isset($config['show_links']) ? (bool) $config['show_links'] : true;
    $showUsers = !empty($config['show_users']);
    $showTopics = !empty($config['show_topics']);
    $showOrders = !empty($config['show_orders']);

    try {
        $bannerViews = $showBanners ? \Illuminate\Support\Facades\DB::table('state')->where('t_name', 'banner')->count() : 0;
    } catch (\Exception $e) {
        $bannerViews = 0;
    }

    try {
        $linkViews = $showLinks ? \Illuminate\Support\Facades\DB::table('state')->where('t_name', 'link')->count() : 0;
    } catch (\Exception $e) {
        $linkViews = 0;
    }

    $bannersCount = $showBanners ? \Illuminate\Support\Facades\DB::table('banner')->count() : 0;
    $linksCount = $showLinks ? \Illuminate\Support\Facades\DB::table('link')->count() : 0;
    $usersCount = $showUsers ? \App\Models\User::count() : 0;
    $topicsCount = $showTopics ? \App\Models\ForumTopic::count() : 0;
    $ordersCount = $showOrders ? \App\Models\OrderRequest::count() : 0;
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @if($showBanners)
                <!-- Banner Ads -->
                <div class="p-3 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-primary small"><i class="fa-solid fa-rectangle-ad me-1"></i> {{ __('messages.bannads') }}</span>
                        <span class="badge bg-primary rounded-pill fw-bold">{{ $bannersCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted smaller fw-bold">{{ __('messages.Views') }}</span>
                        <span class="text-dark fw-bold small">{{ $bannerViews }}</span>
                    </div>
                </div>
            @endif

            @if($showLinks)
                <!-- Link Ads -->
                <div class="p-3 rounded-4 bg-info bg-opacity-10 border border-info border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-info small"><i class="fa-solid fa-link me-1"></i> {{ __('messages.linkads') }}</span>
                        <span class="badge bg-info rounded-pill fw-bold">{{ $linksCount }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted smaller fw-bold">{{ __('messages.Views') }}</span>
                        <span class="text-dark fw-bold small">{{ $linkViews }}</span>
                    </div>
                </div>
            @endif

            @if($showUsers)
                <!-- Members -->
                <div class="p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success small"><i class="fa-solid fa-users me-1"></i> {{ __('messages.members') }}</span>
                        <span class="badge bg-success rounded-pill fw-bold">{{ $usersCount }}</span>
                    </div>
                </div>
            @endif

            @if($showTopics)
                <!-- Forum Topics -->
                <div class="p-3 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-warning-emphasis small"><i class="fa-solid fa-comments me-1"></i> {{ __('messages.forum') }}</span>
                        <span class="badge bg-warning text-dark rounded-pill fw-bold">{{ $topicsCount }}</span>
                    </div>
                </div>
            @endif

            @if($showOrders)
                <!-- Service Orders -->
                <div class="p-3 rounded-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-secondary small"><i class="fa-solid fa-briefcase me-1"></i> {{ __('messages.order_requests') }}</span>
                        <span class="badge bg-secondary rounded-pill fw-bold">{{ $ordersCount }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
