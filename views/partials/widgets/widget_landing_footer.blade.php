<div class="card border-0 shadow-sm rounded-4 mb-4">
    @if($widget->name)
        <div class="card-header bg-white py-3 border-bottom-0">
            <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
        </div>
    @endif
    <div class="card-body {{ $widget->name ? 'pt-0' : 'py-4' }} text-center">
        <div class="d-flex justify-content-center gap-3 flex-wrap mb-3">
            <a href="{{ route('sitemap.xml') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                <i class="fa-solid fa-sitemap opacity-75"></i>
                {{ __('messages.sitemap') }}
            </a>
            <a href="{{ route('developer.index') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                <i class="fa-solid fa-code opacity-75"></i>
                {{ __('messages.developers') }}
            </a>
            <a href="{{ route('privacy') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                <i class="fa-solid fa-shield-halved opacity-75"></i>
                {{ __('messages.privacy_policy') }}
            </a>
            <a href="{{ route('terms') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                <i class="fa-solid fa-file-contract opacity-75"></i>
                {{ __('messages.terms_conditions') }}
            </a>
        </div>
        <div class="border-top border-light pt-3">
            <p class="text-muted smaller mb-1">&copy; {{ date('Y') }} {{ $site_settings->titer ?? 'MyAds' }}. {{ __('messages.all_rights_reserved') }}</p>
            <p class="text-muted smaller mb-0 opacity-75">
                Powered by <strong>MyAds SEO Engine</strong> | {{ \App\Support\SystemVersion::tag() }}
            </p>
        </div>
    </div>
</div>
