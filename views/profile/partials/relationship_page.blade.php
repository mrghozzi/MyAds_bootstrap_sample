@php
    $relationshipTotal = method_exists($relationshipItems, 'total')
        ? (int) $relationshipItems->total()
        : (int) $relationshipItems->count();
@endphp

<div class="container py-4">
    <!-- Profile Header -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 border border-light transition-all">
        <!-- Cover Image -->
        <div class="profile-cover position-relative" style="height: 250px; background: url({{ asset($cover) }}) no-repeat center; background-size: cover;">
            <div class="position-absolute bottom-0 start-0 w-100 p-4 bg-gradient-dark d-flex align-items-end justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="user-avatar-wrap p-1 bg-white rounded-circle shadow-lg" style="width: 140px; height: 140px; margin-bottom: -70px; position: relative; z-index: 2;">
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}" class="rounded-circle border border-4 border-white w-100 h-100" style="object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 p-3 bg-{{ $user->isOnline() ? 'success' : 'secondary' }} border border-4 border-white rounded-circle shadow-sm" style="width: 24px; height: 24px; margin-right: 10px; margin-bottom: 10px;"></span>
                    </div>
                    <div class="text-white mb-n2 d-none d-md-block">
                        <h1 class="h2 fw-black mb-1 drop-shadow">{{ $user->username }}</h1>
                        <p class="mb-0 opacity-75 fw-bold smaller drop-shadow">{{ __('messages.lastcontact') }} {{ \Carbon\Carbon::createFromTimestamp($user->online)->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4 pt-5 mt-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex flex-wrap gap-4 py-2">
                        <div class="text-center">
                            <h5 class="fw-black mb-0 text-dark">{{ $followersCount ?? 0 }}</h5>
                            <small class="text-muted text-uppercase smaller fw-black letter-spacing-1">{{ __('messages.Followers') }}</small>
                        </div>
                        <div class="text-center border-start border-light-subtle ps-4">
                            <h5 class="fw-black mb-0 text-dark">{{ $followingCount ?? 0 }}</h5>
                            <small class="text-muted text-uppercase smaller fw-black letter-spacing-1">{{ __('messages.following') }}</small>
                        </div>
                        <div class="text-center border-start border-light-subtle ps-4">
                            <h5 class="fw-black mb-0 text-dark">{{ $postsCount ?? 0 }}</h5>
                            <small class="text-muted text-uppercase smaller fw-black letter-spacing-1">{{ __('messages.Posts') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if(Auth::check() && Auth::id() == $user->id)
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill fw-black px-4 py-2 shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-edit me-2"></i> {{ __('messages.edit') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Profile Navigation -->
        <div class="card-footer bg-white border-top p-0 overflow-x-auto">
            @include('theme::profile.navigation', ['selectedTab' => $selectedTab])
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ $relationshipTitle }}</h6>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="display-4 fw-black text-primary mb-1">{{ number_format($relationshipTotal) }}</div>
                    <p class="text-muted small fw-bold mb-0">{{ $relationshipTitle }} {{ __('messages.total') ?? 'Total' }}</p>
                </div>
            </div>
            <x-widget-column side="profile_left" />
        </div>

        <!-- Relationship List -->
        <div class="col-lg-8">
            <div class="d-grid gap-4">
                @forelse($relationshipItems as $relationship)
                    @php
                        $targetUser = $relationshipType === 'followers'
                            ? $relationship->user
                            : $relationship->targetUser;
                        $isViewerFollowingTarget = in_array((int) $targetUser->id, $viewerFollowingIds ?? [], true);
                    @endphp

                    @if($targetUser)
                        <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden transition-all hover-translate-y">
                            <div class="card-body p-4">
                                <div class="row align-items-center g-4">
                                    <div class="col-auto">
                                        <div class="position-relative">
                                            <img src="{{ $targetUser->avatarUrl() }}" alt="{{ $targetUser->username }}" class="rounded-circle shadow-sm border border-3 border-white" width="70" height="70" style="object-fit: cover;">
                                            <div class="position-absolute bottom-0 end-0 bg-{{ $targetUser->isOnline() ? 'success' : 'secondary' }} border border-2 border-white rounded-circle" style="width: 15px; height: 15px;"></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="fw-black mb-1">
                                            <a href="{{ route('profile.short', $targetUser->publicRouteIdentifier()) }}" class="text-dark text-decoration-none hover-primary">
                                                {{ $targetUser->username }}
                                                @if($targetUser->hasVerifiedBadge())
                                                    <i class="fa fa-check-circle text-primary fs-6 ms-1"></i>
                                                @endif
                                            </a>
                                        </h5>
                                        <div class="d-flex flex-wrap gap-3 text-muted smaller fw-black letter-spacing-1 text-uppercase">
                                            <span><i class="fa fa-clock me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($targetUser->online)->diffForHumans() }}</span>
                                            @if(!empty($relationship->date ?? $relationship->time_t))
                                                <span><i class="fa fa-user-plus me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($relationship->date ?? $relationship->time_t)->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-auto text-md-end">
                                        <div class="d-flex gap-2 justify-content-md-end">
                                            @if(Auth::check() && Auth::id() !== $targetUser->id)
                                                <form action="{{ route('profile.follow', $targetUser->username) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-{{ $isViewerFollowingTarget ? 'danger' : 'primary' }} rounded-pill fw-black px-4 py-2 shadow-sm transition-all hover-translate-y">
                                                        <i class="fa {{ $isViewerFollowingTarget ? 'fa-user-times' : 'fa-user-plus' }} me-2"></i>
                                                        {{ $isViewerFollowingTarget ? __('messages.unfollow') : __('messages.follow') }}
                                                    </button>
                                                </form>
                                                <a href="{{ route('messages.show', \App\Models\Message::encodeConversationRouteKey(auth()->id(), $targetUser)) }}" class="btn btn-outline-primary rounded-pill fw-black px-4 py-2 shadow-sm transition-all hover-translate-y">
                                                    <i class="fa fa-envelope me-2"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-25 border border-light">
                        <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-4 border border-light">
                            <i class="fa fa-users fa-3x text-muted opacity-25"></i>
                        </div>
                        <h4 class="fw-black text-dark">{{ $emptyMessage }}</h4>
                        <p class="text-muted small mb-0 fw-bold">Try interacting with other community members!</p>
                    </div>
                @endforelse
            </div>

            @if(method_exists($relationshipItems, 'hasPages') && $relationshipItems->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $relationshipItems->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .hover-primary:hover { color: var(--bs-primary) !important; }
    .bg-gradient-dark { background: linear-gradient(transparent, rgba(0,0,0,0.7)); }
    .drop-shadow { text-shadow: 0 2px 4px rgba(0,0,0,0.3); }</style>
