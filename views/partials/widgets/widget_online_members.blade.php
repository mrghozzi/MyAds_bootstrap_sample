<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @php
                $userId = auth()->id() ?? 0;
                $activeThreshold = time() - 900; // 15 minutes
                
                $onlineUsers = \App\Models\User::where('online', '>', $activeThreshold)
                    ->where('id', '!=', $userId)
                    ->orderBy('online', 'desc')
                    ->limit(5)
                    ->get();
            @endphp

            @if($onlineUsers->isEmpty())
                <p class="text-center text-muted mb-0"><small>{{ __('messages.no_users_online_right_now') ?? 'No users online right now.' }}</small></p>
            @else
                @foreach($onlineUsers as $user)
                    @php
                        $followersCount = \Illuminate\Support\Facades\DB::table('like')->where('sid', $user->id)->where('type', 1)->count();
                        $postsCount = \App\Models\ForumTopic::where('uid', $user->id)->count();
                    @endphp
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('profile.short', $user->publicRouteIdentifier()) }}" class="position-relative">
                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}" class="rounded-circle border" style="width: 48px; height: 48px; object-fit: cover;">
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle">
                                <span class="visually-hidden">Online</span>
                            </span>
                        </a>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <a href="{{ route('profile.short', $user->publicRouteIdentifier()) }}" class="text-dark fw-bold text-decoration-none text-truncate d-block">
                                    {{ Str::limit($user->username, 15) }}
                                    @if($user->hasVerifiedBadge())
                                        <i class="fa fa-check-circle text-primary small ms-1" title="Verified"></i>
                                    @endif
                                </a>
                                <a href="{{ route('messages.show', $user->username) }}" class="btn btn-light btn-sm rounded-circle shadow-sm" title="{{ __('messages.Message') }}">
                                    <i class="fa-regular fa-envelope text-primary"></i>
                                </a>
                            </div>
                            <div class="text-muted smaller fw-bold text-truncate">
                                {{ __('messages.Followers') }} {{ $followersCount }} &bull; {{ __('messages.Posts') }} {{ $postsCount }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
