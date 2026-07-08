<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 activity-post-card news-card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold text-uppercase">
                <i class="fa fa-newspaper me-1"></i>
                {{ __('messages.news') }}
            </span>
            <span class="text-muted small fw-bold">
                <i class="fa-regular fa-calendar me-1"></i>
                {{ $activity->date_formatted }}
            </span>
        </div>

        <h4 class="card-title fw-black mb-3">
            <a href="{{ route('news.show', $activity->related_content->id) }}" class="text-dark text-decoration-none hover-primary">{{ $activity->related_content->name }}</a>
        </h4>

        <div class="card-text text-secondary news-preview-content markdown-news-preview mb-4 lh-lg" data-news-id="{{ $activity->related_content->id }}">
            {!! $activity->related_content->text !!}
        </div>

        <div class="d-flex justify-content-end border-top border-light pt-3">
            <a href="{{ route('news.show', $activity->related_content->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-black">
                {{ __('messages.details') }} <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
