@php
    $config = [];
    if (!empty($widget?->o_valuer)) {
        if (is_array($widget->o_valuer)) {
            $config = $widget->o_valuer;
        } else {
            $decoded = json_decode((string) $widget->o_valuer, true);
            $config = is_array($decoded) ? $decoded : [];
        }
    }

    $limit = (int) ($config['limit'] ?? 5);
    $orderBy = $config['order_by'] ?? 'random';
    $verifiedOnly = !empty($config['verified_only']);
    $showStats = isset($config['show_stats']) ? (bool) $config['show_stats'] : true;
    $showFollowBtn = isset($config['show_follow_btn']) ? (bool) $config['show_follow_btn'] : true;

    $userId = auth()->id() ?? 0;
    $query = \App\Models\User::query()
        ->whereNotIn('id', function($q) use ($userId) {
            $q->select('sid')->from('like')->where('uid', $userId)->where('type', 1);
        })
        ->where('id', '!=', $userId);

    if ($verifiedOnly) {
        $query->where('verify', 1);
    }

    if ($orderBy === 'popular') {
        $query->orderBy('pts', 'desc');
    } elseif ($orderBy === 'newest') {
        $query->orderBy('id', 'desc');
    } elseif ($orderBy === 'active') {
        $query->orderBy('online', 'desc');
    } else {
        $query->inRandomOrder();
    }

    $users = $query->limit($limit)->get();
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <!-- WIDGET BOX TITLE -->
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <!-- /WIDGET BOX TITLE -->

    <!-- WIDGET BOX CONTENT -->
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @forelse($users as $user)
                @php
                     $followersCount = \Illuminate\Support\Facades\DB::table('like')->where('sid', $user->id)->where('type', 1)->count();
                     $followingCount = \Illuminate\Support\Facades\DB::table('like')->where('uid', $user->id)->where('type', 1)->count();
                     $postsCount = \App\Models\ForumTopic::where('uid', $user->id)->count();
                     $isOnline = $user->isOnline();
                     $userProfileUrl = route('profile.short', $user->publicRouteIdentifier());
                @endphp
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2 min-width-0">
                        <a href="{{ $userProfileUrl }}" class="position-relative flex-shrink-0">
                            <img src="{{ $user->avatarUrl() }}" class="rounded-circle border" width="38" height="38" alt="{{ $user->username }}">
                            @if($isOnline)
                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                            @endif
                        </a>
                        <div class="min-width-0">
                            <h6 class="mb-0 fw-bold small text-truncate">
                                <a href="{{ $userProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $user->username }}</a>
                                @if($user->hasVerifiedBadge())
                                    <svg class="verified-icon ms-1" viewBox="0 0 24 24" width="12" height="12" fill="#23d2e2" style="vertical-align: middle;"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-1.9 14.7L6 12.6l1.5-1.5 2.6 2.6 6.4-6.4 1.5 1.5-7.9 7.9z"/></svg>
                                @endif
                            </h6>
                            @if($showStats)
                                <small class="text-muted smaller d-block text-truncate" style="font-size: 0.75rem;">
                                    {{ $followersCount }} {{ __('messages.Followers') }} &bull; {{ $postsCount }} {{ __('messages.Posts') }}
                                </small>
                            @endif
                        </div>
                    </div>

                    @if($showFollowBtn)
                        @auth
                            <form action="{{ route('profile.follow', $user->username) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 30px; height: 30px;" title="{{ __('messages.follow') ?? 'Follow' }}">
                                    <i class="fa fa-user-plus" style="font-size: 11px;"></i>
                                </button>
                            </form>
                        @endauth
                    @endif
                </div>
            @empty
                <p class="text-center text-muted mb-0"><small>{{ __('messages.no_members_found') ?? 'No members found.' }}</small></p>
            @endforelse
        </div>
    </div>
</div>
