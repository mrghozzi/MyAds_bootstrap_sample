@php
    $activityUser = $activity->user;
    $activityUserProfileUrl = $activityUser ? route('profile.show', $activityUser->username) : '#';
    $activityUserName = $activityUser?->username ?? __('messages.unknown_user');
    $activityUserAvatar = $activityUser ? $activityUser->avatarUrl() : asset('upload/_avatar.png');
    $activityUserPresence = $activityUser?->isOnline() ? 'online' : 'offline';
    $activityUserIsAdmin = $activityUser?->isAdmin() ?? false;
    $activityUserHasVerifiedBadge = $activityUser?->hasVerifiedBadge() ?? false;
    $relatedId = $activity->related_content?->id ?? $activity->tp_id ?? $activity->id;
    $formattedText = \App\Support\ContentFormatter::format($activity->related_content?->txt ?? ($activity->txt ?? ''));
    
    $poll = $activity->poll ?? \App\Models\Poll::where('status_id', $activity->id)->with('options')->first();
    $currentUserId = auth()->id();
    $hasVoted = $poll ? $poll->hasUserVoted($currentUserId) : false;
    $votedOptionId = $poll ? $poll->getUserVotedOptionId($currentUserId) : null;
    $isExpired = $poll ? $poll->is_expired : false;
    $totalVotes = $poll ? $poll->total_votes : 0;
    
    $repostExcerpt = \Illuminate\Support\Str::limit(strip_tags($poll?->question ?? $formattedText), 80);
    $repostAuthorName = addslashes($activityUserName);
@endphp

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 post{{ $activity->id }} activity-post-card" data-poll-id="{{ $poll?->id }}">
    <!-- Header -->
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ $activityUserProfileUrl }}" class="me-3 position-relative">
                <img src="{{ $activityUserAvatar }}" class="rounded-circle border border-2 border-white shadow-sm" width="48" height="48" alt="{{ $activityUserName }}">
                @if($activityUserPresence == 'online')
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                @endif
            </a>
            <div>
                <h6 class="fw-bold mb-0">
                    <a href="{{ $activityUserProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $activityUserName }}</a>
                    @if($activity->is_pinned)
                        <span class="badge ms-2" style="background-color: var(--bs-primary); color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;"><i class="fa fa-thumb-tack"></i> {{ __('messages.pinned_post') ?? 'Pinned Post' }}</span>
                    @endif
                    @if($activityUserHasVerifiedBadge)
                        <svg class="verified-icon ms-1" viewBox="0 0 24 24" width="14" height="14" fill="#23d2e2" style="vertical-align: middle;"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1.9 14.7L6 12.6l1.5-1.5 2.6 2.6 6.4-6.4 1.5 1.5-7.9 7.9z"/></svg>
                    @endif
                </h6>
                <small class="text-muted smaller fw-bold"><i class="fa-regular fa-clock me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($activity->date)->diffForHumans() }}</small>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                @auth
                    @if(auth()->id() == $activity->uid)
                        <li>
                            <button class="dropdown-item py-2" onclick="togglePinPost({{ $activity->id }}, {{ $activity->is_pinned ? 'true' : 'false' }}, {{ isset($hasPinnedPost) && $hasPinnedPost ? 'true' : 'false' }})">
                                <i class="fa fa-thumb-tack me-2"></i> {{ $activity->is_pinned ? __('messages.unpin_post') : __('messages.pin_post') }}
                            </button>
                        </li>
                    @endif
                    @if(auth()->id() == $activity->uid || auth()->user()->isAdmin())
                        <li><button class="dropdown-item py-2 text-danger" onclick="deletePost({{ $activity->tp_id }}, 100, '.post{{ $activity->id }}')"><i class="fa-regular fa-trash-can me-2"></i> {{ __('messages.delete') }}</button></li>
                    @endif
                    @include('theme::partials.activity.promotion_link', ['activity' => $activity])
                    <li><button class="dropdown-item py-2" onclick="reportPost({{ $activity->tp_id }}, 100, {{ $relatedId }})"><i class="fa-regular fa-flag me-2"></i> {{ __('messages.report') }}</button></li>
                    <li><button class="dropdown-item py-2" onclick="reportUser({{ $activity->uid }}, {{ $relatedId }})"><i class="fa-regular fa-user me-2"></i> {{ __('messages.report_author') }}</button></li>
                @endauth
                <li><button class="dropdown-item py-2" onclick="navigator.clipboard.writeText('{{ route('forum.topic', $activity->tp_id) }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa-regular fa-copy me-2"></i> {{ __('messages.copy_link') }}</button></li>
            </ul>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body p-4">
        @include('theme::partials.activity.promotion_badge', ['activity' => $activity])

        @if(trim(strip_tags($formattedText)) !== '' && (!isset($poll) || $formattedText !== ($poll->question ?? '')))
            <div class="post-content post_text{{ $relatedId }} mb-3">
                <div class="textpost mb-3 lh-lg" id="post_form{{ $relatedId }}">
                    {!! $formattedText !!}
                </div>
            </div>
        @endif

        @if($poll)
            <div class="community-poll-card border rounded-4 p-3 p-md-4 mb-3" id="poll-box-{{ $poll->id }}" style="background: rgba(97, 93, 250, 0.02);">
                <!-- Poll Status Header -->
                <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">
                            <i class="fa fa-poll me-1"></i> {{ __('messages.poll') ?? 'استطلاع رأي' }}
                        </span>
                        @if($isExpired)
                            <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                <i class="fa fa-lock me-1"></i> {{ __('messages.poll_ended') ?? 'منتهي' }}
                            </span>
                        @else
                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                <i class="fa fa-circle me-1" style="font-size: 8px;"></i> {{ __('messages.poll_active') ?? 'نشط' }}
                            </span>
                        @endif
                    </div>

                    <div class="small text-muted d-flex align-items-center gap-2">
                        <span id="poll-total-votes-{{ $poll->id }}" class="fw-bold">
                            {{ $totalVotes }} {{ __('messages.votes') ?? 'صوت' }}
                        </span>
                        @if($poll->expires_at && !$isExpired)
                            <span>&bull;</span>
                            <span title="{{ $poll->expires_at->toDateTimeString() }}">
                                <i class="fa fa-hourglass-half"></i> {{ $poll->expires_at->diffForHumans() }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Poll Question -->
                <h5 class="fw-bold mb-3 lh-base text-dark">
                    {{ $poll->question }}
                </h5>

                <!-- Poll Options -->
                <div class="poll-options-wrapper d-flex flex-column gap-2" id="poll-options-{{ $poll->id }}">
                    @foreach($poll->options as $opt)
                        @php
                            $percent = $opt->percentage;
                            $isUserVote = ($votedOptionId === $opt->id);
                            $canVote = auth()->check() && !$hasVoted && !$isExpired;
                        @endphp

                        <div class="poll-option-item position-relative overflow-hidden border rounded-3 bg-white p-3 {{ $isUserVote ? 'border-primary shadow-sm' : '' }}" 
                             style="cursor: {{ $canVote ? 'pointer' : 'default' }}; transition: all 0.2s ease;"
                             @if($canVote) onclick="submitPollVote({{ $poll->id }}, {{ $opt->id }})" @endif>
                            
                            <!-- Background Progress Fill -->
                            <div class="position-absolute top-0 bottom-0 start-0" style="
                                width: {{ ($hasVoted || $isExpired) ? $percent : 0 }}%;
                                background: {{ $isUserVote ? 'rgba(97, 93, 250, 0.18)' : 'rgba(35, 210, 226, 0.12)' }};
                                transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
                                z-index: 1;
                                pointer-events: none;
                            "></div>

                            <!-- Option Content -->
                            <div class="d-flex align-items-center justify-content-between position-relative" style="z-index: 2;">
                                <div class="d-flex align-items-center gap-2">
                                    @if($canVote)
                                        <div style="width: 18px; height: 18px; border: 2px solid #adb5bd; border-radius: 50%;"></div>
                                    @elseif($isUserVote)
                                        <i class="fa fa-check-circle text-primary fs-5"></i>
                                    @endif
                                    <span class="fw-bold text-dark">{{ $opt->option_text }}</span>
                                </div>

                                @if($hasVoted || $isExpired)
                                    <div class="d-flex align-items-center gap-2 text-nowrap">
                                        <span class="small text-muted">{{ $opt->votes_count }} {{ __('messages.votes') ?? 'صوت' }}</span>
                                        <span class="fw-bold text-primary">{{ $percent }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @guest
                    <div class="text-center mt-3 pt-2 border-top">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            <i class="fa fa-sign-in-alt me-1"></i> {{ __('messages.login_to_vote') ?? 'سجل دخولك للمشاركة في التصويت' }}
                        </a>
                    </div>
                @endguest
            </div>
        @endif

        <div id="notif{{ $relatedId }}"></div>
    </div>

    <!-- Footer -->
    <div class="card-footer bg-white border-top py-3">
        @include('theme::partials.activity.post_footer_shared', ['activity' => $activity, 'repostAuthorName' => $repostAuthorName, 'repostExcerpt' => $repostExcerpt])
    </div>
</div>

@once
@push('scripts')
<script>
window.submitPollVote = function(pollId, optionId) {
    const pollBox = document.getElementById('poll-box-' + pollId);
    if (!pollBox) return;

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) return;

    pollBox.style.opacity = '0.6';
    pollBox.style.pointerEvents = 'none';

    fetch('/polls/' + pollId + '/vote', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ poll_option_id: optionId })
    })
    .then(res => res.json())
    .then(data => {
        pollBox.style.opacity = '1';
        pollBox.style.pointerEvents = 'auto';

        if (data.error) {
            alert(data.error);
            return;
        }

        if (data.success && data.options) {
            const totalEl = document.getElementById('poll-total-votes-' + pollId);
            if (totalEl) {
                totalEl.textContent = data.total_votes + ' ' + (@json(__('messages.votes') ?? 'صوت'));
            }

            const optionsContainer = document.getElementById('poll-options-' + pollId);
            if (optionsContainer) {
                let html = '';
                data.options.forEach(opt => {
                    const isSelected = (opt.id === data.user_voted_option_id);
                    html += `
                        <div class="poll-option-item position-relative overflow-hidden border rounded-3 bg-white p-3 ${isSelected ? 'border-primary shadow-sm' : ''}" style="cursor: default; transition: all 0.2s ease;">
                            <div class="position-absolute top-0 bottom-0 start-0" style="
                                width: ${opt.percentage}%;
                                background: ${isSelected ? 'rgba(97, 93, 250, 0.18)' : 'rgba(35, 210, 226, 0.12)'};
                                transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
                                z-index: 1;
                                pointer-events: none;
                            "></div>
                            <div class="d-flex align-items-center justify-content-between position-relative" style="z-index: 2;">
                                <div class="d-flex align-items-center gap-2">
                                    ${isSelected ? '<i class="fa fa-check-circle text-primary fs-5"></i>' : ''}
                                    <span class="fw-bold text-dark">${opt.option_text}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 text-nowrap">
                                    <span class="small text-muted">${opt.votes_count} ${@json(__('messages.votes') ?? 'صوت')}</span>
                                    <span class="fw-bold text-primary">${opt.percentage}%</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                optionsContainer.innerHTML = html;
            }
        }
    })
    .catch(err => {
        pollBox.style.opacity = '1';
        pollBox.style.pointerEvents = 'auto';
        console.error('Poll vote error:', err);
    });
};
</script>
@endpush
@endonce

