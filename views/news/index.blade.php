@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-newspaper fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.news') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.latest_news') }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-bullhorn fa-10x"></i>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <a href="{{ url('/home') }}" class="btn btn-outline-primary w-100 fw-black rounded-pill py-2 shadow-sm transition-all hover-translate-y">
                        <i class="fa fa-home me-2"></i> {{ __('messages.board') }}
                    </a>
                </div>
            </div>
            <x-widget-column side="news_side" />
        </div>

        <!-- News Feed -->
        <div class="col-lg-9">
            <div class="news-feed-stack d-grid gap-3" id="news_cards">
                @forelse($news as $item)
                    @php
                        $newsExcerpt = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($item->text))), 250);
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 news-card transition-all hover-translate-y">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill smaller fw-black text-uppercase">
                                    <i class="fa fa-tag me-1"></i> {{ __('messages.news') }}
                                </span>
                                <small class="text-muted fw-bold opacity-75">
                                    <i class="fa fa-clock me-1"></i> {{ $item->date ? date('M d, Y', $item->date) : '' }}
                                </small>
                            </div>
                            <h3 class="fw-black mb-3 text-dark">
                                <a href="{{ route('news.show', $item->id) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $item->name }}
                                </a>
                            </h3>
                            <p class="text-muted lh-lg mb-4 fs-6">{{ $newsExcerpt }}</p>
                            <div class="d-flex justify-content-end align-items-center mt-3 pt-3 border-top border-light">
                                <a href="{{ route('news.show', $item->id) }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-black shadow-sm">
                                    {{ __('messages.details') }} <i class="fa fa-arrow-right ms-2 small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-50">
                        <div class="mb-3">
                            <i class="fa fa-newspaper fa-4x text-muted opacity-25"></i>
                        </div>
                        <h4 class="fw-black text-muted">{{ __('messages.no_news_found') }}</h4>
                    </div>
                @endforelse
            </div>

            @if($news->hasMorePages())
                <div class="text-center mt-5">
                    <button type="button" class="btn btn-primary btn-lg rounded-pill px-5 fw-black shadow-sm transition-all hover-translate-y" id="news_load_more_btn" data-next-page="{{ $news->currentPage() + 1 }}" data-endpoint="{{ route('news.index') }}">
                        {{ __('messages.more_topics') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .hover-primary:hover { color: #615dfa !important; }
    .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2) !important; }
    .news-card { border: 1px solid rgba(0,0,0,0.02) !important; }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('news_load_more_btn');
        const container = document.getElementById('news_cards');
        if (!loadMoreBtn) return;

        loadMoreBtn.addEventListener('click', async function() {
            const nextPage = loadMoreBtn.getAttribute('data-next-page');
            const endpoint = loadMoreBtn.getAttribute('data-endpoint');
            
            loadMoreBtn.disabled = true;
            const originalContent = loadMoreBtn.innerHTML;
            loadMoreBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Loading...';

            try {
                const response = await fetch(`${endpoint}?page=${nextPage}&ajax=1`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (data.items && data.items.length > 0) {
                    data.items.forEach(item => {
                        const html = `
                            <div class="card border-0 shadow-sm rounded-4 news-card transition-all hover-translate-y mb-3">
                                <div class="card-body p-4 p-md-5">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill smaller fw-black text-uppercase">
                                            <i class="fa fa-tag me-1"></i> News
                                        </span>
                                        <small class="text-muted fw-bold opacity-75">
                                            <i class="fa fa-clock me-1"></i> ${item.date}
                                        </small>
                                    </div>
                                    <h3 class="fw-black mb-3 text-dark">
                                        <a href="/news/${item.id}" class="text-dark text-decoration-none hover-primary">
                                            ${item.name}
                                        </a>
                                    </h3>
                                    <p class="text-muted lh-lg mb-4 fs-6">${item.excerpt}</p>
                                    <div class="d-flex justify-content-end align-items-center mt-3 pt-3 border-top border-light">
                                        <a href="/news/${item.id}" class="btn btn-primary btn-sm rounded-pill px-4 fw-black shadow-sm">
                                            Details <i class="fa fa-arrow-right ms-2 small"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', html);
                    });
                }

                if (data.next_page) {
                    loadMoreBtn.setAttribute('data-next-page', data.next_page);
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = originalContent;
                } else {
                    loadMoreBtn.remove();
                }
            } catch (e) {
                console.error(e);
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = 'Try Again';
            }
        });
    });
</script>
@endpush
