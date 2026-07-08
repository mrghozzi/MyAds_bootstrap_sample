@php
    $topUsers = \App\Models\User::orderBy('pts', 'desc')->limit(5)->get();
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-3">
            @foreach($topUsers as $index => $user)
                @php
                    $isOnline = $user->isOnline();
                    $userProfileUrl = route('profile.short', $user->publicRouteIdentifier());
                @endphp
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center fw-bold text-muted" style="width: 20px;">
                            @if($index === 0)
                                <i class="fa fa-trophy text-warning fs-5" title="1st"></i>
                            @elseif($index === 1)
                                <i class="fa fa-trophy text-secondary" style="color: #c0c0c0 !important; font-size: 1.1rem;" title="2nd"></i>
                            @elseif($index === 2)
                                <i class="fa fa-trophy text-danger" style="color: #cd7f32 !important; font-size: 1.1rem;" title="3rd"></i>
                            @else
                                <span class="small">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <a href="{{ $userProfileUrl }}" class="position-relative">
                            <img src="{{ $user->avatarUrl() }}" class="rounded-circle border" width="38" height="38" alt="{{ $user->username }}">
                            @if($isOnline)
                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 10px; height: 10px;"></span>
                            @endif
                        </a>
                        <div>
                            <h6 class="mb-0 fw-bold small">
                                <a href="{{ $userProfileUrl }}" class="text-dark text-decoration-none hover-primary">{{ $user->username }}</a>
                            </h6>
                            <small class="text-muted smaller">{{ number_format($user->pts) }} {{ __('messages.pts') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
