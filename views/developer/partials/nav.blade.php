<div class="card border-0 shadow-sm rounded-4 dev-panel">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.dev_platform') }}</h6>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush rounded-bottom-4">
            <a href="{{ route('developer.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 {{ ($active ?? 'overview') === 'overview' ? 'bg-primary bg-opacity-10 text-primary fw-bold' : 'text-muted' }}">
                <span><i class="fa fa-compass me-2"></i>{{ __('messages.overview') }}</span>
                <i class="fa fa-chevron-right small"></i>
            </a>
            <a href="{{ route('developer.apps.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 {{ ($active ?? '') === 'apps' ? 'bg-primary bg-opacity-10 text-primary fw-bold' : 'text-muted' }}">
                <span><i class="fa fa-cubes me-2"></i>{{ __('messages.my_apps') }}</span>
                <i class="fa fa-chevron-right small"></i>
            </a>
            <a href="{{ route('developer.apps.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 {{ ($active ?? '') === 'create' ? 'bg-primary bg-opacity-10 text-primary fw-bold' : 'text-muted' }}">
                <span><i class="fa fa-plus-circle me-2"></i>{{ __('messages.create_app') }}</span>
                <i class="fa fa-chevron-right small"></i>
            </a>
            <a href="{{ route('developer.tokens.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 {{ ($active ?? '') === 'tokens' ? 'bg-primary bg-opacity-10 text-primary fw-bold' : 'text-muted' }}">
                <span><i class="fa fa-key me-2"></i>{{ __('messages.personal_tokens') ?? 'API Tokens (PAT)' }}</span>
                <i class="fa fa-chevron-right small"></i>
            </a>
            <a href="{{ route('developer.playground') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 {{ ($active ?? '') === 'playground' ? 'bg-primary bg-opacity-10 text-primary fw-bold' : 'text-muted' }}">
                <span><i class="fa fa-terminal me-2"></i>{{ __('messages.api_playground') ?? 'API Playground' }}</span>
                <i class="fa fa-chevron-right small"></i>
            </a>
            <a href="{{ route('developer.directory.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 {{ ($active ?? '') === 'directory' ? 'bg-primary bg-opacity-10 text-primary fw-bold' : 'text-muted' }}">
                <span><i class="fa fa-store me-2"></i>{{ __('messages.app_directory') ?? 'App Directory' }}</span>
                <i class="fa fa-chevron-right small"></i>
            </a>
            <a href="{{ route('developer.guides') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 {{ ($active ?? '') === 'guides' ? 'bg-primary bg-opacity-10 text-primary fw-bold' : 'text-muted' }}">
                <span><i class="fa fa-book me-2"></i>{{ __('messages.dev_guides') ?? 'Documentation' }}</span>
                <i class="fa fa-chevron-right small"></i>
            </a>
            <a href="{{ route('developer.openapi') }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 text-muted">
                <span><i class="fa fa-file-code me-2"></i>OpenAPI 3.1 JSON</span>
                <i class="fa fa-external-link-alt small" style="font-size: 11px;"></i>
            </a>
        </div>
    </div>
</div>
