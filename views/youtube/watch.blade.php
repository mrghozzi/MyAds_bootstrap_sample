@extends('theme::layouts.master')

@section('title', __('Watch Video'))

@section('content')
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%; background-size: cover;">
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}" alt="overview-icon">
    <p class="section-banner-title">{{ __('Watch Video') }}</p>
    <p class="section-banner-text">{{ __('Watch & Earn') }}</p>
</div>

<div class="d-flex flex-wrap gap-2 mt-4 mb-3">
    <a href="{{ route('youtube.exchange.index') }}" class="btn btn-outline-secondary rounded-pill fw-bold px-4" id="back-btn"><i class="fa fa-arrow-left me-2"></i> {{ __('Return to Exchange') }}</a>
</div>

<!-- Error or Success Alert Container -->
<div id="alert-container"></div>

<div class="card border-0 shadow-sm rounded-4 mb-4 text-center">
    <div class="card-body p-4 p-md-5">
        <h4 class="fw-bold mb-4 text-dark">
            {{ __('Watch & Earn') }} 
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold align-middle ms-2">
                {{ $video->reward_points }} PTS
            </span>
        </h4>

        <div class="rounded-4 overflow-hidden bg-dark position-relative mx-auto mb-4 shadow-sm" style="padding-top: 56.25%; width: 100%; max-width: 800px;">
            <div id="youtube-player" class="position-absolute top-0 start-0 w-100 h-100"></div>
        </div>

        <!-- Timer and Control UI -->
        <div class="mx-auto" style="max-width: 600px;">
            <div id="status-message" class="text-muted mb-3 fs-5 fw-bold">
                {{ __('Click play to start the timer.') }}
            </div>
            
            <div class="bg-light rounded-pill overflow-hidden mb-4 position-relative border" style="height: 24px;">
                <div id="progress-bar" class="bg-primary h-100" style="width: 0%; transition: width 1s linear;"></div>
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold small text-shadow-sm" style="pointer-events: none;">
                    <span id="timer-text" class="me-1">{{ $video->duration_required }}</span>s
                </div>
            </div>

            <button id="claim-btn" class="btn btn-primary btn-lg rounded-pill fw-bold w-100 mx-auto shadow-sm" style="max-width: 300px; display: none;" onclick="claimReward()">
                <i class="fas fa-gift me-2"></i> {{ __('Claim Reward') }}
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const requiredDuration = {{ $video->duration_required }};
    const verificationToken = '{{ $token }}';
    const verifyUrl = '{{ route('youtube.exchange.verify') }}';
    const csrfToken = '{{ csrf_token() }}';
    
    let player;
    let timerInterval;
    let timeWatched = 0;
    let isCompleted = false;

    // Load YouTube IFrame API
    let tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    let firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube-player', {
            videoId: '{{ $video->youtube_id }}',
            playerVars: {
                'controls': 0,
                'disablekb': 1,
                'fs': 0,
                'rel': 0,
                'modestbranding': 1,
                'playsinline': 1,
                'iv_load_policy': 3
            },
            events: {
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerStateChange(event) {
        if (isCompleted) return;

        if (event.data == YT.PlayerState.PLAYING) {
            startTimer();
            document.getElementById('status-message').innerText = "{{ __('Watching...') }}";
            document.getElementById('status-message').style.color = '#615dfa';
        } else {
            stopTimer();
            if (event.data == YT.PlayerState.PAUSED) {
                document.getElementById('status-message').innerText = "{{ __('messages.paused') }}";
                document.getElementById('status-message').style.color = '#ffb800';
            }
        }
    }

    function startTimer() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            timeWatched++;
            updateUI();

            if (timeWatched >= requiredDuration) {
                completeView();
            }
        }, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
    }

    function updateUI() {
        let remaining = requiredDuration - timeWatched;
        if (remaining < 0) remaining = 0;
        
        let percent = (timeWatched / requiredDuration) * 100;
        document.getElementById('progress-bar').style.width = percent + '%';
        document.getElementById('timer-text').innerText = remaining;
    }

    function completeView() {
        isCompleted = true;
        stopTimer();
        player.pauseVideo();
        
        document.getElementById('status-message').innerText = "{{ __('Requirement met! You can now claim your reward.') }}";
        document.getElementById('status-message').style.color = '#10b981';
        
        document.getElementById('progress-bar').style.background = '#10b981';
        
        document.getElementById('claim-btn').style.display = 'block';
    }

    // Page Visibility API to prevent background watching
    document.addEventListener("visibilitychange", () => {
        if (document.hidden && player && typeof player.pauseVideo === 'function' && !isCompleted) {
            player.pauseVideo();
            stopTimer();
        }
    });

    setInterval(() => {
        if (!isCompleted && player && player.getPlayerState() === YT.PlayerState.PLAYING) {
            // Basic check placeholder
        }
    }, 2000);

    function claimReward() {
        let btn = document.getElementById('claim-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> {{ __('Verifying...') }}';

        fetch(verifyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ token: verificationToken })
        })
        .then(response => response.json())
        .then(data => {
            let container = document.getElementById('alert-container');
            if (data.success) {
                container.innerHTML = `<div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm fw-bold"><i class="fas fa-check-circle me-2"></i> ${data.message}</div>`;
                btn.style.display = 'none';
            } else {
                container.innerHTML = `<div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm fw-bold"><i class="fas fa-exclamation-circle me-2"></i> ${data.message}</div>`;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-gift me-2"></i> {{ __('Claim Reward') }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('alert-container').innerHTML = `<div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm fw-bold"><i class="fas fa-exclamation-circle me-2"></i> {{ __('Network error occurred.') }}</div>`;
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-gift me-2"></i> {{ __('Claim Reward') }}';
        });
    }
</script>
@endpush
