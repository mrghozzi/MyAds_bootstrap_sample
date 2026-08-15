@extends('theme::layouts.master')

@section('title', __('messages.dev_platform'))

@push('head')
    @include('theme::developer.partials.styles')
@endpush

@section('content')
@php
    $developerApps = collect($apps ?? []);
    $activeAppsCount = $developerApps->where('status', 'active')->count();
    $pendingAppsCount = $developerApps->where('status', 'pending_review')->count();
    $scopeCount = count($scopes ?? []);
@endphp

<div class="bg-primary bg-gradient rounded-4 text-white p-5 mb-4 text-center shadow-sm">
    <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle mb-3" style="width: 64px; height: 64px;">
        <i class="fa fa-code-branch fs-3"></i>
    </div>
    <h1 class="h3 fw-bold mb-2">{{ __('messages.dev_platform') }}</h1>
    <p class="mb-0 opacity-75">{{ __('messages.dev_platform_desc', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>
</div>

<div class="row g-4">
    <!-- Left Sidebar -->
    <div class="col-lg-3">
        <div class="d-flex flex-column gap-4">
            @include('theme::developer.partials.nav', ['active' => 'overview'])

            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ auth()->check() && $eligible ? __('messages.applications') : __('messages.platform_info') }}</h6>
                </div>
                <div class="card-body p-4">
                    @if(auth()->check() && $eligible)
                        <div class="dev-stat-grid dev-stat-grid--compact">
                            <div class="dev-stat-card p-3 mb-2">
                                <span>{{ __('messages.total_apps') }}</span>
                                <strong>{{ $developerApps->count() }}</strong>
                            </div>
                            <div class="dev-stat-card p-3 mb-2">
                                <span>{{ __('messages.active_apps') }}</span>
                                <strong>{{ $activeAppsCount }}</strong>
                            </div>
                            <div class="dev-stat-card p-3">
                                <span>{{ __('messages.pending_review') }}</span>
                                <strong>{{ $pendingAppsCount }}</strong>
                            </div>
                        </div>
                    @else
                        <p class="dev-card-copy mb-3">{{ __('messages.v1_api_desc') }}</p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill py-2 px-3">
                                <i class="fa fa-plug me-1"></i> {{ __('messages.v1_api') }}
                            </span>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill py-2 px-3">
                                <i class="fa fa-shield-halved me-1"></i> {{ __('messages.oauth_secured') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            @include('theme::developer.partials.platform_rules')
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-6">
        @if(session('error'))
            <div class="alert alert-danger rounded-4 shadow-sm mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="d-flex flex-column gap-4">
            <!-- Hero Header Card -->
            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-1">{{ __('messages.dev_platform') }}</p>
                            <h2 class="h4 fw-bold mb-2">{{ __('messages.dev_docs') }}</h2>
                            <p class="text-muted small mb-0">{{ __('messages.dev_features_subtitle') }}</p>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            @auth
                                @if($eligible)
                                    <a href="{{ $developerApps->isEmpty() ? route('developer.apps.create') : route('developer.apps.index') }}" class="btn btn-primary rounded-pill fw-bold px-4">
                                        <i class="fa {{ $developerApps->isEmpty() ? 'fa-plus' : 'fa-cubes' }} me-1"></i>
                                        {{ $developerApps->isEmpty() ? __('messages.create_app') : __('messages.manage_apps') }}
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill fw-bold px-4">{{ __('messages.login') }}</a>
                            @endauth
                            <a href="{{ route('developer.guides') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4">
                                <i class="fa fa-book-open me-1"></i>
                                {{ __('messages.dev_explore_guides') }}
                            </a>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-user-shield text-primary me-1"></i> OAuth 2.0 Flow
                        </span>
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-bolt text-warning me-1"></i> REST API v1 (20+ Endpoints)
                        </span>
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-layer-group text-info me-1"></i> 3 Ready Widgets
                        </span>
                        <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                            <i class="fa fa-tachometer-alt text-success me-1"></i> 30 req/min Limit
                        </span>
                    </div>
                </div>
            </div>

            <!-- 4 Pillar Features Grid -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-light">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 p-3 mb-3" style="width: 46px; height: 46px;">
                            <i class="fa fa-user-shield fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-2">OAuth 2.0 Authorization</h5>
                        <p class="text-muted small mb-3">{{ __('messages.dev_oauth_desc', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>
                        <div class="mt-auto">
                            <span class="badge bg-white text-primary border rounded-pill py-1 px-2 small">
                                <i class="fa fa-key me-1"></i> Auth Code Flow
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-light">
                        <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-3 p-3 mb-3" style="width: 46px; height: 46px;">
                            <i class="fa fa-cubes-stacked fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Developer API v1</h5>
                        <p class="text-muted small mb-3">{{ __('messages.dev_api_endpoints_desc') }}</p>
                        <div class="mt-auto">
                            <span class="badge bg-white text-info border rounded-pill py-1 px-2 small">
                                <i class="fa fa-network-wired me-1"></i> 20+ Endpoints
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-light">
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-3 p-3 mb-3" style="width: 46px; height: 46px;">
                            <i class="fa fa-wand-magic-sparkles fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('messages.dev_widgets_integration') }}</h5>
                        <p class="text-muted small mb-3">{{ __('messages.dev_widgets_integration_desc') }}</p>
                        <div class="mt-auto">
                            <span class="badge bg-white text-warning border rounded-pill py-1 px-2 small">
                                <i class="fa fa-code me-1"></i> Profile / Follow / Feed
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-light">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-3 p-3 mb-3" style="width: 46px; height: 46px;">
                            <i class="fa fa-share-nodes fs-5"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('messages.dev_share_api_title') }}</h5>
                        <p class="text-muted small mb-3">{{ __('messages.dev_share_api_desc') }}</p>
                        <div class="mt-auto">
                            <span class="badge bg-white text-success border rounded-pill py-1 px-2 small">
                                <i class="fa fa-globe me-1"></i> GET /share
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quickstart Interactive Code Sandbox -->
            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-0">Quickstart Example</p>
                            <h4 class="h5 fw-bold mb-0">Token Exchange &amp; Fetch Profile</h4>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2">API v1 Ready</span>
                    </div>

                    <div class="dev-lang-tabs">
                        <div class="dev-lang-tab is-active" data-lang="php">PHP (cURL)</div>
                        <div class="dev-lang-tab" data-lang="node">Node.js (Axios)</div>
                        <div class="dev-lang-tab" data-lang="python">Python (Requests)</div>
                        <div class="dev-lang-tab" data-lang="curl">cURL CLI</div>
                    </div>

                    <div class="dev-code-block dev-code-container">
                        <div class="js-lang-content" data-lang="php">
                            <div class="dev-code-toolbar">
                                <span>PHP (cURL)</span>
                                <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="bs-home-code-php">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
<pre><code id="bs-home-code-php">&lt;?php
// 1. Exchange authorization code for token
$ch = curl_init('{{ url('/oauth/token') }}');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'grant_type'    => 'authorization_code',
    'client_id'     => 'YOUR_CLIENT_ID',
    'client_secret' => 'YOUR_CLIENT_SECRET',
    'redirect_uri'  => 'https://yourapp.com/callback',
    'code'          => $_GET['code']
]);
$res = json_decode(curl_exec($ch), true);
$token = $res['access_token'];

// 2. Fetch authenticated member profile via Developer API v1
$ch = curl_init('{{ url('/api/developer/v1/me/profile') }}');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
$profile = json_decode(curl_exec($ch), true);
print_r($profile['data']);
?&gt;</code></pre>
                        </div>

                        <div class="js-lang-content d-none" data-lang="node">
                            <div class="dev-code-toolbar">
                                <span>Node.js (Axios)</span>
                                <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="bs-home-code-node">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
<pre><code id="bs-home-code-node">const axios = require('axios');

async function getProfile(code) {
    // 1. Exchange code for access token
    const tokenRes = await axios.post('{{ url('/oauth/token') }}', {
        grant_type: 'authorization_code',
        client_id: 'YOUR_CLIENT_ID',
        client_secret: 'YOUR_CLIENT_SECRET',
        redirect_uri: 'https://yourapp.com/callback',
        code: code
    });

    const accessToken = tokenRes.data.access_token;

    // 2. Access Developer API v1 endpoint
    const profileRes = await axios.get('{{ url('/api/developer/v1/me/profile') }}', {
        headers: {
            'Authorization': `Bearer ${accessToken}`,
            'Accept': 'application/json'
        }
    });

    return profileRes.data.data;
}</code></pre>
                        </div>

                        <div class="js-lang-content d-none" data-lang="python">
                            <div class="dev-code-toolbar">
                                <span>Python (Requests)</span>
                                <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="bs-home-code-python">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
<pre><code id="bs-home-code-python">import requests

# 1. Exchange authorization code for token
token_res = requests.post('{{ url('/oauth/token') }}', data={
    'grant_type': 'authorization_code',
    'client_id': 'YOUR_CLIENT_ID',
    'client_secret': 'YOUR_CLIENT_SECRET',
    'redirect_uri': 'https://yourapp.com/callback',
    'code': auth_code
})
access_token = token_res.json().get('access_token')

# 2. Fetch profile from Developer API v1
profile_res = requests.get('{{ url('/api/developer/v1/me/profile') }}', headers={
    'Authorization': f'Bearer {access_token}',
    'Accept': 'application/json'
})
print(profile_res.json().get('data'))</code></pre>
                        </div>

                        <div class="js-lang-content d-none" data-lang="curl">
                            <div class="dev-code-toolbar">
                                <span>cURL</span>
                                <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="bs-home-code-curl">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
<pre><code id="bs-home-code-curl"># 1. Exchange code for access token
curl -X POST {{ url('/oauth/token') }} \
     -d "grant_type=authorization_code" \
     -d "client_id=YOUR_CLIENT_ID" \
     -d "client_secret=YOUR_CLIENT_SECRET" \
     -d "code=AUTHORIZATION_CODE" \
     -d "redirect_uri=https://yourapp.com/callback"

# 2. Call Developer API v1 with Bearer token
curl -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
     -H "Accept: application/json" \
     {{ url('/api/developer/v1/me/profile') }}</code></pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scopes Preview Box -->
            <div class="card border-0 shadow-sm rounded-4 dev-panel">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="text-uppercase small fw-bold text-muted mb-0">{{ __('messages.dev_scopes_catalog') }}</p>
                            <h4 class="h5 fw-bold mb-0">{{ __('messages.dev_scopes_catalog') }} ({{ $scopeCount }} Scopes)</h4>
                            <p class="text-muted small mb-0 mt-1">{{ __('messages.dev_scopes_catalog_desc') }}</p>
                        </div>
                        <a href="{{ route('developer.guides') }}#scopes-catalog" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                            {{ __('messages.dev_view_catalog') }}
                        </a>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @foreach($categories ?? [] as $catKey => $catData)
                            <span class="badge bg-light text-dark border rounded-pill py-2 px-3">
                                <i class="fa {{ $catData['icon'] ?? 'fa-tag' }} text-primary me-1"></i>
                                {{ __($catData['title']) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-3">
        @include('theme::developer.partials.account_state', ['apps' => $developerApps])
    </div>
</div>
@endsection

@push('scripts')
    @include('theme::developer.partials.scripts')
    <script>
        document.querySelectorAll('.dev-lang-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                const lang = tab.getAttribute('data-lang');
                
                document.querySelectorAll('.dev-lang-tab').forEach(t => t.classList.remove('is-active'));
                tab.classList.add('is-active');
                
                document.querySelectorAll('.js-lang-content').forEach(content => {
                    if (content.getAttribute('data-lang') === lang) {
                        content.classList.remove('d-none');
                    } else {
                        content.classList.add('d-none');
                    }
                });
            });
        });
    </script>
@endpush
