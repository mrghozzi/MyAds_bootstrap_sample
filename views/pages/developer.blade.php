@extends('theme::layouts.master')

@push('head')
<style>
    .docs-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .code-block {
        background-color: var(--dev-surface-soft, #1d2333);
        color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        font-family: var(--bs-font-monospace);
        font-size: 0.875rem;
    }
    [data-theme="css"] .code-block {
        background-color: #f8f9fa;
        color: #212529;
        border: 1px solid var(--bs-border-color);
    }
</style>
@endpush

@section('content')
<!-- SECTION BANNER -->
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%;" >
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}"  alt="docs-icon">
    <p class="section-banner-title">{{ __('messages.developer_docs') }}</p>
    <p class="section-banner-text">{{ __('messages.developer_page_description', ['site' => $site_settings->titer ?? 'MYADS']) }}</p>
</div>

<div class="docs-container py-5">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-md-5">
            <h2 class="h4 fw-bold mb-4 d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fa fa-plug"></i>
                </div>
                {{ __('messages.share_api') }}
            </h2>
            <p class="text-muted mb-4">
                {{ __('messages.share_api_intro', ['site' => $site_settings->titer ?? 'MYADS']) ?? 'The External Share API allows you to integrate a "Share on '.($site_settings->titer ?? 'MYADS').'" button on your website. When users click this button, they will be redirected to '.($site_settings->titer ?? 'MYADS').' with a post composer pre-filled with your content.' }}
            </p>
            
            <div class="mb-4">
                <h4 class="h6 fw-bold mb-3">{{ __('messages.api_endpoint') }}</h4>
                <div class="code-block">
                    <code>{{ url('/') }}/share</code>
                </div>
            </div>

            <div>
                <h4 class="h6 fw-bold mb-3">{{ __('messages.parameters') }}</h4>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-bold text-muted">{{ __('messages.name') }}</th>
                                <th class="text-uppercase small fw-bold text-muted">{{ __('messages.type') }}</th>
                                <th class="text-uppercase small fw-bold text-muted">{{ __('messages.description') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code class="fw-bold">text</code></td>
                                <td><span class="badge bg-primary rounded-pill">String</span></td>
                                <td class="text-muted small">{{ __('messages.param_text_desc') ?? 'The text content to be pre-filled in the post composer. Can include links, hashtags, and mentions.' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-md-5">
            <h2 class="h4 fw-bold mb-4 d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fa fa-code"></i>
                </div>
                {{ __('messages.usage_examples') }}
            </h2>

            <div class="mb-4">
                <h4 class="h6 fw-bold mb-2">HTML Link</h4>
                <p class="text-muted small mb-3">{{ __('messages.example_html_desc') ?? 'The simplest way to integrate sharing is a standard anchor tag.' }}</p>
                <div class="code-block">
                    <code>&lt;a href="{{ url('/') }}/share?text=Check out this site! {{ url('/') }}" target="_blank"&gt;
    Share on {{ $site_settings->titer ?? 'MYADS' }}
&lt;/a&gt;</code>
                </div>
            </div>

            <div>
                <h4 class="h6 fw-bold mb-2">JavaScript Button</h4>
                <p class="text-muted small mb-3">{{ __('messages.example_js_desc') ?? 'You can also use JavaScript to dynamically generate the share URL.' }}</p>
                <div class="code-block">
                    <code>function shareOnMyAds(text) {
    const baseUrl = "{{ url('/') }}/share";
    const shareUrl = `${baseUrl}?text=${encodeURIComponent(text)}`;
    window.open(shareUrl, '_blank');
}

// Usage
shareOnMyAds("I love using {{ $site_settings->titer ?? 'MYADS' }}! #Social #AdExchange");</code>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4 p-md-5">
            <h2 class="h4 fw-bold mb-4 d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fa fa-lightbulb"></i>
                </div>
                {{ __('messages.best_practices') }}
            </h2>
            <ul class="list-unstyled mb-0 d-grid gap-3">
                <li class="d-flex align-items-start gap-3">
                    <i class="fa fa-hashtag text-muted mt-1"></i>
                    <div>
                        <strong class="d-block mb-1">{{ __('messages.use_hashtags') ?? 'Use Hashtags' }}</strong>
                        <span class="text-muted small">{{ __('messages.best_practice_hashtags') ?? 'Include hashtags to make your content discoverable in the community feed.' }}</span>
                    </div>
                </li>
                <li class="d-flex align-items-start gap-3">
                    <i class="fa fa-link text-muted mt-1"></i>
                    <div>
                        <strong class="d-block mb-1">{{ __('messages.include_links') ?? 'Include Links' }}</strong>
                        <span class="text-muted small">{{ __('messages.best_practice_links') ?? 'The system will automatically generate a link preview if a valid URL is included.' }}</span>
                    </div>
                </li>
                <li class="d-flex align-items-start gap-3">
                    <i class="fa fa-percent text-muted mt-1"></i>
                    <div>
                        <strong class="d-block mb-1">{{ __('messages.encoding') ?? 'URL Encoding' }}</strong>
                        <span class="text-muted small">{{ __('messages.best_practice_encoding') ?? 'Always URL-encode the text parameter to ensure special characters and spaces are handled correctly.' }}</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
