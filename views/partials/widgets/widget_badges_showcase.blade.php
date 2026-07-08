@php
    $showcaseBadges = [];
    if (auth()->check()) {
        $showcaseBadges = \App\Models\BadgeShowcase::where('user_id', auth()->id())
            ->with('badge')
            ->orderBy('sort_order', 'asc')
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
                     style="width: 40px; height: 40px; background: {{ $item->badge->color ?? '#615dfa' }}; font-size: 1.1rem; transition: transform 0.2s;"
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
        <a class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold" href="{{ route('profile.show', ['username' => auth()->user()->username, 'tab' => 'badges']) }}">
            {{ __('messages.manage_badges') ?? 'Manage Badges' }}
        </a>
    </div>
</div>
@endif
