@extends('theme::layouts.master')

@section('title', __('messages.create_app'))

@push('head')
    @include('theme::developer.partials.styles')
@endpush

@section('content')
<div class="section-banner">
    <div class="section-banner-icon" style="display: flex; align-items: center; justify-content: center;">
        <i class="fa fa-plus-circle" style="font-size: 26px; color: #fff;"></i>
    </div>
    <p class="section-banner-title">{{ __('messages.create_app') }}</p>
    <p class="section-banner-text">{{ __('messages.dev_create_help') }}</p>
</div>

<div class="row g-4">
    <div class="col-lg-3">
        <div class="d-flex flex-column gap-4">
            @include('theme::developer.partials.nav', ['active' => 'create'])
            @include('theme::developer.partials.platform_rules')
        </div>
    </div>

    <div class="col-lg-6">
        <div class="d-flex flex-column gap-4">
            @if(session('success'))
                <div class="alert alert-success rounded-4 shadow-sm mb-0" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-4 shadow-sm mb-0" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 mb-0">
                    <strong><i class="fa fa-exclamation-circle me-1"></i>{{ __('messages.save') }}</strong>
                    <ul class="mb-0 mt-2 small text-danger">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="dev-form-alert" class="alert alert-danger rounded-4 shadow-sm mb-0" style="display: none;"></div>

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.create_app') }}</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <form action="{{ route('developer.apps.store', [], false) }}" method="POST" class="dev-form-layout" id="dev-create-app-form">
                        @csrf

                        @include('theme::developer.partials.form_fields', [
                            'scopes' => $scopes,
                            'scopeInputPrefix' => 'developer_create_scope',
                        ])

                        <div class="dev-form-actions mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4" id="dev-submit-btn">{{ __('messages.save') }}</button>
                            <a href="{{ route('developer.apps.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4">{{ __('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="d-flex flex-column gap-4">
            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.information') }}</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="text-muted small mb-3">{{ __('messages.dev_create_help') }}</p>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-2 small">
                        <li class="d-flex gap-2 text-muted">
                            <i class="fa fa-check-circle text-primary mt-1"></i>
                            <span>{{ __('messages.dev_https_hint') }}</span>
                        </li>
                        <li class="d-flex gap-2 text-muted">
                            <i class="fa fa-check-circle text-primary mt-1"></i>
                            <span>{{ __('messages.dev_scopes_help') }}</span>
                        </li>
                        <li class="d-flex gap-2 text-muted">
                            <i class="fa fa-check-circle text-primary mt-1"></i>
                            <span>{{ __('messages.submit_for_review') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.dev_docs') }}</h6>
                </div>
                <div class="card-body p-4 pt-0">
                    <p class="text-muted small mb-0">{{ __('messages.dev_widgets_desc') }}</p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-user-shield text-muted me-1"></i>
                            OAuth 2.0
                        </span>
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-share-nodes text-muted me-1"></i>
                            Share API
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('dev-create-app-form');
    if (!form) return;

    const getCsrfToken = function () {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || form.querySelector('input[name="_token"]')?.value 
            || '';
    };

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const submitBtn = document.getElementById('dev-submit-btn');
        const originalText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __("messages.saving") ?? "Saving..." }}';
        }

        const alertContainer = document.getElementById('dev-form-alert');
        if (alertContainer) alertContainer.style.display = 'none';

        try {
            const csrfToken = getCsrfToken();
            const scopes = [];
            form.querySelectorAll('input[name="requested_scopes[]"]:checked').forEach(function (cb) {
                scopes.push(cb.value);
            });

            const payload = {
                _token: csrfToken,
                name: form.querySelector('input[name="name"]')?.value || '',
                domain: form.querySelector('input[name="domain"]')?.value || '',
                description: form.querySelector('textarea[name="description"]')?.value || '',
                redirect_uris: form.querySelector('textarea[name="redirect_uris"]')?.value || '',
                requested_scopes: scopes
            };

            const storeUrl = '{{ route("developer.apps.store") }}';

            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const rawText = await response.text();
            let data = {};
            try {
                data = JSON.parse(rawText);
            } catch (err) {
                data = {};
            }

            if (response.ok && data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
                return;
            }

            let errorMsg = '';
            if (data.message) {
                errorMsg = data.message;
            } else if (data.errors) {
                errorMsg = Object.values(data.errors).flat().join('<br>');
            } else if (response.status === 419) {
                errorMsg = 'Session/CSRF expired (HTTP 419). Please refresh the page and log in again.';
            } else {
                let specificError = '';
                try {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(rawText, 'text/html');
                    const title = doc.querySelector('.error-title')?.innerText || doc.querySelector('h1')?.innerText || '';
                    const message = doc.querySelector('.error-message')?.innerText || doc.querySelector('.error-card p')?.innerText || doc.querySelector('p')?.innerText || '';
                    specificError = (title + (message ? ' — ' + message : '')).trim();
                } catch (e) {}

                if (!specificError) {
                    specificError = rawText.replace(/<style[^>]*>[\s\S]*?<\/style>/gi, '')
                                           .replace(/<script[^>]*>[\s\S]*?<\/script>/gi, '')
                                           .replace(/<[^>]+>/g, ' ')
                                           .replace(/\s+/g, ' ')
                                           .trim()
                                           .substring(0, 300);
                }

                errorMsg = '[HTTP ' + response.status + ' ' + response.statusText + '] ' + (specificError || 'Permission Denied / WAF Block');
            }

            if (alertContainer) {
                alertContainer.innerHTML = '<strong>' + errorMsg + '</strong>';
                alertContainer.style.display = 'block';
                alertContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                alert(errorMsg);
            }
        } catch (err) {
            console.error('AJAX Submit Exception:', err);
            if (alertContainer) {
                alertContainer.innerHTML = '<strong>Network / Script Error: ' + err.message + '</strong>';
                alertContainer.style.display = 'block';
            } else {
                alert('Network Error: ' + err.message);
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
});
</script>
@endpush
