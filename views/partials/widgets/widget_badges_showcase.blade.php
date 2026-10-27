@php
    $config = [];
    if (!empty($widget?->o_valuer)) {
        if (is_array($widget->o_valuer)) {
            $config = $widget->o_valuer;
        } else {
            $decoded = json_decode((string) $widget->o_valuer, true);
            $config = is_array($decoded) ? $decoded : [];
        }
    }

    $maxBadges = (int) ($config['max_badges'] ?? 8);
    $badgeSize = $config['badge_size'] ?? 'medium';
    $showButton = isset($config['show_button']) ? (bool) $config['show_button'] : true;
    $guestVisibility = !empty($config['guest_visibility']);

    $sizePx = 40;
    $fontPx = '1.1rem';
    if ($badgeSize === 'small') {
        $sizePx = 32;
        $fontPx = '0.9rem';
    } elseif ($badgeSize === 'large') {
        $sizePx = 48;
        $fontPx = '1.3rem';
    }

    $showcaseBadges = [];
    if (auth()->check()) {
        $showcaseBadges = \App\Models\BadgeShowcase::where('user_id', auth()->id())
            ->with('badge')
            ->orderBy('sort_order', 'asc')
            ->limit($maxBadges)
            ->get();
    }
@endphp

@if(auth()->check() && count($showcaseBadges) > 0)
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name ?? __('messages.your_badges') }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-wrap gap-2 mb-3">
            @foreach($showcaseBadges as $item)
                @if($item->badge)
                <div class="badge-item rounded-3 d-flex align-items-center justify-content-center text-white" 
                     title="{{ __('messages.' . $item->badge->name_key) }}: {{ __('messages.' . $item->badge->description_key) }}" 
                     style="width: {{ $sizePx }}px; height: {{ $sizePx }}px; background: {{ $item->badge->color ?? '#615dfa' }}; font-size: {{ $fontPx }}; transition: transform 0.2s;"
                     onmouseover="this.style.transform='scale(1.1)'"
                     onmouseout="this.style.transform='scale(1)'">
                    @if($item->badge->icon && str_contains($item->badge->icon, ' '))
                        <i class="{{ $item->badge->icon }}"></i>
                    @elseif($item->badge->icon && str_starts_with($item->badge->icon, 'fa-'))
                        <i class="fa {{ $item->badge->icon }}"></i>
                    @else
                        <i class="fa fa-trophy"></i>
                    @endif
                </div>
                @endif
            @endforeach
        </div>
        @if($showButton)
            <a class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold" href="{{ route('profile.show', ['username' => auth()->user()->username, 'tab' => 'badges']) }}">
                {{ __('messages.manage_badges') ?? 'Manage Badges' }}
            </a>
        @endif
    </div>
</div>
@elseif(!auth()->check() && $guestVisibility)
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name ?? __('messages.badges') }}</h6>
    </div>
    <div class="card-body pt-0 text-center py-4">
        <i class="fa fa-award fs-1 text-primary mb-2 opacity-75"></i>
        <p class="small text-muted mb-3">{{ __('messages.earn_badges_desc') ?? 'Join our community, complete quests, and earn prestigious badges!' }}</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">{{ __('messages.register') }}</a>
    </div>
</div>
@endif
