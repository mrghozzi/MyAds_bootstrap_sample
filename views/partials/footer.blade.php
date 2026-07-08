<footer class="bg-white border-top py-5 mt-5">
    <div class="container text-center">
        <div class="mb-4">
            <a href="{{ route('sitemap.xml') }}" class="text-decoration-none text-muted mx-3">
                <i class="fa-solid fa-sitemap me-1"></i> {{ __('messages.sitemap') }}
            </a>
            <a href="{{ route('developer.index') }}" class="text-decoration-none text-muted mx-3">
                <i class="fa-solid fa-code me-1"></i> {{ __('messages.developers') }}
            </a>
            <a href="{{ route('privacy') }}" class="text-decoration-none text-muted mx-3">
                <i class="fa-solid fa-shield-halved me-1"></i> {{ __('messages.privacy_policy') }}
            </a>
            <a href="{{ route('terms') }}" class="text-decoration-none text-muted mx-3">
                <i class="fa-solid fa-file-contract me-1"></i> {{ __('messages.terms_conditions') }}
            </a>
        </div>
        <p class="text-muted small mb-1">&copy; {{ date('Y') }} {{ $site_settings->titer ?? 'MyAds' }}. {{ __('messages.all_rights_reserved') }}</p>
        <p class="text-muted small">
            Powered by <strong>MyAds</strong> | {{ \App\Support\SystemVersion::tag() }}
        </p>
    </div>
</footer>
