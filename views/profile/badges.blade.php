@extends('theme::layouts.master')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-5 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-award fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.badges') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.badge_showcase_help') }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-crown fa-10x"></i>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            @include('theme::profile.settings_nav')
            <x-widget-column side="portal_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden mb-4">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.badge_showcase') ?? 'Badge Showcase' }}</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @include('theme::billing.partials.alerts')

                    @if(!empty($upgradeNotice))
                        @include('theme::partials.upgrade_notice', ['upgradeNotice' => $upgradeNotice])
                    @endif

                    <form action="{{ route('profile.badges.update') }}" method="POST">
                        @csrf
                        <fieldset {{ !($featureAvailable ?? true) ? 'disabled' : '' }}>
                            <div class="alert alert-info border-0 shadow-sm rounded-4 p-4 mb-5 d-flex align-items-center" role="alert">
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fa fa-info-circle text-info fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-1">{{ __('messages.important_note') ?? 'Important Note' }}</div>
                                    <div class="small text-muted fw-bold">{{ __('messages.badge_showcase_limit') }}</div>
                                </div>
                            </div>

                            <div class="row g-4">
                                @forelse($earnedBadges as $earned)
                                    @php $badge = $earned->badge; @endphp
                                    @if($badge)
                                        @php $isSelected = in_array($badge->id, $showcaseIds, true); @endphp
                                        <div class="col-lg-4 col-md-6">
                                            <label class="card h-100 border-0 shadow-sm rounded-4 cursor-pointer transition-all hover-translate-y badge-selector-card overflow-hidden border border-light {{ $isSelected ? 'border-primary border-opacity-50 bg-primary bg-opacity-5' : 'bg-light bg-opacity-25' }}">
                                                <input type="checkbox" name="badge_ids[]" value="{{ $badge->id }}" {{ $isSelected ? 'checked' : '' }} class="d-none badge-checkbox">
                                                <div class="card-body p-4 text-center">
                                                    <div class="bg-white rounded-circle shadow-sm p-3 mb-4 d-inline-flex border border-light transition-all hover-scale badge-icon-container" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                                                        <div class="fs-1 text-primary">
                                                            @if($badge->icon && str_contains($badge->icon, ' '))
                                                                <i class="{{ $badge->icon }}"></i>
                                                            @elseif($badge->icon && str_starts_with($badge->icon, 'fa-'))
                                                                <i class="fa {{ $badge->icon }}"></i>
                                                            @elseif($badge->icon && str_starts_with($badge->icon, 'svg-'))
                                                                <svg class="icon {{ $badge->icon }} w-100 h-100"><use xlink:href="#{{ $badge->icon }}"></use></svg>
                                                            @else
                                                                <i class="fa fa-award"></i>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <h6 class="fw-black mb-2 text-dark text-truncate">{{ __('messages.' . $badge->name_key) }}</h6>
                                                    <p class="text-muted smallest mb-3 fw-bold lh-sm text-truncate-2" style="min-height: 32px;">{{ __('messages.' . $badge->description_key) }}</p>
                                                    
                                                    <div class="mt-auto">
                                                        <span class="badge rounded-pill shadow-sm py-2 px-3 transition-all fw-black smaller letter-spacing-1 text-uppercase {{ $isSelected ? 'bg-primary' : 'bg-white text-muted border border-light' }} showcase-badge">
                                                            <i class="fa {{ $isSelected ? 'fa-check-circle' : 'fa-eye' }} me-2"></i> {{ __('messages.showcase') ?? 'Showcase' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endif
                                @empty
                                    <div class="col-12">
                                        <div class="p-5 text-center bg-light bg-opacity-25 rounded-4 border border-light">
                                            <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-4 border border-light">
                                                <i class="fa fa-award fa-3x text-muted opacity-25"></i>
                                            </div>
                                            <h4 class="fw-black text-dark">{{ ($featureAvailable ?? true) ? __('messages.no_badges_unlocked') : __('messages.upgrade_legacy_mode_notice') }}</h4>
                                            <p class="text-muted small mb-0 fw-bold">Earn badges by participating in the community!</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <div class="d-flex justify-content-center pt-5 border-top mt-5">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg transition-all hover-translate-y" {{ !($featureAvailable ?? true) ? 'disabled' : '' }}>
                                    <i class="fa fa-save me-2"></i> {{ __('messages.save_changes') }}
                                </button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
