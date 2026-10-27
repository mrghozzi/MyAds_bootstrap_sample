@php
    $config = [];
    if (!empty($widget->o_valuer)) {
        if (is_array($widget->o_valuer)) {
            $config = $widget->o_valuer;
        } else {
            $decoded = json_decode((string) $widget->o_valuer, true);
            $config = is_array($decoded) ? $decoded : [];
        }
    }

    $siteTitle = $site_settings->titer ?? config('app.name', 'MyAds');
    $showTitle = (bool) ($config['show_title'] ?? false);
    $showHelp = (bool) ($config['show_help'] ?? true);
    $showSitemap = (bool) ($config['show_sitemap'] ?? true);
    $showDeveloper = (bool) ($config['show_developer'] ?? true);
    $showPrivacy = (bool) ($config['show_privacy'] ?? true);
    $showTerms = (bool) ($config['show_terms'] ?? true);
    $showRefund = (bool) ($config['show_refund'] ?? true);
    $showSocial = (bool) ($config['show_social'] ?? false);
    $showPoweredBy = (bool) ($config['show_powered_by'] ?? true);
    
    $layoutStyle = $config['layout_style'] ?? 'centered';
    $bgStyle = $config['background_style'] ?? 'card';

    // Social Links Fallbacks
    $facebookUrl = !empty($config['facebook_url']) ? $config['facebook_url'] : ($site_settings->facebook ?? '');
    $twitterUrl = !empty($config['twitter_url']) ? $config['twitter_url'] : ($site_settings->twitter ?? '');
    $linkedinUrl = !empty($config['linkedin_url']) ? $config['linkedin_url'] : ($site_settings->linkedin ?? '');
    $telegramUrl = $config['telegram_url'] ?? '';
    $youtubeUrl = $config['youtube_url'] ?? '';
    $githubUrl = $config['github_url'] ?? '';

    // Custom Extra Links
    $customLinks = [];
    if (!empty($config['custom_links_text'])) {
        $lines = explode("\n", str_replace("\r", "", $config['custom_links_text']));
        foreach ($lines as $line) {
            $parts = explode("|", trim($line));
            if (count($parts) >= 2) {
                $customLinks[] = [
                    'label' => trim($parts[0]),
                    'url' => trim($parts[1]),
                ];
            }
        }
    }

    // Copyright Text
    $copyrightText = !empty($config['custom_copyright'])
        ? str_replace([':year', ':site'], [date('Y'), $siteTitle], $config['custom_copyright'])
        : ('&copy; ' . date('Y') . ' ' . $siteTitle . '. ' . (__('messages.all_rights_reserved') ?? 'All rights reserved.'));

    // Powered By Text
    $poweredByText = !empty($config['powered_by_text'])
        ? str_replace([':site', ':version'], [$siteTitle, \App\Support\SystemVersion::tag()], $config['powered_by_text'])
        : ('Powered by <strong>' . $siteTitle . ' SEO Engine</strong> | ' . \App\Support\SystemVersion::tag());

    $cardClass = match($bgStyle) {
        'subtle' => 'bg-body-tertiary border-0 shadow-sm',
        'transparent' => 'bg-transparent border-0 shadow-none',
        default => 'card border-0 shadow-sm',
    };
@endphp

<div class="{{ $cardClass }} rounded-4 mb-4">
    @if($showTitle && !empty($widget->name))
        <div class="card-header bg-white py-3 border-bottom-0">
            <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
        </div>
    @endif
    <div class="card-body {{ $showTitle && !empty($widget->name) ? 'pt-0' : 'py-4' }} {{ $layoutStyle === 'centered' ? 'text-center' : '' }}">
        @if($layoutStyle === 'columns')
            <div class="row g-4 text-start">
                <div class="col-md-4">
                    <h6 class="fw-bold text-dark mb-2">{{ $siteTitle }}</h6>
                    <p class="text-muted small mb-0">{{ __('messages.footer_about_short', ['site' => $siteTitle]) ?? 'Interactive community platform and digital marketplace.' }}</p>
                </div>
                <div class="col-md-5">
                    <h6 class="fw-bold text-dark mb-2">{{ __('messages.quick_links') ?? 'Quick Links' }}</h6>
                    <div class="d-flex flex-wrap gap-3">
                        @if($showSitemap)<a href="{{ route('sitemap.xml') }}" class="text-secondary text-decoration-none small hover-primary"><i class="fa-solid fa-sitemap me-1"></i> {{ __('messages.sitemap') }}</a>@endif
                        @if($showHelp)<a href="{{ route('help.index') }}" class="text-secondary text-decoration-none small hover-primary"><i class="fa-solid fa-circle-question me-1"></i> {{ __('help.help_center') ?? 'Help Center' }}</a>@endif
                        @if($showDeveloper)<a href="{{ route('developer.index') }}" class="text-secondary text-decoration-none small hover-primary"><i class="fa-solid fa-code me-1"></i> {{ __('messages.developers') }}</a>@endif
                        @if($showPrivacy)<a href="{{ route('privacy') }}" class="text-secondary text-decoration-none small hover-primary"><i class="fa-solid fa-shield-halved me-1"></i> {{ __('messages.privacy_policy') }}</a>@endif
                        @if($showTerms)<a href="{{ route('terms') }}" class="text-secondary text-decoration-none small hover-primary"><i class="fa-solid fa-file-contract me-1"></i> {{ __('messages.terms_conditions') }}</a>@endif
                        @if($showRefund)<a href="{{ route('refund') }}" class="text-secondary text-decoration-none small hover-primary"><i class="fa-solid fa-rotate-left me-1"></i> {{ __('messages.refund_policy') }}</a>@endif
                        @foreach($customLinks as $cl)
                            <a href="{{ $cl['url'] }}" class="text-secondary text-decoration-none small hover-primary">{{ $cl['label'] }}</a>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3">
                    @if($showSocial)
                        <h6 class="fw-bold text-dark mb-2">{{ __('messages.follow_us') ?? 'Follow Us' }}</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($facebookUrl)<a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fa-brands fa-facebook-f"></i></a>@endif
                            @if($twitterUrl)<a href="{{ $twitterUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fa-brands fa-x-twitter"></i></a>@endif
                            @if($linkedinUrl)<a href="{{ $linkedinUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fa-brands fa-linkedin-in"></i></a>@endif
                            @if($telegramUrl)<a href="{{ $telegramUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fa-brands fa-telegram"></i></a>@endif
                            @if($youtubeUrl)<a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fa-brands fa-youtube"></i></a>@endif
                            @if($githubUrl)<a href="{{ $githubUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fa-brands fa-github"></i></a>@endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Centered or Compact -->
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-3">
                @if($showSitemap)
                    <a href="{{ route('sitemap.xml') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-sitemap opacity-75"></i>
                        {{ __('messages.sitemap') }}
                    </a>
                @endif
                @if($showHelp)
                    <a href="{{ route('help.index') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-circle-question opacity-75"></i>
                        {{ __('help.help_center') ?? 'Help Center' }}
                    </a>
                @endif
                @if($showDeveloper)
                    <a href="{{ route('developer.index') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-code opacity-75"></i>
                        {{ __('messages.developers') }}
                    </a>
                @endif
                @if($showPrivacy)
                    <a href="{{ route('privacy') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-shield-halved opacity-75"></i>
                        {{ __('messages.privacy_policy') }}
                    </a>
                @endif
                @if($showTerms)
                    <a href="{{ route('terms') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-file-contract opacity-75"></i>
                        {{ __('messages.terms_conditions') }}
                    </a>
                @endif
                @if($showRefund)
                    <a href="{{ route('refund') }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-rotate-left opacity-75"></i>
                        {{ __('messages.refund_policy') }}
                    </a>
                @endif
                @foreach($customLinks as $cl)
                    <a href="{{ $cl['url'] }}" class="text-secondary text-decoration-none small fw-bold hover-primary d-inline-flex align-items-center gap-1">
                        {{ $cl['label'] }}
                    </a>
                @endforeach
            </div>

            @if($showSocial)
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($facebookUrl)<a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;"><i class="fa-brands fa-facebook-f"></i></a>@endif
                    @if($twitterUrl)<a href="{{ $twitterUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;"><i class="fa-brands fa-x-twitter"></i></a>@endif
                    @if($linkedinUrl)<a href="{{ $linkedinUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;"><i class="fa-brands fa-linkedin-in"></i></a>@endif
                    @if($telegramUrl)<a href="{{ $telegramUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;"><i class="fa-brands fa-telegram"></i></a>@endif
                    @if($youtubeUrl)<a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;"><i class="fa-brands fa-youtube"></i></a>@endif
                    @if($githubUrl)<a href="{{ $githubUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;"><i class="fa-brands fa-github"></i></a>@endif
                </div>
            @endif
        @endif

        <div class="border-top border-light pt-3">
            <p class="text-muted smaller mb-1">{!! $copyrightText !!}</p>
            @if($showPoweredBy)
                <p class="text-muted smaller mb-0 opacity-75">
                    {!! $poweredByText !!}
                </p>
            @endif
        </div>
    </div>
</div>
