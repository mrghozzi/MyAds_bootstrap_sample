@extends('theme::layouts.master')

@section('content')
<style>
    /* Full Clips TikTok-style Container */
    .clips-wrapper {
        height: calc(100vh - 100px);
        background-color: #050505 !important;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        max-width: 450px;
        margin: 0 auto;
        box-shadow: 0 15px 35px rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .clips-container {
        height: 100%;
        overflow-y: scroll;
        scroll-snap-type: y mandatory;
        scroll-behavior: smooth;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        background-color: #050505 !important;
    }
    
    .clips-container::-webkit-scrollbar {
        display: none;
    }
    
    .reel-item {
        height: 100%;
        width: 100%;
        scroll-snap-align: start;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #050505 !important;
    }
    
    .reel-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        background-color: #050505 !important;
    }
    
    .reel-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none; /* Let clicks pass to video */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 30%, rgba(0,0,0,0.2) 70%, rgba(0,0,0,0.5) 100%);
    }
    
    /* Play/Pause indicator fades out */
    .reel-play-indicator {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
        background: rgba(0, 0, 0, 0.5);
        padding: 15px;
        border-radius: 50%;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .reel-item.is-paused .reel-play-indicator {
        opacity: 1;
    }
    
    .reel-info {
        position: absolute;
        left: 20px;
        bottom: 25px;
        color: #fff;
        max-width: calc(100% - 90px);
        pointer-events: auto;
        text-shadow: 0 2px 4px rgba(0,0,0,0.8);
        z-index: 10;
    }
    
    .reel-user {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        color: #fff;
        text-decoration: none;
        transition: opacity 0.2s;
    }
    
    .reel-user:hover {
        color: #fff;
        opacity: 0.9;
    }
    
    .reel-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid #23d2e2;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(35,210,226,0.3);
    }
    
    .reel-username {
        font-weight: 800;
        font-size: 16px;
        letter-spacing: 0.5px;
    }
    
    .reel-caption {
        font-size: 14px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        opacity: 0.95;
    }
    
    .reel-caption a {
        color: #23d2e2 !important;
        text-decoration: none;
        font-weight: bold;
    }
    
    .reel-actions {
        position: absolute;
        right: 15px;
        bottom: 25px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        pointer-events: auto;
        z-index: 10;
    }
    
    .reel-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #fff;
        cursor: pointer;
        background: rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.08);
        padding: 10px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        justify-content: center;
        backdrop-filter: blur(5px);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .reel-action-btn:hover {
        transform: scale(1.15);
        background: rgba(0,0,0,0.6);
        border-color: rgba(255,255,255,0.2);
    }
    
    .reel-action-btn .icon {
        width: 24px;
        height: 24px;
        fill: #fff;
        transition: fill 0.3s, transform 0.3s;
    }
    
    .reel-action-label {
        font-size: 11px;
        font-weight: 700;
        margin-top: 4px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.8);
        color: rgba(255, 255, 255, 0.8);
        pointer-events: none;
    }
    
    .reel-action-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .reel-action-btn.active.toggle-reaction {
        background: rgba(255, 51, 102, 0.15);
        border-color: rgba(255, 51, 102, 0.3);
    }
    
    .reel-action-btn.active.toggle-reaction .icon {
        fill: #ff3366;
        animation: heartBeat 0.5s ease-in-out;
    }
    
    .reel-action-btn.active.toggle-save {
        background: rgba(35, 210, 226, 0.15);
        border-color: rgba(35, 210, 226, 0.3);
    }
    
    .reel-action-btn.active.toggle-save .icon {
        fill: #23d2e2;
    }
    
    @keyframes heartBeat {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
    
    /* Floating Saved Clips Button */
    .clips-header-actions {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 20;
    }
    
    .btn-saved-clips {
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(8px);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 30px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        letter-spacing: 0.5px;
        text-uppercase: uppercase;
    }
    
    .btn-saved-clips:hover {
        background: #fff;
        color: #000;
        border-color: #fff;
        transform: translateY(-2px);
    }
    
    .btn-saved-clips svg {
        width: 14px;
        height: 14px;
        fill: currentColor;
    }
    
    /* Mute and Progress */
    .reel-mute-toggle {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 100;
        width: 40px;
        height: 40px;
        background: rgba(0,0,0,0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.15);
        transition: all 0.2s;
    }
    
    .reel-mute-toggle:hover {
        background: rgba(0,0,0,0.7);
        transform: scale(1.05);
    }
    
    .reel-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: rgba(255, 255, 255, 0.15);
        z-index: 100;
    }
    
    .reel-progress-filled {
        height: 100%;
        width: 0%;
        background: linear-gradient(to right, #23d2e2, #23d2e2);
        transition: width 0.1s linear;
    }
</style>

<div class="container py-4">
    <div class="clips-wrapper">
        <!-- Floating Actions -->
        <div class="clips-header-actions">
            <a href="{{ route('clips.saved') }}" class="btn-saved-clips">
                <svg viewBox="0 0 24 24"><path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg>
                {{ __('messages.saved') ?? 'Saved' }}
            </a>
        </div>
        
        <!-- Video Container -->
        <div class="clips-container" id="clips-container">
            @include('theme::clips.partials.clips_list', ['activities' => $activities])
        </div>
        
        <!-- Loading Indicator for Infinite Scroll -->
        <div id="clips-loading" style="display: none; position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); text-align: center; color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.8); z-index: 200;">
            <div class="spinner-border spinner-border-sm text-light me-2" role="status"></div>
            <span class="small fw-bold">{{ __('messages.loading') ?? 'Loading...' }}</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('clips-container');
    const loadingEl = document.getElementById('clips-loading');
    let nextPageUrl = '{{ $activities->nextPageUrl() }}';
    let isLoading = false;

    // Intersection Observer for playing/pausing videos
    const videoObserverOptions = {
        root: container,
        rootMargin: '0px',
        threshold: 0.6 // 60% of the video must be visible
    };

    const videoObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const video = entry.target.querySelector('.reel-video');
            const item = entry.target;
            
            if (!video) return;

            if (entry.isIntersecting) {
                video.play().then(() => {
                    item.classList.remove('is-paused');
                }).catch(e => {
                    // Autoplay prevented, show paused state
                    item.classList.add('is-paused');
                    console.log('Autoplay prevented:', e);
                });
            } else {
                video.pause();
                item.classList.add('is-paused');
            }
        });
    }, videoObserverOptions);

    function observeVideos() {
        document.querySelectorAll('.reel-item').forEach(item => {
            // Unobserve first to prevent duplicates
            videoObserver.unobserve(item);
            videoObserver.observe(item);
            
            const video = item.querySelector('.reel-video');
            if(video && !item.dataset.boundEvents) {
                item.dataset.boundEvents = 'true';
                
                // Add click to play/pause
                video.addEventListener('click', () => {
                    if (video.paused) {
                        video.play();
                        item.classList.remove('is-paused');
                    } else {
                        video.pause();
                        item.classList.add('is-paused');
                    }
                });

                // Progress Bar Update
                const progressBar = item.querySelector('.reel-progress-filled');
                video.addEventListener('timeupdate', () => {
                    if (video.duration) {
                        const percent = (video.currentTime / video.duration) * 100;
                        progressBar.style.width = percent + '%';
                    }
                });

                // Mute Toggle Logic
                const muteBtn = item.querySelector('.reel-mute-toggle');
                if (muteBtn) {
                    muteBtn.addEventListener('click', (e) => {
                        e.stopPropagation(); // Prevent play/pause toggle
                        
                        // Toggle global mute state for all clips
                        const isMuted = !video.muted;
                        
                        document.querySelectorAll('.reel-video').forEach(v => {
                            v.muted = isMuted;
                        });

                        document.querySelectorAll('.reel-item').forEach(el => {
                            const iconMute = el.querySelector('.icon-mute');
                            const iconUnmute = el.querySelector('.icon-unmute');
                            if (isMuted) {
                                iconMute.style.display = 'block';
                                iconUnmute.style.display = 'none';
                            } else {
                                iconMute.style.display = 'none';
                                iconUnmute.style.display = 'block';
                            }
                        });
                    });
                }
            }
        });
    }

    observeVideos();

    // Infinite Scroll Observer
    const sentinelObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && nextPageUrl && !isLoading) {
                loadMoreClips();
            }
        });
    }, {
        root: container,
        threshold: 0.1
    });

    function observeSentinel() {
        const items = document.querySelectorAll('.reel-item');
        if (items.length > 0) {
            // Observe the last item
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
                observeVideos();
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

    // Toggle Reaction Action
    container.addEventListener('click', function(e) {
        const btn = e.target.closest('.toggle-reaction');
        if (!btn) return;
        
        const sid = btn.dataset.id;
        const type = btn.dataset.type;
        const countSpan = btn.querySelector('.reaction-count');
        let currentCount = parseInt(countSpan.textContent) || 0;
        
        const isActive = btn.classList.contains('active');
        
        if (isActive) {
            btn.classList.remove('active');
            countSpan.textContent = Math.max(0, currentCount - 1);
        } else {
            btn.classList.add('active');
            countSpan.textContent = currentCount + 1;
        }

        fetch('{{ route('reaction.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: sid,
                type: type,
                reaction: 'like'
            })
        }).catch(err => console.error(err));
    });

    // Toggle Save Action
    container.addEventListener('click', function(e) {
        const btn = e.target.closest('.toggle-save');
        if (!btn) return;
        
        const sid = btn.dataset.id;
        const countSpan = btn.querySelector('.save-count');
        let currentCount = parseInt(countSpan.textContent) || 0;
        
        const isActive = btn.classList.contains('active');
        
        if (isActive) {
            btn.classList.remove('active');
            countSpan.textContent = Math.max(0, currentCount - 1);
        } else {
            btn.classList.add('active');
            countSpan.textContent = currentCount + 1;
        }

        fetch('{{ url('/api/clips/save') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status_id: sid
            })
        }).catch(err => console.error(err));
    });

    // Share Action
    container.addEventListener('click', function(e) {
        const btn = e.target.closest('.share-reel');
        if (!btn) return;
        
        const url = btn.dataset.url;
        if (navigator.share) {
            navigator.share({
                title: 'MYADS Clips',
                url: url
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(url).then(() => {
                alert('{{ __('messages.link_copied') ?? 'Link copied to clipboard!' }}');
            });
        }
    });
    
    // Comments Action (redirect to post page)
    container.addEventListener('click', function(e) {
        const btn = e.target.closest('.open-comments');
        if (!btn) return;
        
        const sid = btn.dataset.id;
        window.location.href = '{{ url('/status') }}/' + sid;
    });

    // Hash Scroll Handler (if page loaded with target hash)
    if (window.location.hash) {
        const targetId = window.location.hash.substring(1);
        const targetElement = document.querySelector(`.reel-item[data-id="${targetId}"]`);
        if (targetElement) {
            setTimeout(() => {
                targetElement.scrollIntoView({ behavior: 'auto' });
            }, 100);
        }
    }
});
</script>
@endsection

