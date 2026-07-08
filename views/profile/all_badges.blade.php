@extends('theme::layouts.master')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                <i class="fa fa-award fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-bold mb-1">{{ __('messages.badges_hub') }}</h1>
                <p class="mb-0 text-white-50 small">{{ __('messages.badges_hub_desc') }}</p>
            </div>
        </div>
    </div>

    @if(!empty($upgradeNotice))
        @include('theme::partials.upgrade_notice', ['upgradeNotice' => $upgradeNotice])
    @endif

    <div class="row g-4">
        @forelse($badges as $badge)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-all hover-translate-y position-relative {{ $badge->is_unlocked ? 'border-top border-4 border-success' : '' }}">
                    <div class="position-absolute top-0 end-0 p-3">
                        @if($badge->is_unlocked)
                            <span class="badge bg-success rounded-pill px-3 py-1 shadow-sm small fw-bold">
                                {{ __('messages.badge_unlocked') }}
                            </span>
                        @else
                            <span class="badge bg-light text-muted border rounded-pill px-3 py-1 small fw-bold">
                                {{ __('messages.badge_locked') }}
                            </span>
                        @endif
                    </div>

                    <div class="card-body p-4 text-center d-flex flex-column">
                        <div class="mb-4 mt-3">
                            <div class="bg-{{ $badge->is_unlocked ? 'success' : 'primary' }} bg-opacity-10 text-{{ $badge->is_unlocked ? 'success' : 'primary' }} rounded-circle mx-auto d-flex align-items-center justify-content-center transition-all hover-rotate" style="width: 80px; height: 80px;">
                                <div class="fs-1">
                                    @if($badge->icon && str_contains($badge->icon, ' '))
                                        <i class="{{ $badge->icon }}"></i>
                                    @elseif($badge->icon && str_starts_with($badge->icon, 'fa-'))
                                        <i class="fa {{ $badge->icon }}"></i>
                                    @elseif($badge->icon && str_starts_with($badge->icon, 'svg-'))
                                        <svg class="icon {{ $badge->icon }} w-50 h-50"><use xlink:href="#{{ $badge->icon }}"></use></svg>
                                    @else
                                        <i class="fa fa-trophy"></i>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-2 text-dark">{{ __('messages.' . $badge->name_key) }}</h5>
                        <p class="text-muted small mb-4 lh-base flex-grow-1">{{ __('messages.' . $badge->description_key) }}</p>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2 small fw-bold">
                                <span class="text-muted">{{ __('messages.progress') }}</span>
                                <span class="text-primary">{{ $badge->progress }} / {{ $badge->criteria_target }}</span>
                            </div>

                            <div class="progress rounded-pill mb-2" style="height: 8px;">
                                <div class="progress-bar {{ $badge->is_unlocked ? 'bg-success' : 'bg-primary' }}" 
                                     role="progressbar" 
                                     style="width: {{ ($badge->progress / $badge->criteria_target) * 100 }}%" 
                                     aria-valuenow="{{ $badge->progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $badge->criteria_target }}"></div>
                            </div>

                            @if($badge->is_unlocked && $badge->unlocked_at)
                                <div class="text-success smaller fw-bold mt-2">
                                    <i class="fa fa-check-circle me-1"></i> {{ __('messages.unlocked_on', ['date' => $badge->unlocked_at->format('M d, Y')]) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted">
                    <i class="fa fa-award fa-4x mb-3 opacity-10"></i>
                    <h5 class="fw-bold">{{ __('messages.no_data') }}</h5>
                    <p class="mb-0 small">Badges will appear here as they are created by admins.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
