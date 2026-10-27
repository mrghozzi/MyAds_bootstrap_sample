<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('developer.apps.show', $app->id) }}" class="btn {{ ($activeTab ?? 'general') === 'general' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-2 fw-semibold btn-sm">
        <i class="fa fa-sliders me-1"></i>{{ __('messages.app_settings') }}
    </a>
    <a href="{{ route('developer.apps.webhooks.index', $app->id) }}" class="btn {{ ($activeTab ?? '') === 'webhooks' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-2 fw-semibold btn-sm">
        <i class="fa fa-bolt me-1"></i>{{ __('messages.webhooks') ?? 'Webhooks' }}
    </a>
    <a href="{{ route('developer.apps.analytics', $app->id) }}" class="btn {{ ($activeTab ?? '') === 'analytics' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-2 fw-semibold btn-sm">
        <i class="fa fa-chart-line me-1"></i>{{ __('messages.analytics') ?? 'Analytics' }}
    </a>
    <a href="{{ route('developer.apps.logs', $app->id) }}" class="btn {{ ($activeTab ?? '') === 'logs' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-2 fw-semibold btn-sm">
        <i class="fa fa-stream me-1"></i>{{ __('messages.request_logs') ?? 'Live Logs' }}
    </a>
</div>
