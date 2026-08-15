@extends('theme::layouts.master')

@section('title', __('messages.dev_guides'))

@push('head')
    @include('theme::developer.partials.styles')
    <style>
        .dev-guide-header {
            padding: 40px;
            border-radius: 24px;
            background: var(--dev-surface-accent);
            border: 1px solid var(--dev-border);
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }
        .dev-guide-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--dev-accent) 0%, transparent 70%);
            opacity: 0.1;
            filter: blur(40px);
        }
        .dev-toc-card {
            position: sticky;
            top: 20px;
        }
        .dev-toc-link {
            display: block;
            padding: 10px 16px;
            color: var(--dev-text);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.86rem;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .dev-toc-link:hover {
            background: rgba(97, 93, 250, 0.06);
            color: var(--dev-accent);
            padding-inline-start: 20px;
            text-decoration: none;
        }
        .dev-toc-link.is-active {
            background: var(--dev-surface-accent);
            color: var(--dev-accent);
        }
        .dev-step-badge {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--dev-accent);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9rem;
            margin-bottom: 16px;
        }
        .dev-lang-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: -1px;
            position: relative;
            z-index: 2;
            overflow-x: auto;
            padding-bottom: 4px;
        }
        .dev-lang-tab {
            padding: 10px 18px;
            background: var(--dev-surface-soft);
            border: 1px solid var(--dev-border);
            border-bottom: 0;
            border-radius: 14px 14px 0 0;
            color: var(--dev-muted);
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .dev-lang-tab:hover {
            color: var(--dev-title);
            background: var(--dev-surface);
        }
        .dev-lang-tab.is-active {
            background: var(--dev-code-bg);
            border-color: var(--dev-code-border);
            color: #fff;
        }
        .dev-code-container {
            border-radius: 0 18px 18px 18px;
            margin-top: 0;
        }
    </style>
@endpush

@section('content')
<div class="dev-guide-header">
    <p class="dev-kicker">{{ __('messages.dev_integration_guide') }}</p>
    <h1 class="dev-title" style="font-size: 2.1rem; margin-bottom: 12px;">{{ __('messages.dev_guides') }}</h1>
    <p class="dev-summary-copy" style="max-width: 780px;">{{ __('messages.dev_guides_intro', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>
</div>

<div class="row g-4">
    <!-- Left Sticky Sidebar -->
    <div class="col-lg-3">
        <div class="d-flex flex-column gap-4">
            @include('theme::developer.partials.nav', ['active' => 'guides'])

            <div class="card border-0 shadow-sm rounded-4 dev-toc-card d-none d-lg-block">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.information') }}</h6>
                </div>
                <div class="card-body p-2">
                    <nav class="dev-toc">
                        <a href="#step-1" class="dev-toc-link">1. {{ __('messages.dev_step_1_title') }}</a>
                        <a href="#step-2" class="dev-toc-link">2. {{ __('messages.dev_step_2_title') }}</a>
                        <a href="#code-samples" class="dev-toc-link">3. {{ __('messages.dev_code_examples') }}</a>
                        <a href="#api-endpoints" class="dev-toc-link">4. {{ __('messages.dev_api_endpoints') }}</a>
                        <a href="#scopes-catalog" class="dev-toc-link">5. {{ __('messages.dev_scopes_catalog') }}</a>
                        <a href="#embed-widgets" class="dev-toc-link">6. {{ __('messages.dev_widgets_integration') }}</a>
                        <a href="#share-api" class="dev-toc-link">7. {{ __('messages.dev_share_api_title') }}</a>
                        <a href="#rate-limits" class="dev-toc-link">8. {{ __('messages.dev_rate_limits_title') }}</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Guide Content -->
    <div class="col-lg-9">
        <div class="d-flex flex-column gap-4">
            <!-- Step 1: App Registration & Credentials -->
            <section id="step-1" class="card border-0 shadow-sm rounded-4 dev-panel dev-guide-section">
                <div class="card-body p-4">
                    <span class="dev-step-badge">1</span>
                    <h2 class="h4 fw-bold mb-3">{{ __('messages.dev_step_1_title') }}</h2>
                    <p class="text-muted small">{{ __('messages.dev_step_1_desc') }}</p>
                    
                    <div class="alert alert-info border-0 bg-primary bg-opacity-10 text-primary rounded-4 my-4">
                        <p class="mb-0"><strong><i class="fa fa-info-circle me-1"></i>{{ __('messages.info') }}:</strong> {{ __('messages.dev_create_help') }}</p>
                    </div>

                    <div class="dev-rule-list">
                        <div class="dev-rule-item">
                            <strong>{{ __('messages.client_id') }}</strong>
                            <span class="dev-help-text">A unique 32-character hexadecimal identifier generated for your app upon creation.</span>
                        </div>
                        <div class="dev-rule-item">
                            <strong>Client Secret (Secret Key)</strong>
                            <span class="dev-help-text">{{ __('messages.dev_credentials_help') }}</span>
                        </div>
                        <div class="dev-rule-item">
                            <strong>Redirect URIs</strong>
                            <span class="dev-help-text">{{ __('messages.dev_https_hint') }} Comma-separated list of authorized callback URLs where the authorization code will be sent.</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Step 2: OAuth 2.0 Authorization Code Flow -->
            <section id="step-2" class="card border-0 shadow-sm rounded-4 dev-panel dev-guide-section">
                <div class="card-body p-4">
                    <span class="dev-step-badge">2</span>
                    <h2 class="h4 fw-bold mb-3">{{ __('messages.dev_step_2_title') }}</h2>
                    <p class="text-muted small">{{ __('messages.dev_step_2_desc') }}</p>

                    <!-- Step 2.1: Request Authorization -->
                    <div class="mt-4">
                        <h3 class="h6 fw-bold mb-2">{{ __('messages.dev_step_auth_code') }}</h3>
                        <p class="text-muted small">
                            Redirect the user to the authorization endpoint. The user will be prompted to grant the requested permissions.
                        </p>
                        
                        <div class="dev-code-block">
                            <div class="dev-code-toolbar">
                                <span>GET /oauth/authorize</span>
                                <button type="button" class="dev-copy-btn js-dev-copy" data-copy="{{ url('/oauth/authorize') }}?client_id=YOUR_CLIENT_ID&redirect_uri=https://yourapp.com/callback&response_type=code&scope=user.identity.read%20user.profile.read&state=RANDOM_STRING">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
                            <pre><code>GET {{ url('/oauth/authorize') }}?
    client_id=YOUR_CLIENT_ID&
    redirect_uri=https://yourapp.com/callback&
    response_type=code&
    scope=user.identity.read%20user.profile.read&
    state=RANDOM_CSRF_STATE</code></pre>
                        </div>
                    </div>

                    <!-- Step 2.2: Token Exchange -->
                    <div class="mt-4">
                        <h3 class="h6 fw-bold mb-2">{{ __('messages.dev_step_token_exchange') }}</h3>
                        <p class="text-muted small">
                            Once authorized, the user is redirected back to your <code>redirect_uri</code> with a <code>code</code> query parameter. Exchange this code via a secure server-to-server POST request:
                        </p>

                        <div class="dev-code-block">
                            <div class="dev-code-toolbar">
                                <span>POST /oauth/token</span>
                                <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="bs-guide-token-req">
                                    <i class="fa fa-copy"></i>
                                </button>
                            </div>
                            <pre><code id="bs-guide-token-req">POST {{ url('/oauth/token') }}
Content-Type: application/json

{
    "grant_type": "authorization_code",
    "client_id": "YOUR_CLIENT_ID",
    "client_secret": "YOUR_CLIENT_SECRET",
    "redirect_uri": "https://yourapp.com/callback",
    "code": "AUTHORIZATION_CODE"
}</code></pre>
                        </div>

                        <div class="dev-code-block mt-3">
                            <div class="dev-code-toolbar">
                                <span>JSON Response (HTTP 200)</span>
                            </div>
                            <pre><code>{
    "access_token": "def50200a87...",
    "refresh_token": "def50200b92...",
    "expires_in": 3600,
    "token_type": "Bearer"
}</code></pre>
                        </div>
                    </div>

                    <!-- Step 2.3: Access Protected APIs -->
                    <div class="mt-4">
                        <h3 class="h6 fw-bold mb-2">{{ __('messages.dev_step_api_call') }}</h3>
                        <p class="text-muted small">
                            Provide the access token in the <code>Authorization: Bearer {access_token}</code> HTTP header on all API requests:
                        </p>

                        <div class="dev-code-block">
                            <div class="dev-code-toolbar">
                                <span>GET /api/developer/v1/me</span>
                            </div>
                            <pre><code>GET {{ url('/api/developer/v1/me') }} HTTP/1.1
Host: {{ request()->getHost() }}
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json</code></pre>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Step 3: Multi-Language Code Examples -->
            <section id="code-samples" class="card border-0 shadow-sm rounded-4 dev-panel dev-guide-section">
                <div class="card-body p-4">
                    <span class="dev-step-badge">3</span>
                    <h2 class="h4 fw-bold mb-3">{{ __('messages.dev_code_examples') }}</h2>
                    <p class="text-muted small">{{ __('messages.dev_step_3_desc') }}</p>
                    
                    <div class="mt-4">
                        <div class="dev-lang-tabs">
                            <div class="dev-lang-tab is-active" data-lang="php">PHP (cURL)</div>
                            <div class="dev-lang-tab" data-lang="node">Node.js (Axios)</div>
                            <div class="dev-lang-tab" data-lang="python">Python (Requests)</div>
                            <div class="dev-lang-tab" data-lang="csharp">C# (.NET)</div>
                            <div class="dev-lang-tab" data-lang="curl">cURL CLI</div>
                        </div>

                        <div class="dev-code-block dev-code-container">
                            <!-- PHP -->
                            <div class="js-lang-content" data-lang="php">
                                <div class="dev-code-toolbar">
                                    <span>PHP (cURL)</span>
                                    <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="guide-code-php">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
<pre><code id="guide-code-php">&lt;?php
$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';
$code = $_GET['code']; // Code received from authorization redirect

// 1. Exchange code for access token
$ch = curl_init('{{ url('/oauth/token') }}');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'grant_type'    => 'authorization_code',
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri'  => 'https://yourapp.com/callback',
    'code'          => $code
]);

$response = json_decode(curl_exec($ch), true);
$accessToken = $response['access_token'];

// 2. Fetch authenticated member identity
$ch = curl_init('{{ url('/api/developer/v1/me') }}');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $accessToken,
    'Accept: application/json'
]);

$user = json_decode(curl_exec($ch), true);
print_r($user);
?&gt;</code></pre>
                            </div>

                            <!-- Node.js -->
                            <div class="js-lang-content d-none" data-lang="node">
                                <div class="dev-code-toolbar">
                                    <span>Node.js (Axios)</span>
                                    <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="guide-code-node">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
<pre><code id="guide-code-node">const axios = require('axios');

async function authenticateAndFetchUser(authCode) {
    // 1. Exchange authorization code for token
    const tokenResponse = await axios.post('{{ url('/oauth/token') }}', {
        grant_type: 'authorization_code',
        client_id: 'YOUR_CLIENT_ID',
        client_secret: 'YOUR_CLIENT_SECRET',
        redirect_uri: 'https://yourapp.com/callback',
        code: authCode
    });

    const accessToken = tokenResponse.data.access_token;

    // 2. Call Developer API v1 endpoint
    const userResponse = await axios.get('{{ url('/api/developer/v1/me') }}', {
        headers: {
            'Authorization': `Bearer ${accessToken}`,
            'Accept': 'application/json'
        }
    });

    return userResponse.data.data;
}</code></pre>
                            </div>

                            <!-- Python -->
                            <div class="js-lang-content d-none" data-lang="python">
                                <div class="dev-code-toolbar">
                                    <span>Python (Requests)</span>
                                    <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="guide-code-python">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
<pre><code id="guide-code-python">import requests

def get_user_profile(auth_code):
    # 1. Exchange code for access token
    token_url = '{{ url('/oauth/token') }}'
    payload = {
        'grant_type': 'authorization_code',
        'client_id': 'YOUR_CLIENT_ID',
        'client_secret': 'YOUR_CLIENT_SECRET',
        'redirect_uri': 'https://yourapp.com/callback',
        'code': auth_code
    }
    token_res = requests.post(token_url, data=payload)
    access_token = token_res.json().get('access_token')

    # 2. Call Developer API v1
    api_url = '{{ url('/api/developer/v1/me') }}'
    headers = {
        'Authorization': f'Bearer {access_token}',
        'Accept': 'application/json'
    }
    user_res = requests.get(api_url, headers=headers)
    return user_res.json()</code></pre>
                            </div>

                            <!-- C# -->
                            <div class="js-lang-content d-none" data-lang="csharp">
                                <div class="dev-code-toolbar">
                                    <span>C# (HttpClient)</span>
                                    <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="guide-code-csharp">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
<pre><code id="guide-code-csharp">using System.Net.Http;
using System.Net.Http.Headers;
using System.Threading.Tasks;
using System.Collections.Generic;

public async Task&lt;string&gt; GetUserProfile(string authCode) {
    using var client = new HttpClient();

    // 1. Exchange code for token
    var parameters = new Dictionary&lt;string, string&gt; {
        { "grant_type", "authorization_code" },
        { "client_id", "YOUR_CLIENT_ID" },
        { "client_secret", "YOUR_CLIENT_SECRET" },
        { "redirect_uri", "https://yourapp.com/callback" },
        { "code", authCode }
    };

    var content = new FormUrlEncodedContent(parameters);
    var tokenResponse = await client.PostAsync("{{ url('/oauth/token') }}", content);
    var tokenJson = await tokenResponse.Content.ReadAsStringAsync();
    
    // Parse accessToken from tokenJson ...
    string accessToken = "EXTRACTED_ACCESS_TOKEN";

    // 2. Call Developer API v1
    client.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Bearer", accessToken);
    client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));
    
    var userResponse = await client.GetAsync("{{ url('/api/developer/v1/me') }}");
    return await userResponse.Content.ReadAsStringAsync();
}</code></pre>
                            </div>

                            <!-- cURL -->
                            <div class="js-lang-content d-none" data-lang="curl">
                                <div class="dev-code-toolbar">
                                    <span>cURL CLI</span>
                                    <button type="button" class="dev-copy-btn js-dev-copy" data-copy-id="guide-code-curl">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
<pre><code id="guide-code-curl"># 1. Exchange authorization code for token
curl -X POST {{ url('/oauth/token') }} \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "grant_type=authorization_code" \
     -d "client_id=YOUR_CLIENT_ID" \
     -d "client_secret=YOUR_CLIENT_SECRET" \
     -d "code=AUTHORIZATION_CODE" \
     -d "redirect_uri=https://yourapp.com/callback"

# 2. Call Developer API v1 with Bearer token
curl -X GET {{ url('/api/developer/v1/me') }} \
     -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
     -H "Accept: application/json"</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Step 4: Complete Developer API v1 Endpoints Directory -->
            <section id="api-endpoints" class="card border-0 shadow-sm rounded-4 dev-panel dev-guide-section">
                <div class="card-body p-4">
                    <span class="dev-step-badge">4</span>
                    <h2 class="h4 fw-bold mb-3">{{ __('messages.dev_api_endpoints') }}</h2>
                    <p class="text-muted small">
                        {{ __('messages.dev_api_endpoints_desc') }}
                        All requests require the <code>Authorization: Bearer {token}</code> header and are rate-limited to 30 requests per minute.
                    </p>

                    <!-- Group 1: Identity & Profile -->
                    <div class="mt-4">
                        <h3 class="h6 fw-bold mb-3">
                            <i class="fa fa-id-badge text-primary me-2"></i>
                            {{ __('messages.dev_scope_cat_identity') }}
                        </h3>

                        <div class="dev-endpoint-list">
                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.identity.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_identity_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/profile</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.profile.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_profile_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/email</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> user.email.read (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_email_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/social-links</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.social_links.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_social_links_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/follows</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.follows.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_follows_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge post">POST</span>
                                        <span>/api/developer/v1/me/follows</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> user.follows.write (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_follows_write_desc') }}</p>
                                <div class="dev-endpoint-params">
                                    <strong>Payload:</strong> <code>{"target_user_id": 123, "action": "follow|unfollow|toggle"}</code>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group 2: Content & Messaging -->
                    <div class="mt-4">
                        <h3 class="h6 fw-bold mb-3">
                            <i class="fa fa-newspaper text-primary me-2"></i>
                            {{ __('messages.dev_scope_cat_content') }} &amp; {{ __('messages.dev_scope_cat_messaging') }}
                        </h3>

                        <div class="dev-endpoint-list">
                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/content</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.content.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_content_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge post">POST</span>
                                        <span>/api/developer/v1/me/content</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> user.content.write (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_content_write_desc') }}</p>
                                <div class="dev-endpoint-params">
                                    <strong>Payload:</strong> <code>{"content": "Post text", "privacy": "public|followers|private"}</code>
                                </div>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge post">POST</span>
                                        <span>/api/developer/v1/me/reactions</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.reactions.write</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_reactions_write_desc') }}</p>
                                <div class="dev-endpoint-params">
                                    <strong>Payload:</strong> <code>{"status_id": 123}</code>
                                </div>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/messages</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> user.messages.read (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_messages_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge post">POST</span>
                                        <span>/api/developer/v1/me/messages</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> user.messages.write (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_messages_write_desc') }}</p>
                                <div class="dev-endpoint-params">
                                    <strong>Payload:</strong> <code>{"receiver_id": 123, "content": "Message body"}</code>
                                </div>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/notifications</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.notifications.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_notifications_read_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Group 3: Economy, Gamification, Media & Store -->
                    <div class="mt-4">
                        <h3 class="h6 fw-bold mb-3">
                            <i class="fa fa-wallet text-primary me-2"></i>
                            {{ __('messages.dev_scope_cat_gamification') }}, {{ __('messages.dev_scope_cat_community') }} &amp; {{ __('messages.dev_scope_cat_commerce') }}
                        </h3>

                        <div class="dev-endpoint-list">
                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/wallet</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> user.wallet.read (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_wallet_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/badges</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.badges.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_badges_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/clips</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.clips.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_clips_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/forums</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.forums.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_forums_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/store/products</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.store.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_store_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/orders</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> user.orders.read (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_orders_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/me/ads/stats</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> user.ads.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_ads_read_desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Group 4: App Owner Integrations -->
                    <div class="mt-4">
                        <h3 class="h6 fw-bold mb-3">
                            <i class="fa fa-shield-halved text-primary me-2"></i>
                            {{ __('messages.dev_scope_cat_owner') }}
                        </h3>

                        <div class="dev-endpoint-list">
                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/owner/profile</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> owner.profile.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_owner_profile_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge get">GET</span>
                                        <span>/api/developer/v1/owner/content</span>
                                    </div>
                                    <span class="dev-scope-badge"><i class="fa fa-key"></i> owner.content.read</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_owner_content_read_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge post">POST</span>
                                        <span>/api/developer/v1/owner/follow</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> owner.follow.write (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_owner_follow_write_desc') }}</p>
                            </div>

                            <div class="dev-endpoint-card">
                                <div class="dev-endpoint-head">
                                    <div class="dev-endpoint-route">
                                        <span class="dev-method-badge post">POST</span>
                                        <span>/api/developer/v1/owner/messages</span>
                                    </div>
                                    <span class="dev-scope-badge is-sensitive"><i class="fa fa-shield-halved"></i> owner.messages.write (Sensitive)</span>
                                </div>
                                <p class="dev-endpoint-desc">{{ __('messages.dev_scope_owner_messages_write_desc') }}</p>
                                <div class="dev-endpoint-params">
                                    <strong>Payload:</strong> <code>{"content": "Message text"}</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Step 5: OAuth 2.0 Scopes Catalog -->
            <section id="scopes-catalog" class="card border-0 shadow-sm rounded-4 dev-panel dev-guide-section">
                <div class="card-body p-4">
                    <span class="dev-step-badge">5</span>
                    <h2 class="h4 fw-bold mb-3">{{ __('messages.dev_scopes_catalog') }}</h2>
                    <p class="text-muted small">{{ __('messages.dev_scopes_catalog_desc') }}</p>

                    <div class="dev-table-wrap mt-4">
                        <table class="dev-table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Scope Identifier</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scopes ?? [] as $scopeId => $scope)
                                    <tr>
                                        <td>
                                            <span class="badge bg-light text-dark border rounded-pill py-1 px-2 small">
                                                {{ ucfirst($scope['category'] ?? 'general') }}
                                            </span>
                                        </td>
                                        <td>
                                            <code class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small">{{ $scopeId }}</code>
                                        </td>
                                        <td>{{ __($scope['description'] ?? '') }}</td>
                                        <td>
                                            @if(!empty($scope['is_sensitive']))
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill py-1 px-2 small">
                                                    <i class="fa fa-shield-halved me-1"></i> {{ __('messages.dev_sensitive_scope') }}
                                                </span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill py-1 px-2 small">
                                                    <i class="fa fa-check me-1"></i> {{ __('messages.dev_public_scope') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Step 6: Embeddable JavaScript Widgets -->
            <section id="embed-widgets" class="card border-0 shadow-sm rounded-4 dev-panel dev-guide-section">
                <div class="card-body p-4">
                    <span class="dev-step-badge">6</span>
                    <h2 class="h4 fw-bold mb-3">{{ __('messages.dev_widgets_integration') }}</h2>
                    <p class="text-muted small">{{ __('messages.dev_widgets_desc', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>

                    <div class="d-flex flex-column gap-3 mt-4">
                        <!-- Follow Widget -->
                        <div class="card border-0 bg-light rounded-4 p-3">
                            <h5 class="fw-bold fs-6 mb-1"><i class="fa fa-user-plus text-primary me-2"></i>1. Follow Button Widget</h5>
                            <p class="text-muted small mb-2">Embed an interactive button allowing visitors to follow your profile on MYADS with a single click.</p>
                            <div class="dev-code-block">
                                <div class="dev-code-toolbar">
                                    <span>HTML Embed Code</span>
                                    <button type="button" class="dev-copy-btn js-dev-copy" data-copy='&lt;div id="myads-widget-follow-YOUR_APP_ID"&gt;&lt;/div&gt;&#10;&lt;script src="{{ url('/embed/developer/YOUR_APP_ID/follow.js') }}"&gt;&lt;/script&gt;'>
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                                <pre><code>&lt;div id="myads-widget-follow-YOUR_APP_ID"&gt;&lt;/div&gt;
&lt;script src="{{ url('/embed/developer/YOUR_APP_ID/follow.js') }}"&gt;&lt;/script&gt;</code></pre>
                            </div>
                        </div>

                        <!-- Profile Card Widget -->
                        <div class="card border-0 bg-light rounded-4 p-3">
                            <h5 class="fw-bold fs-6 mb-1"><i class="fa fa-id-card text-primary me-2"></i>2. Profile Card Widget</h5>
                            <p class="text-muted small mb-2">Display your verified badge, avatar, bio, follower count, and stats on your website.</p>
                            <div class="dev-code-block">
                                <div class="dev-code-toolbar">
                                    <span>HTML Embed Code</span>
                                    <button type="button" class="dev-copy-btn js-dev-copy" data-copy='&lt;div id="myads-widget-profile-YOUR_APP_ID"&gt;&lt;/div&gt;&#10;&lt;script src="{{ url('/embed/developer/YOUR_APP_ID/profile.js') }}"&gt;&lt;/script&gt;'>
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                                <pre><code>&lt;div id="myads-widget-profile-YOUR_APP_ID"&gt;&lt;/div&gt;
&lt;script src="{{ url('/embed/developer/YOUR_APP_ID/profile.js') }}"&gt;&lt;/script&gt;</code></pre>
                            </div>
                        </div>

                        <!-- Content Stream Widget -->
                        <div class="card border-0 bg-light rounded-4 p-3">
                            <h5 class="fw-bold fs-6 mb-1"><i class="fa fa-rss text-primary me-2"></i>3. Latest Content Feed Widget</h5>
                            <p class="text-muted small mb-2">Showcase your latest public posts and status updates dynamically inside your web application.</p>
                            <div class="dev-code-block">
                                <div class="dev-code-toolbar">
                                    <span>HTML Embed Code</span>
                                    <button type="button" class="dev-copy-btn js-dev-copy" data-copy='&lt;div id="myads-widget-content-YOUR_APP_ID"&gt;&lt;/div&gt;&#10;&lt;script src="{{ url('/embed/developer/YOUR_APP_ID/content.js') }}"&gt;&lt;/script&gt;'>
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                                <pre><code>&lt;div id="myads-widget-content-YOUR_APP_ID"&gt;&lt;/div&gt;
&lt;script src="{{ url('/embed/developer/YOUR_APP_ID/content.js') }}"&gt;&lt;/script&gt;</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Step 7: External Web Share API -->
            <section id="share-api" class="card border-0 shadow-sm rounded-4 dev-panel dev-guide-section">
                <div class="card-body p-4">
                    <span class="dev-step-badge">7</span>
                    <h2 class="h4 fw-bold mb-3">{{ __('messages.dev_share_api_title') }}</h2>
                    <p class="text-muted small">{{ __('messages.dev_share_desc') }}</p>

                    <div class="dev-code-block mt-4">
                        <div class="dev-code-toolbar">
                            <span>GET /share Endpoint</span>
                            <button type="button" class="dev-copy-btn js-dev-copy" data-copy="{{ url('/share') }}?text=Check+out+this+article!+https://example.com">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                        <pre><code>{{ url('/share') }}?text=Check+out+this+article!+https://example.com</code></pre>
                    </div>

                    <div class="mt-3">
                        <a href="{{ url('/share') }}?text=Check+out+the+Developer+Platform!+{{ url('/developer') }}" target="_blank" class="btn btn-outline-primary rounded-pill fw-bold px-4">
                            <i class="fa fa-arrow-up-right-from-square me-1"></i> Test Live Share Composer
                        </a>
                    </div>
                </div>
            </section>

            <!-- Step 8: Rate Limiting & Response Format -->
            <section id="rate-limits" class="card border-0 shadow-sm rounded-4 dev-panel dev-guide-section">
                <div class="card-body p-4">
                    <span class="dev-step-badge">8</span>
                    <h2 class="h4 fw-bold mb-3">{{ __('messages.dev_rate_limits_title') }}</h2>
                    <p class="text-muted small">{{ __('messages.dev_rate_limits_desc') }}</p>

                    <div class="dev-rule-list mt-4">
                        <div class="dev-rule-item">
                            <strong>Rate Limiting</strong>
                            <span class="dev-help-text">Standard Developer API endpoints: <strong>30 requests per minute</strong> per client IP. Rate-limited requests receive HTTP <code>429 Too Many Requests</code>.</span>
                        </div>
                        <div class="dev-rule-item">
                            <strong>Standard JSON Response Envelope</strong>
                            <span class="dev-help-text">Every response contains consistent <code>success</code>, <code>message</code>, and <code>data</code> fields:</span>
                            <div class="dev-code-block mt-2">
                                <pre><code>{
    "success": true,
    "message": "Operation completed successfully.",
    "data": { ... }
}</code></pre>
                            </div>
                        </div>
                        <div class="dev-rule-item">
                            <strong>Localization Support (Accept-Language)</strong>
                            <span class="dev-help-text">Send <code>Accept-Language: ar</code> or <code>Accept-Language: en</code> in request headers to receive localized responses and validation messages.</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @include('theme::developer.partials.scripts')
    <script>
        // Language tab switching
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

        // Copy button enhancement for specific IDs
        document.querySelectorAll('.js-dev-copy').forEach(btn => {
            const copyId = btn.getAttribute('data-copy-id');
            if (copyId) {
                btn.addEventListener('click', () => {
                    const code = document.getElementById(copyId).innerText;
                    navigator.clipboard.writeText(code).then(() => {
                        btn.setAttribute('data-copied', 'true');
                        btn.innerHTML = '<i class="fa fa-check"></i>';
                        setTimeout(() => {
                            btn.setAttribute('data-copied', 'false');
                            btn.innerHTML = '<i class="fa fa-copy"></i>';
                        }, 2000);
                    });
                });
            }
        });

        // TOC active highlight on scroll
        const sections = document.querySelectorAll('.dev-guide-section');
        const navLinks = document.querySelectorAll('.dev-toc-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 120;
                if (pageYOffset >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('is-active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('is-active');
                }
            });
        });
    </script>
@endpush
