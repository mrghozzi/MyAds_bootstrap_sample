@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-5 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-trophy fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.quests') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.quests_description') }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-medal fa-10x"></i>
        </div>
    </div>

    <!-- Quests Grid -->
    <div class="row g-4">
        @forelse($quests as $quest)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-all hover-translate-y position-relative border border-light {{ $quest['is_completed'] ? 'border-top border-4 border-success' : '' }}">
                    @if($quest['is_completed'])
                        <div class="position-absolute top-0 end-0 p-3 z-3">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 text-uppercase border border-success border-opacity-10 shadow-sm">
                                <i class="fa fa-check-circle me-1"></i> {{ __('messages.completed') }}
                            </span>
                        </div>
                    @endif
                    
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="position-absolute top-0 start-0 p-3">
                            <span class="badge bg-warning bg-opacity-20 text-dark rounded-pill px-3 py-2 smallest fw-black letter-spacing-1 border border-warning border-opacity-25 shadow-sm">
                                <i class="fa fa-star me-1"></i> +{{ $quest['reward'] }} PTS
                            </span>
                        </div>

                        <div class="mb-5 mt-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center transition-all hover-scale shadow-sm border border-primary border-opacity-10" style="width: 100px; height: 100px;">
                                @if($quest['model']->icon && str_contains($quest['model']->icon, ' '))
                                    <i class="{{ $quest['model']->icon }} fa-3x"></i>
                                @elseif($quest['model']->icon && str_starts_with($quest['model']->icon, 'fa-'))
                                    <i class="fa {{ $quest['model']->icon }} fa-3x"></i>
                                @elseif($quest['model']->icon && str_starts_with($quest['model']->icon, 'svg-'))
                                    <svg class="w-50 h-50" fill="currentColor">
                                        <use xlink:href="#{{ $quest['model']->icon }}"></use>
                                    </svg>
                                @else
                                    <i class="fa fa-trophy fa-3x"></i>
                                @endif
                            </div>
                        </div>

                        <div class="smaller fw-black text-primary mb-2 text-uppercase letter-spacing-1">{{ $quest['period'] }}</div>
                        <h4 class="fw-black mb-3 text-dark">{{ __('messages.' . $quest['model']->name_key) }}</h4>
                        <p class="text-muted small mb-5 px-2 fw-bold lh-base">{{ __('messages.' . $quest['model']->description_key) }}</p>

                        <div class="bg-light bg-opacity-50 p-4 rounded-4 border border-light mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="smaller fw-black text-muted text-uppercase letter-spacing-1">{{ __('messages.quest_progress') }}</span>
                                <span class="smaller fw-black text-primary">{{ round($quest['percent']) }}%</span>
                            </div>

                            <div class="progress rounded-pill mb-3 shadow-inner" style="height: 12px; background-color: rgba(0,0,0,0.05);">
                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ $quest['is_completed'] ? 'bg-success' : 'bg-primary' }} rounded-pill" 
                                     role="progressbar" 
                                     style="width: {{ $quest['percent'] }}%" 
                                     aria-valuenow="{{ $quest['percent'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>

                            <div class="smaller fw-black {{ $quest['is_completed'] ? 'text-success' : 'text-muted' }} text-uppercase letter-spacing-1">
                                @if($quest['is_completed'])
                                    <i class="fa fa-check-circle me-1"></i> {{ __('messages.completed') }}
                                @else
                                    {{ $quest['current'] }} <span class="opacity-50">/</span> {{ $quest['target'] }}
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($quest['is_completed'])
                        <div class="card-footer bg-success bg-opacity-5 border-0 text-center py-3">
                            <span class="smallest text-success fw-black text-uppercase letter-spacing-1">
                                <i class="fa fa-gift me-2"></i> {{ __('messages.reward_claimed') ?? 'Reward Claimed' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light bg-opacity-25 border border-light">
                    <div class="rounded-circle bg-white shadow-sm p-5 mb-4 d-inline-flex border border-light">
                        <i class="fa fa-trophy fa-4xl text-muted opacity-25"></i>
                    </div>
                    <h4 class="fw-black text-dark">{{ __('messages.no_active_quests') }}</h4>
                    <p class="text-muted small mb-0 fw-bold">Check back later for new challenges!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .smallest { font-size: 0.7rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-10px); }
    .hover-scale:hover { transform: scale(1.1); }
    .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2) !important; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.06); }
    .z-3 { z-index: 3; }
</style>
@endsection
