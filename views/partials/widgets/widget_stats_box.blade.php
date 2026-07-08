@php
    try {
        $bannerViews = \Illuminate\Support\Facades\DB::table('state')->where('t_name', 'banner')->count();
    } catch (\Exception $e) {
        $bannerViews = 0;
    }

    try {
        $linkViews = \Illuminate\Support\Facades\DB::table('state')->where('t_name', 'link')->count();
    } catch (\Exception $e) {
        $linkViews = 0;
    }
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            <!-- Banner Ads -->
            <div class="p-3 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-primary small"><i class="fa-solid fa-rectangle-ad me-1"></i> {{ __('messages.bannads') }}</span>
                    <span class="badge bg-primary rounded-pill fw-bold">{{ \Illuminate\Support\Facades\DB::table('banner')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted smaller fw-bold">{{ __('messages.Views') }}</span>
                    <span class="text-dark fw-bold small">{{ $bannerViews }}</span>
                </div>
            </div>

            <!-- Link Ads -->
            <div class="p-3 rounded-4 bg-info bg-opacity-10 border border-info border-opacity-10">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-info small"><i class="fa-solid fa-link me-1"></i> {{ __('messages.linkads') }}</span>
                    <span class="badge bg-info rounded-pill fw-bold">{{ \Illuminate\Support\Facades\DB::table('link')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted smaller fw-bold">{{ __('messages.Views') }}</span>
                    <span class="text-dark fw-bold small">{{ $linkViews }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
