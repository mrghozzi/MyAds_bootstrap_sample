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

    $limit = (int) ($config['limit'] ?? 3);
    $showDate = isset($config['show_date']) ? (bool) $config['show_date'] : true;
    $showButton = isset($config['show_button']) ? (bool) $config['show_button'] : true;
    $buttonText = !empty($config['button_text']) ? $config['button_text'] : __('messages.see_all');

    $newsItems = \App\Models\News::where('statu', 1)->latest('id')->limit($limit)->get();
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @forelse($newsItems as $news)
                <div class="d-flex flex-column">
                    <h6 class="mb-1 fw-bold small text-truncate">
                        <a href="{{ route('news.show', $news->id) }}" class="text-dark text-decoration-none hover-primary">{{ $news->name }}</a>
                    </h6>
                    @if($showDate)
                        <small class="text-muted smaller"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::createFromTimestamp($news->date ?? time())->diffForHumans() }}</small>
                    @endif
                </div>
            @empty
                <p class="text-center text-muted small my-3">{{ __('messages.no_post') }}</p>
            @endforelse
        </div>
        @if($showButton)
            <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-3 fw-bold">
                {{ $buttonText }}
            </a>
        @endif
    </div>
</div>
