@extends('theme::layouts.master')

@section('content')
<style>
    .saved-clips-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }
    
    .saved-reel-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    [data-theme="css_d"] .saved-reel-card {
        background: #151515;
        border-color: rgba(255,255,255,0.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    
    .saved-reel-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    }
    
    [data-theme="css_d"] .saved-reel-card:hover {
        box-shadow: 0 12px 25px rgba(0,0,0,0.4);
    }
    
    .saved-reel-link {
        position: relative;
        display: block;
        padding-top: 177%; /* 16:9 ratio */
        overflow: hidden;
        background: #000;
        border-radius: 16px 16px 0 0;
    }
    
    .saved-reel-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .saved-reel-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.2) 60%, rgba(0,0,0,0.4) 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .saved-reel-link:hover .saved-reel-overlay {
        opacity: 1;
    }
    
    .saved-reel-overlay .icon-play-btn {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 15px;
        border-radius: 50%;
        color: #fff;
        transform: scale(0.8);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .saved-reel-link:hover .saved-reel-overlay .icon-play-btn {
        transform: scale(1);
    }
    
    .saved-reel-stats {
        position: absolute;
        bottom: 15px;
        left: 15px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    }
    
    .saved-reel-info {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .saved-reel-caption {
        font-size: 14px;
        color: #333;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-weight: 600;
        line-height: 1.4;
    }
    
    [data-theme="css_d"] .saved-reel-caption {
        color: #e0e0e0;
    }
    
    .saved-reel-author {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #666;
        font-size: 13px;
        font-weight: 700;
        transition: color 0.2s;
    }
    
    [data-theme="css_d"] .saved-reel-author {
        color: #aaa;
    }
    
    .saved-reel-author:hover {
        color: #23d2e2;
    }
    
    .saved-reel-author img {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid rgba(0,0,0,0.05);
    }
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="#fff"><path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.saved_clips') ?? 'Saved Clips' }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.saved_clips_desc') ?? 'Clips you have saved from the community feed.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-bookmark fa-10x"></i>
        </div>
    </div>

    @if($activities->isEmpty())
        <div class="p-5 text-center bg-white shadow-sm rounded-4 border border-light">
            <div class="rounded-circle bg-light p-4 d-inline-flex mb-4">
                <svg style="width: 48px; height: 48px; fill: #6c757d; opacity: 0.5;" viewBox="0 0 24 24">
                    <path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
                </svg>
            </div>
            <h3 class="fw-black text-dark">{{ __('messages.no_saved_clips') ?? 'No Saved Clips' }}</h3>
            <p class="text-muted small mb-4 fw-bold">{{ __('messages.no_saved_clips_desc') ?? 'You haven\'t saved any clips yet. Explore the community and save your favorites!' }}</p>
            <a href="{{ route('clips.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">{{ __('messages.explore_clips') ?? 'Explore Clips' }}</a>
        </div>
    @else
        <div class="saved-clips-container" id="saved-clips-container">
            @include('theme::clips.partials.clips_grid', ['activities' => $activities])
        </div>
        
        <div id="clips-loading" style="display: none; text-align: center; padding: 30px;" class="text-muted small fw-bold">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            {{ __('messages.loading') ?? 'Loading...' }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if($activities->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('saved-clips-container');
    const loadingEl = document.getElementById('clips-loading');
    let nextPageUrl = '{{ $activities->nextPageUrl() }}';
    let isLoading = false;

    const sentinelObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && nextPageUrl && !isLoading) {
                loadMoreClips();
            }
        });
    }, {
        root: null,
        threshold: 0.1
    });

    function observeSentinel() {
        const items = document.querySelectorAll('.saved-reel-card');
        if (items.length > 0) {
            sentinelObserver.disconnect();
            sentinelObserver.observe(items[items.length - 1]);
        }
    }
    
    observeSentinel();

    function loadMoreClips() {
        if (!nextPageUrl) return;
        
        isLoading = true;
        loadingEl.style.display = 'block';

        fetch(nextPageUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                container.insertAdjacentHTML('beforeend', data.html);
                nextPageUrl = data.next_page_url;
                observeSentinel();
            } else {
                nextPageUrl = null;
            }
        })
        .catch(err => console.error(err))
        .finally(() => {
            isLoading = false;
            loadingEl.style.display = 'none';
        });
    }
});
</script>
@endif
@endpush

