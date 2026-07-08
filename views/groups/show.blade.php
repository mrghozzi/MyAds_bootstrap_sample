@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Group Header -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <!-- Cover Image -->
        <div class="position-relative" style="height: 250px;">
            <img src="{{ asset($cover) }}" class="w-100 h-100 object-fit-cover">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>
            
            @if($group->is_featured)
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-bold shadow-sm">
                        <i class="fa fa-star me-1"></i> {{ __('messages.groups_featured') }}
                    </span>
                </div>
            @endif
        </div>

        <div class="card-body p-4 pt-0">
            <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end mt-n5 position-relative" style="margin-top: -50px;">
                <!-- Avatar -->
                <div class="bg-white p-1 rounded-circle shadow-sm mb-3 mb-md-0">
                    <img src="{{ asset($avatar) }}" class="rounded-circle border border-4 border-white" width="120" height="120" style="object-fit: cover;">
                </div>
                
                <!-- Group Title & Privacy -->
                <div class="ms-md-4 flex-grow-1 text-center text-md-start mb-3 mb-md-0">
                    <h2 class="fw-black mb-1 text-dark">{{ $group->name }}</h2>
                    <p class="text-muted mb-0 small fw-bold">
                        <i class="fa {{ $group->privacy === \App\Models\Group::PRIVACY_PUBLIC ? 'fa-globe' : 'fa-lock' }} me-1"></i>
                        {{ $group->privacy === \App\Models\Group::PRIVACY_PUBLIC ? __('messages.groups_public') : __('messages.groups_private') }}
                        &middot; 
                        <span class="text-primary">{{ $group->members_count }} {{ __('messages.members') }}</span>
                    </p>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2">
                    @auth
                        @if($canManageGroup)
                            <a href="{{ route('groups.edit', $group) }}" class="btn btn-light rounded-pill px-4 fw-bold border">
                                <i class="fa fa-cog me-1 text-muted"></i> {{ __('messages.edit') }}
                            </a>
                        @endif

                        @if($membership?->status === \App\Models\GroupMembership::STATUS_ACTIVE)
                            @if($membership->role !== \App\Models\GroupMembership::ROLE_OWNER)
                                <form method="POST" action="{{ route('groups.leave', $group) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-danger rounded-pill px-4 fw-bold" type="submit" onclick="return confirm('{{ __('messages.confirm_leave_group') }}')">
                                        {{ __('messages.groups_leave') }}
                                    </button>
                                </form>
                            @endif
                        @elseif($membership?->status === \App\Models\GroupMembership::STATUS_PENDING)
                            <button class="btn btn-secondary rounded-pill px-4 fw-bold disabled" type="button">
                                <i class="fa fa-clock me-1"></i> {{ __('messages.groups_request_pending') }}
                            </button>
                        @elseif($group->status === \App\Models\Group::STATUS_ACTIVE)
                            <form method="POST" action="{{ route('groups.join', $group) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" type="submit">
                                    <i class="fa {{ $group->privacy === \App\Models\Group::PRIVACY_PUBLIC ? 'fa-user-plus' : 'fa-paper-plane' }} me-1"></i>
                                    {{ $group->privacy === \App\Models\Group::PRIVACY_PUBLIC ? __('messages.groups_join_now') : __('messages.groups_request_join') }}
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                            <i class="fa fa-sign-in-alt me-1"></i> {{ __('messages.groups_join_now') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Navigation Tabs -->
        <div class="card-footer bg-white border-top p-0 px-4">
            <ul class="nav nav-pills nav-fill-md gap-2 py-2">
                <li class="nav-item">
                    <a class="nav-link rounded-pill fw-bold {{ $tab === 'overview' ? 'active' : 'text-muted' }}" href="{{ route('groups.show', [$group, 'tab' => 'overview']) }}">
                        <i class="fa fa-th-large me-2"></i> {{ __('messages.overview') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill fw-bold {{ $tab === 'feed' ? 'active' : 'text-muted' }}" href="{{ route('groups.show', [$group, 'tab' => 'feed']) }}">
                        <i class="fa fa-rss me-2"></i> {{ __('messages.feed') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill fw-bold {{ $tab === 'discussions' ? 'active' : 'text-muted' }}" href="{{ route('groups.show', [$group, 'tab' => 'discussions']) }}">
                        <i class="fa fa-comments me-2"></i> {{ __('messages.discussions') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill fw-bold {{ $tab === 'members' ? 'active' : 'text-muted' }}" href="{{ route('groups.show', [$group, 'tab' => 'members']) }}">
                        <i class="fa fa-users me-2"></i> {{ __('messages.members') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Sidebar Left -->
        <div class="col-lg-3">
            <!-- About Group -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.about') }}</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-0">{{ $group->description ?: __('messages.groups_no_description') }}</p>
                </div>
            </div>

            <!-- Rules -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.groups_rules') }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-muted small mb-0" style="white-space: pre-line;">{!! trim((string) $group->rules_markdown) !== '' ? nl2br(e($group->rules_markdown)) : e(__('messages.groups_rules_empty')) !!}</div>
                </div>
            </div>

            <x-widget-column side="groups_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-6">
            @if(!$canViewContent)
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="bg-light d-inline-block p-4 rounded-circle mb-4 mx-auto">
                        <i class="fa fa-lock fa-4x text-muted opacity-25"></i>
                    </div>
                    <h4 class="fw-bold">{{ __('messages.groups_private_shell_title') }}</h4>
                    <p class="text-muted">{{ __('messages.groups_private_shell_description') }}</p>
                    @if($membership?->status !== \App\Models\GroupMembership::STATUS_PENDING)
                        <div class="mt-4">
                            <form method="POST" action="{{ route('groups.join', $group) }}">
                                @csrf
                                <button class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" type="submit">
                                    {{ __('messages.groups_request_join') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @else
                <!-- Composer (Feed/Discussions) -->
                @if(in_array($tab, ['overview', 'feed', 'discussions'], true) && $canPostToGroup)
                    @if($tab === 'discussions')
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-3 text-uppercase smaller text-muted">{{ __('messages.groups_start_discussion') }}</h6>
                            <form method="POST" action="{{ route('groups.discussions.store', $group) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" name="name" class="form-control bg-light border-0 py-2" placeholder="{{ __('messages.subject') }}" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="txt" rows="3" class="form-control bg-light border-0" placeholder="{{ __('messages.description') }}" required></textarea>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-3">
                                        <div class="form-check small">
                                            <input class="form-check-input" type="radio" name="type" id="type_text" value="2" checked>
                                            <label class="form-check-label text-muted" for="type_text">{{ __('messages.text') }}</label>
                                        </div>
                                        <div class="form-check small">
                                            <input class="form-check-input" type="radio" name="type" id="type_photo" value="4">
                                            <label class="form-check-label text-muted" for="type_photo">{{ __('messages.insertphoto') }}</label>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="file" name="img" class="d-none" id="discussion_img" accept="image/*">
                                        <label for="discussion_img" class="btn btn-light btn-sm rounded-pill"><i class="fa fa-image me-1"></i> {{ __('messages.image') }}</label>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold btn-sm shadow-sm">{{ __('messages.groups_publish_discussion') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
                            @include('theme::partials.status.add_post', [
                                'composerContext' => [
                                    'group' => $group,
                                    'group_id' => $group->id,
                                    'allowedKinds' => ['text', 'link', 'gallery'],
                                    'submitLabelKey' => 'messages.groups_publish_post',
                                    'placeholderKey' => 'messages.groups_post_placeholder',
                                    'disableDirectoryOnly' => true,
                                ],
                            ])
                        </div>
                    @endif
                @endif

                <!-- Tab Content -->
                @if($tab === 'overview')
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-dark">{{ __('messages.groups_latest_feed') }}</h5>
                            <a href="{{ route('groups.show', [$group, 'tab' => 'feed']) }}" class="text-primary smaller fw-bold text-decoration-none">{{ __('messages.see_all') }}</a>
                        </div>
                        <div class="d-grid gap-3">
                            @forelse($activities->take(3) as $activity)
                                @include('theme::partials.activity.render', ['activity' => $activity])
                            @empty
                                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                                    <p class="text-muted small mb-0">{{ __('messages.groups_feed_empty') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-dark">{{ __('messages.groups_latest_discussions') }}</h5>
                            <a href="{{ route('groups.show', [$group, 'tab' => 'discussions']) }}" class="text-primary smaller fw-bold text-decoration-none">{{ __('messages.see_all') }}</a>
                        </div>
                        <div class="d-grid gap-3">
                            @forelse($discussions->take(4) as $topic)
                                <div class="card border-0 shadow-sm rounded-4 p-4 transition-all hover-translate-y">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0"><a href="{{ route('forum.topic', $topic->id) }}" class="text-dark text-decoration-none">{{ $topic->name }}</a></h6>
                                        <span class="smaller text-muted">{{ $topic->date ? \Carbon\Carbon::createFromTimestamp($topic->date)->diffForHumans() : '' }}</span>
                                    </div>
                                    <p class="text-muted smaller mb-3 text-truncate-2">{{ \Illuminate\Support\Str::limit(strip_tags((string) $topic->txt), 180) }}</p>
                                    <div class="d-flex align-items-center justify-content-between mt-auto border-top pt-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $topic->user?->avatarUrl() ?? asset('upload/_avatar.png') }}" class="rounded-circle me-2" width="24" height="24">
                                            <span class="smaller fw-bold text-muted">{{ $topic->user?->username ?? __('messages.unknown_user') }}</span>
                                        </div>
                                        <span class="badge bg-light text-muted border fw-bold smaller rounded-pill px-3">{{ $topic->replies_count ?? 0 }} {{ __('messages.replies') }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                                    <p class="text-muted small mb-0">{{ __('messages.groups_discussions_empty') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                @if($tab === 'feed')
                    <div class="d-grid gap-3 mb-4">
                        @forelse($activities as $activity)
                            @include('theme::partials.activity.render', ['activity' => $activity])
                        @empty
                            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                                <i class="fa fa-rss fa-3x mb-3 text-muted opacity-25"></i>
                                <p class="text-muted mb-0">{{ __('messages.groups_feed_empty') }}</p>
                            </div>
                        @endforelse
                    </div>
                    {{ $activities->appends(['tab' => 'feed'])->links() }}
                @endif

                @if($tab === 'discussions')
                    <div class="d-grid gap-3 mb-4">
                        @forelse($discussions as $topic)
                            <div class="card border-0 shadow-sm rounded-4 p-4 transition-all hover-translate-y">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0"><a href="{{ route('forum.topic', $topic->id) }}" class="text-dark text-decoration-none fs-5">{{ $topic->name }}</a></h6>
                                    <span class="smaller text-muted">{{ $topic->date ? \Carbon\Carbon::createFromTimestamp($topic->date)->diffForHumans() : '' }}</span>
                                </div>
                                <p class="text-muted smaller mb-3">{{ \Illuminate\Support\Str::limit(strip_tags((string) $topic->txt), 300) }}</p>
                                <div class="d-flex align-items-center justify-content-between mt-auto border-top pt-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $topic->user?->avatarUrl() ?? asset('upload/_avatar.png') }}" class="rounded-circle me-2" width="30" height="30">
                                        <span class="smaller fw-bold text-muted">{{ $topic->user?->username ?? __('messages.unknown_user') }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-light text-muted border fw-bold smaller rounded-pill px-3 py-2"><i class="fa fa-comment me-1"></i> {{ $topic->replies_count ?? 0 }}</span>
                                        <span class="badge bg-light text-muted border fw-bold smaller rounded-pill px-3 py-2"><i class="fa fa-eye me-1"></i> {{ $topic->views ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                                <i class="fa fa-comments fa-3x mb-3 text-muted opacity-25"></i>
                                <p class="text-muted mb-0">{{ __('messages.groups_discussions_empty') }}</p>
                            </div>
                        @endforelse
                    </div>
                    {{ $discussions->appends(['tab' => 'discussions'])->links() }}
                @endif

                @if($tab === 'members')
                    <!-- Pending Requests (Moderators Only) -->
                    @if($canManageGroup && $pendingMemberships->isNotEmpty())
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.groups_pending_requests') }}</h6>
                            <div class="row g-3">
                                @foreach($pendingMemberships as $pending)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="{{ $pending->user?->avatarUrl() ?? asset('upload/_avatar.png') }}" class="rounded-circle me-3 shadow-sm" width="45" height="45">
                                                <div class="overflow-hidden">
                                                    <h6 class="fw-bold mb-0 text-truncate text-dark">{{ $pending->user?->username ?? __('messages.unknown_user') }}</h6>
                                                    <span class="smaller text-muted text-truncate d-block">{{ $pending->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 mt-auto">
                                                <form method="POST" action="{{ route('groups.members.approve', [$group, $pending]) }}" class="flex-grow-1">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm w-100 rounded-pill fw-bold" type="submit">{{ __('messages.approve') }}</button>
                                                </form>
                                                <form method="POST" action="{{ route('groups.members.reject', [$group, $pending]) }}" class="flex-grow-1">
                                                    @csrf
                                                    <button class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold" type="submit">{{ __('messages.reject') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Active Members -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.groups_members_title') }}</h6>
                        <div class="row g-3">
                            @forelse($members as $member)
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100 position-relative">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ $member->user?->avatarUrl() ?? asset('upload/_avatar.png') }}" class="rounded-circle me-3 shadow-sm" width="50" height="50">
                                            <div class="overflow-hidden">
                                                <h6 class="fw-bold mb-0 text-truncate text-dark">{{ $member->user?->username ?? __('messages.unknown_user') }}</h6>
                                                <span class="badge {{ $member->role === 'owner' ? 'bg-primary' : ($member->role === 'moderator' ? 'bg-info' : 'bg-light text-muted border') }} smaller fw-bold rounded-pill px-3 mt-1">
                                                    {{ __('messages.groups_role_' . $member->role) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @if($canManageGroup && $member->role !== \App\Models\GroupMembership::ROLE_OWNER)
                                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                                <form method="POST" action="{{ route('groups.members.role', [$group, $member]) }}" class="flex-grow-1 me-2">
                                                    @csrf
                                                    <select name="role" onchange="this.form.submit()" class="form-select form-select-sm rounded-pill bg-light border-0">
                                                        <option value="member" {{ $member->role === 'member' ? 'selected' : '' }}>{{ __('messages.groups_role_member') }}</option>
                                                        <option value="moderator" {{ $member->role === 'moderator' ? 'selected' : '' }}>{{ __('messages.groups_role_moderator') }}</option>
                                                    </select>
                                                </form>
                                                
                                                @if($group->owner_id === Auth::id())
                                                    <button class="btn btn-outline-warning btn-sm rounded-pill px-2" title="{{ __('messages.groups_transfer_ownership') }}" onclick="toggleTransferForm({{ $member->id }})">
                                                        <i class="fa fa-crown"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            @if($group->owner_id === Auth::id())
                                                <div id="transfer-form-{{ $member->id }}" class="mt-3 bg-light p-3 rounded-4 border border-warning" style="display: none;">
                                                    <form method="POST" action="{{ route('groups.transfer', [$group, $member]) }}">
                                                        @csrf
                                                        <p class="smaller text-dark fw-bold mb-3">{{ __('messages.groups_transfer_confirm') }} <u>{{ $member->user?->username }}</u></p>
                                                        <input type="password" name="password" required class="form-control form-control-sm rounded-pill mb-3" placeholder="{{ __('messages.password') }}">
                                                        <div class="d-flex gap-2">
                                                            <button type="submit" class="btn btn-warning btn-sm rounded-pill fw-bold flex-grow-1 text-white">{{ __('messages.confirm') }}</button>
                                                            <button type="button" class="btn btn-light btn-sm rounded-pill fw-bold border flex-grow-1" onclick="toggleTransferForm({{ $member->id }})">{{ __('messages.cancel') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                                        <p class="text-muted small mb-0">{{ __('messages.groups_members_empty') }}</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            {{ $members->appends(['tab' => 'members'])->links() }}
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Sidebar Right -->
        <div class="col-lg-3">
            <!-- Group Stats -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.stats') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fa fa-users text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold small text-dark">{{ $group->members_count }}</div>
                            <div class="smaller text-muted text-uppercase fw-bold">{{ __('messages.members') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div class="bg-success bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fa fa-rss text-success"></i>
                        </div>
                        <div>
                            <div class="fw-bold small text-dark">{{ $group->posts_count }}</div>
                            <div class="smaller text-muted text-uppercase fw-bold">{{ __('messages.posts') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center p-3">
                        <div class="bg-info bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="fa fa-user-shield text-info"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold small text-dark text-truncate">{{ $group->owner?->username ?? __('messages.unknown_user') }}</div>
                            <div class="smaller text-muted text-uppercase fw-bold">{{ __('messages.author') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <x-widget-column side="groups_right" />
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .transition-all { transition: all 0.3s ease; }
    .object-fit-cover { object-fit: cover; }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .mt-n5 { margin-top: -3rem !important; }
    .nav-pills .nav-link.active {
        background-color: #615dfa;
    }
    .nav-pills .nav-link {
        font-size: 0.85rem;
    }
</style>

@push('scripts')
<script>
    function toggleTransferForm(memberId) {
        const form = document.getElementById('transfer-form-' + memberId);
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    }
</script>
@endpush
@endsection
