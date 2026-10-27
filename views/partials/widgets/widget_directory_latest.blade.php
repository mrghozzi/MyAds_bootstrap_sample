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

    $limit = (int) ($config['limit'] ?? 5);
    $categoryId = (int) ($config['category_id'] ?? 0);
    $showCategory = isset($config['show_category']) ? (bool) $config['show_category'] : true;
    $showDate = isset($config['show_date']) ? (bool) $config['show_date'] : true;
    $showButton = isset($config['show_button']) ? (bool) $config['show_button'] : true;
    $buttonText = !empty($config['button_text']) ? $config['button_text'] : __('messages.see_all');

    $query = \App\Models\Directory::where('statu', 1)->latest('id');
    if ($categoryId > 0) {
        $query->where('cat', $categoryId);
    }
    $listings = $query->limit($limit)->get();
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @forelse($listings as $listing)
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border" style="width: 38px; height: 38px; min-width: 38px;">
                        <img src="{{ theme_asset('img/directory/category/all-01.png') }}" width="20" height="20">
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <h6 class="mb-0 fw-bold small text-truncate">
                            <a href="{{ route('directory.show', $listing->id) }}" class="text-dark text-decoration-none hover-primary">{{ $listing->name }}</a>
                        </h6>
                        <small class="text-muted smaller d-block text-truncate">
                            @if($showCategory)
                                {{ $listing->category?->name ?? __('messages.directory') }}
                            @endif
                            @if($showCategory && $showDate) &bull; @endif
                            @if($showDate)
                                {{ \Carbon\Carbon::createFromTimestamp($listing->date)->diffForHumans() }}
                            @endif
                        </small>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted small my-3">{{ __('messages.no_listings_found') }}</p>
            @endforelse
        </div>
        @if($showButton)
            <a href="{{ route('directory.index') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-3 fw-bold">
                {{ $buttonText }}
            </a>
        @endif
    </div>
</div>
