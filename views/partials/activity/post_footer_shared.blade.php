@php
    $repostExcerpt = $repostExcerpt ?? '';
    $repostAuthorName = $repostAuthorName ?? '';
    $detailUrl = $detailUrl ?? ($activity->detail_url ?? route('forum.topic', $activity->tp_id));
    $commentType = $commentType ?? 'forum';
    $reactionType = $reactionType ?? 2;
    $reactionCategory = $reactionCategory ?? 'forum';
    $targetId = $targetId ?? $activity->related_content->id;
@endphp

<!-- CONTENT ACTIONS -->
<div class="d-flex justify-content-between align-items-center py-2 border-top border-bottom my-3">
    <div class="d-flex gap-3">
        <div class="d-flex align-items-center text-muted small fw-bold">
            @include('theme::partials.activity.reaction-list', ['activity' => $activity])
            <span class="ms-1">{{ $activity->reactions_count }}</span>
        </div>
        <div class="d-flex align-items-center text-muted small fw-bold">
            <a href="{{ $detailUrl }}" class="text-decoration-none text-muted">
                <i class="fa fa-comment me-1"></i> {{ $activity->comments_count }}
            </a>
        </div>
        <div class="d-flex align-items-center text-muted small fw-bold">
            <a href="{{ $detailUrl }}" class="text-decoration-none text-muted">
                <i class="fa fa-share-alt me-1"></i> {{ $activity->reposts_count }}
            </a>
        </div>
    </div>
</div>

<!-- POST OPTIONS -->
<div class="d-flex justify-content-around align-items-center py-1">
    @auth
        <!-- React Trigger -->
        <div class="dropdown">
            <button class="btn btn-link text-decoration-none text-muted fw-bold d-flex align-items-center gap-1 dropdown-toggle py-1 px-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="reaction-btn-{{ $targetId }}">
                @php
                    $myReaction = \App\Models\Like::where('uid', auth()->id())
                        ->where('sid', $targetId)
                        ->where('type', $reactionType)
                        ->first();
                    $myReactionOption = $myReaction ? \App\Models\Option::where('o_parent', $myReaction->id)->where('o_type', 'data_reaction')->first() : null;
                @endphp
                @if($myReactionOption)
                    <img class="reaction-option-image" src="{{ theme_asset('img/reaction/'.$myReactionOption->o_valuer.'.png') }}" width="24" height="24" alt="reaction-{{ $myReactionOption->o_valuer }}">
                @else
                    <i class="fa-regular fa-thumbs-up"></i>
                    <span>{{ __('messages.react') }}</span>
                @endif
            </button>
            <ul class="dropdown-menu shadow border-0 p-2" aria-labelledby="reaction-btn-{{ $targetId }}">
                <li class="d-flex gap-1 justify-content-between">
                    @foreach(['like', 'love', 'dislike', 'happy', 'funny', 'wow', 'angry', 'sad'] as $reaction)
                        <button class="btn btn-link p-1 border-0 bg-transparent text-decoration-none" onclick="toggleReaction({{ $targetId }}, '{{ $reactionCategory }}', '{{ $reaction }}')" title="{{ $reaction }}">
                            <img src="{{ theme_asset('img/reaction/'.$reaction.'.png') }}" width="24" height="24" alt="reaction-{{ $reaction }}">
                        </button>
                    @endforeach
                </li>
            </ul>
        </div>
        
        <!-- Comment Trigger -->
        <button class="btn btn-link text-decoration-none text-muted fw-bold d-flex align-items-center gap-1 py-1 px-2 border-0 bg-transparent" onclick="loadComments({{ $targetId }}, '{{ $commentType }}')">
            <i class="fa-regular fa-comment"></i>
            <span>{{ __('messages.comment') }}</span>
        </button>
    @endauth

    <!-- Share Trigger -->
    <div class="dropdown">
        <button class="btn btn-link text-decoration-none text-muted fw-bold d-flex align-items-center gap-1 dropdown-toggle py-1 px-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-share-nodes"></i>
            <span>{{ __('messages.share') }}</span>
        </button>
        <ul class="dropdown-menu shadow border-0 p-2">
            <li class="d-flex gap-2 align-items-center">
                @foreach(['facebook', 'twitter', 'linkedin', 'telegram'] as $social)
                    <button class="btn btn-link p-1 border-0 bg-transparent text-decoration-none" onclick="sharePost('{{ $social }}', '{{ $detailUrl }}', '{{ $activity->related_content->name ?? ($activity->related_content->txt ?? '') }}')" title="{{ $social }}">
                        <img src="{{ theme_asset('img/icons/'.$social.'-icon.png') }}" width="24" height="24">
                    </button>
                @endforeach
                @auth
                    @if((int) ($activity->group_id ?? 0) === 0)
                        <button class="btn btn-link p-1 border-0 bg-transparent text-decoration-none text-primary" onclick="openRepostComposer({{ $activity->id }}, '{{ addslashes($repostAuthorName) }}', '{{ addslashes($repostExcerpt) }}')" title="{{ __('messages.quote_repost') }}">
                            <span class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                <i class="fa fa-retweet" style="font-size: 12px;"></i>
                            </span>
                        </button>
                    @endif
                @endauth
            </li>
        </ul>
    </div>
</div>

<div class="post-comment-list post-comment-list-{{ $targetId }} mt-2"></div>
