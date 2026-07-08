@php
    $newsItems = \App\Models\News::where('statu', 1)->latest('id')->limit(3)->get();
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
                    <small class="text-muted smaller"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::createFromTimestamp($news->date ?? time())->diffForHumans() }}</small>
                </div>
            @empty
                <p class="text-center text-muted small my-3">{{ __('messages.no_post') }}</p>
            @endforelse
        </div>
        <a href="{{ route('news.index') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 mt-3 fw-bold">
            {{ __('messages.see_all') }}
        </a>
    </div>
</div>
