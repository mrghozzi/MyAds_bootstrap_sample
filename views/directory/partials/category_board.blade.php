@if($categoryBoard->isNotEmpty())
    <div class="list-group list-group-flush">
        @foreach($categoryBoard as $entry)
            <div class="list-group-item border-light py-3 transition-all hover-bg-light">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex gap-3">
                        <div class="text-info opacity-75 mt-1">
                            <i class="fa fa-folder-open fa-lg"></i>
                        </div>
                        <div>
                            <a href="{{ route('directory.category.legacy', $entry['category']->id) }}" class="text-dark text-decoration-none fw-bold d-block mb-1">
                                {{ $entry['category']->name }}
                            </a>
                            @if($entry['category']->txt)
                                <p class="text-muted small mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($entry['category']->txt), 80) }}</p>
                            @endif
                        </div>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill border">{{ $entry['listing_count'] }}</span>
                </div>
                
                @if($entry['children']->isNotEmpty())
                    <div class="ms-4 ps-2 mt-2 d-flex flex-wrap gap-2">
                        @foreach($entry['children'] as $child)
                            <a href="{{ route('directory.category.legacy', $child['category']->id) }}" class="badge bg-info bg-opacity-10 text-info text-decoration-none rounded-pill px-3 py-2 border border-info border-opacity-25 transition-all hover-bg-info hover-text-white">
                                {{ $child['category']->name }} <span class="ms-1 opacity-75">({{ $child['listing_count'] }})</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    <style>
        .hover-bg-light:hover { background-color: #f8f9fa; }
        .hover-bg-info:hover { background-color: #0dcaf0 !important; }
        .hover-text-white:hover { color: #fff !important; }
    </style>
@endif
