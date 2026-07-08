@extends('theme::layouts.master')

@section('title', __('messages.notifications'))

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-bell fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.notifications') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.notification_center_subtitle') ?? 'All your account updates in one place.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-bullhorn fa-10x"></i>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4 text-center p-4 border border-light overflow-hidden transition-all hover-translate-y">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center border border-primary border-opacity-10 shadow-sm" style="width: 70px; height: 70px;">
                    <i class="fa fa-bell fs-3"></i>
                </div>
                <h2 class="fw-black mb-0 text-dark">{{ $unreadNotificationCount }}</h2>
                <p class="text-muted small fw-black text-uppercase letter-spacing-1 mb-4">{{ __('messages.unread_notifications') ?? 'Unread Notifications' }}</p>
                
                @if($unreadNotificationCount > 0)
                    <button type="button" class="btn btn-primary w-100 fw-black rounded-pill py-2 shadow-sm transition-all hover-translate-y" onclick="markAllNotificationsAsRead(this)">
                        <i class="fa fa-check-double me-2 smaller"></i> {{ __('messages.mark_all_read') }}
                    </button>
                @endif
            </div>
            <x-widget-column side="notification_sidebar" />
        </div>

        <!-- Feed -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.all_notifications') ?? 'All Notifications' }}</h5>
                    @if($unreadNotificationCount > 0)
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 smaller fw-black shadow-sm">{{ $unreadNotificationCount }} {{ __('messages.new') ?? 'New' }}</span>
                    @endif
                </div>
                <div class="card-body p-0 bg-light bg-opacity-25">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush" id="infinite-scroll-container">
                            @include('theme::notifications.partials.items', ['notifications' => $notifications])
                        </div>
                        @if($notifications->hasPages())
                            <div class="p-4 border-top bg-white d-flex justify-content-center">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    @else
                        <div class="p-5 text-center bg-light bg-opacity-50">
                            <div class="rounded-circle bg-white shadow-sm p-5 mb-4 d-inline-flex border border-light">
                                <i class="fa fa-bell-slash fa-4x text-muted opacity-10"></i>
                            </div>
                            <h4 class="fw-black text-dark mb-2">{{ __('messages.no_new_notifications') ?? 'No new notifications' }}</h4>
                            <p class="text-muted fs-6 mb-0 px-md-5">{{ __('messages.notification_empty_desc') ?? 'New activity will appear here as soon as it arrives.' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2) !important; }
</style>

@push('scripts')
<script>
    function markAllNotificationsAsRead(btn) {
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2 smaller"></i> Processing...';
        fetch('{{ route("notifications.mark_all_read") }}', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(response => {
            if (response.ok) window.location.reload();
            else {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }).catch(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
</script>
@endpush
@endsection
