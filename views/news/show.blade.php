@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 px-4 rounded-pill shadow-sm border">
            <li class="breadcrumb-item"><a href="{{ route('news.index') }}" class="text-decoration-none text-primary fw-bold"><i class="fa fa-newspaper"></i></a></li>
            <li class="breadcrumb-item active text-truncate small fw-bold" aria-current="page" style="max-width: 300px;">{{ $article->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <a href="{{ route('news.index') }}" class="btn btn-outline-dark w-100 fw-black rounded-pill py-2 shadow-sm transition-all hover-bg-dark hover-text-white">
                        <i class="fa fa-arrow-left me-2"></i> {{ __('messages.back_to_news') }}
                    </a>
                </div>
            </div>
            <x-widget-column side="news_side" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <article class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <header class="mb-5 pb-4 border-bottom">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill smaller fw-black text-uppercase">
                                <i class="fa fa-tag me-1"></i> {{ __('messages.news') }}
                            </span>
                            <span class="text-muted smaller fw-bold opacity-75">
                                <i class="fa fa-calendar me-1"></i> {{ $article->date ? date('M d, Y', $article->date) : '' }}
                            </span>
                        </div>
                        <h1 class="fw-black mb-0 text-dark display-5 lh-tight">{{ $article->name }}</h1>
                    </header>
                    
                    <div class="news-article-content markdown-content lh-lg text-dark fs-5">
                        {!! $article->text !!}
                    </div>

                    <footer class="mt-5 pt-5 border-top border-light">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="share-links d-flex align-items-center gap-2">
                                <span class="smaller fw-black text-muted text-uppercase me-2">{{ __('messages.share') ?? 'Share' }}:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;"><i class="fab fa-facebook-f text-primary"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $article->name }}" target="_blank" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;"><i class="fab fa-twitter text-info"></i></a>
                                <a href="whatsapp://send?text={{ url()->current() }}" target="_blank" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;"><i class="fab fa-whatsapp text-success"></i></a>
                            </div>
                            <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold border shadow-sm" onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('{{ __('messages.link_copied') }}')">
                                <i class="fa fa-copy me-1 text-muted"></i> {{ __('messages.copy_link') }}
                            </button>
                        </div>
                    </footer>
                </div>
            </article>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .hover-bg-dark:hover { background-color: #212529 !important; }
    .hover-text-white:hover { color: #fff !important; }
    .breadcrumb-item + .breadcrumb-item::before { color: #dee2e6; }
    
    .markdown-content h2 { font-weight: 800; color: #1a1a1a; margin-top: 2.5rem; margin-bottom: 1.25rem; }
    .markdown-content h3 { font-weight: 800; color: #333; margin-top: 2rem; margin-bottom: 1rem; }
    .markdown-content p { margin-bottom: 1.5rem; }
    .markdown-content img { max-width: 100%; height: auto; border-radius: 1rem; margin: 2rem 0; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.05); }
    .markdown-content blockquote { padding: 1.5rem 2rem; background-color: #f8f9fa; border-left: 5px solid #615dfa; border-radius: 0.5rem; font-style: italic; margin: 2rem 0; }
    [dir="rtl"] .markdown-content blockquote { border-left: 0; border-right: 5px solid #615dfa; }
    .markdown-content pre { background-color: #1e1f33; color: #fff; padding: 1.5rem; border-radius: 1rem; margin: 2rem 0; overflow-x: auto; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const renderer = new marked.Renderer();
        renderer.image = function(href, title, text) {
            return `<img src="${href}" alt="${text}" title="${title || ''}" loading="lazy" class="img-fluid rounded-4 shadow-sm my-4">`;
        };
        marked.setOptions({ renderer: renderer });

        document.querySelectorAll('.markdown-content').forEach(el => {
            if (!el.getAttribute('data-rendered')) {
                try {
                    const rawContent = el.innerHTML;
                    const content = el.innerText || rawContent;
                    el.innerHTML = DOMPurify.sanitize(marked.parse(content));
                    el.setAttribute('data-rendered', 'true');
                } catch (e) {
                    console.error('Error rendering markdown:', e);
                }
            }
        });
    });
</script>
@endpush
