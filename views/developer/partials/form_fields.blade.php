@php
    $developerApp = (isset($app) && $app instanceof \App\Models\DeveloperApp) ? $app : null;
    $selectedScopes = old('requested_scopes', $developerApp ? ($developerApp->requested_scopes ?? []) : []);
    if (!is_array($selectedScopes)) {
        $selectedScopes = [];
    }

    $redirectUrisValue = old(
        'redirect_uris',
        $developerApp ? implode(', ', $developerApp->redirect_uris ?? []) : ''
    );
@endphp

<div class="mb-5 pb-4 border-bottom">
    <div class="mb-4">
        <p class="text-uppercase small fw-bold text-muted mb-1">{{ __('messages.configuration') }}</p>
        <h3 class="h5 fw-bold mb-0">{{ __('messages.app_specifications') }}</h3>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <label for="app_name" class="form-label small fw-bold">{{ __('messages.app_name') }} <span class="text-danger">*</span></label>
            <input
                id="app_name"
                type="text"
                name="name"
                class="form-control form-control-lg @error('name') is-invalid @enderror"
                value="{{ old('name', $developerApp->name ?? '') }}"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="app_domain" class="form-label small fw-bold">{{ __('messages.domain') }} <span class="text-danger">*</span></label>
            <input
                id="app_domain"
                type="url"
                name="domain"
                class="form-control form-control-lg @error('domain') is-invalid @enderror"
                value="{{ old('domain', $developerApp->domain ?? '') }}"
                placeholder="https://example.com"
                required
            >
            @error('domain')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="app_description" class="form-label small fw-bold">{{ __('messages.description') }} <span class="text-danger">*</span></label>
            <textarea
                id="app_description"
                name="description"
                rows="4"
                class="form-control @error('description') is-invalid @enderror"
                required
            >{{ old('description', $developerApp->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5 pb-4 border-bottom">
    <div class="mb-4">
        <p class="text-uppercase small fw-bold text-muted mb-1">{{ __('messages.information') }}</p>
        <h3 class="h5 fw-bold mb-2">{{ __('messages.redirect_uris') }}</h3>
        <p class="text-muted small mb-0">{{ __('messages.redirect_uris_help') }}</p>
    </div>

    <div>
        <label for="redirect_uris" class="form-label small fw-bold">{{ __('messages.redirect_uris') }} <span class="text-danger">*</span></label>
        <textarea
            id="redirect_uris"
            name="redirect_uris"
            rows="3"
            class="form-control @error('redirect_uris') is-invalid @enderror"
            placeholder="https://example.com/callback, https://example.com/oauth/return"
            required
        >{{ $redirectUrisValue }}</textarea>
        <div class="form-text mt-2"><i class="fa fa-info-circle me-1"></i>{{ __('messages.dev_https_hint') }}</div>
        @error('redirect_uris')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-bold text-muted mb-1">{{ __('messages.eligibility') }}</p>
            <h3 class="h5 fw-bold mb-0">{{ __('messages.requested_scopes') }}</h3>
        </div>
        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
            <i class="fa fa-shield-halved text-muted me-1"></i>
            {{ count($selectedScopes) }}
        </span>
    </div>

    <p class="text-muted small mb-4">{{ __('messages.dev_scopes_help') }}</p>

    @include('theme::developer.partials.scope_grid', [
        'scopes' => $scopes,
        'selectedScopes' => $selectedScopes,
        'scopeInputPrefix' => $scopeInputPrefix ?? 'developer_scope_form',
    ])
</div>
