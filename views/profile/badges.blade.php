@extends('theme::layouts.master')

@push('head')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .badge-selector-card {
        cursor: pointer;
        user-select: none;
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        position: relative;
        background-color: var(--bs-card-bg, #ffffff);
        border: 2px solid var(--bs-border-color, #eaeaf5) !important;
    }
    .badge-selector-card:hover {
        transform: translateY(-3px);
        border-color: var(--msg-primary, #615dfa) !important;
        box-shadow: 0 8px 20px rgba(97, 93, 250, 0.12) !important;
    }
    .badge-selector-card.is-selected,
    .badge-selector-card:has(input:checked) {
        border-color: var(--msg-primary, #615dfa) !important;
        background-color: rgba(97, 93, 250, 0.06) !important;
        box-shadow: 0 8px 24px rgba(97, 93, 250, 0.16) !important;
    }
    [data-theme="css_d"] .badge-selector-card.is-selected,
    [data-bs-theme="dark"] .badge-selector-card.is-selected,
    [data-theme="css_d"] .badge-selector-card:has(input:checked),
    [data-bs-theme="dark"] .badge-selector-card:has(input:checked) {
        background-color: rgba(119, 80, 248, 0.12) !important;
        border-color: #7750f8 !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35) !important;
    }
    .badge-check-indicator {
        position: absolute;
        top: 14px;
        inset-inline-end: 14px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid #dedeea;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: transparent;
        font-size: 0.75rem;
        transition: all 0.2s ease;
    }
    .badge-selector-card.is-selected .badge-check-indicator,
    .badge-selector-card:has(input:checked) .badge-check-indicator {
        background-color: var(--msg-primary, #615dfa);
        border-color: var(--msg-primary, #615dfa);
        color: #ffffff;
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(97, 93, 250, 0.35);
    }
    .badge-icon-box {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background-color: rgba(97, 93, 250, 0.1);
        color: var(--msg-primary, #615dfa);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px auto;
        transition: transform 0.2s ease;
    }
    .badge-selector-card:hover .badge-icon-box {
        transform: scale(1.08);
    }
</style>
@endpush

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
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.badge_showcase') ?? 'Badge Showcase' }}</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-black small" id="badge-counter">
                        <i class="fa fa-shield-halved me-1"></i> <span id="selected-count">{{ count($showcaseIds) }}</span> / 6 {{ __('messages.selected') ?? 'Selected' }}
                    </span>
                </div>
                <div class="card-body p-4 p-md-5">
                    @include('theme::billing.partials.alerts')

                    @if(!empty($upgradeNotice))
                        @include('theme::partials.upgrade_notice', ['upgradeNotice' => $upgradeNotice])
                    @endif

                    <form action="{{ route('profile.badges.update') }}" method="POST" id="badges-form">
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

                            <div class="row g-4" id="badges-grid">
                                @forelse($earnedBadges as $earned)
                                    @php $badge = $earned->badge; @endphp
                                    @if($badge)
                                        @php $isSelected = in_array($badge->id, $showcaseIds, true); @endphp
                                        <div class="col-lg-4 col-md-6">
                                            <label class="card h-100 rounded-4 p-4 text-center badge-selector-card {{ $isSelected ? 'is-selected' : '' }}" for="badge-check-{{ $badge->id }}">
                                                <input
                                                    type="checkbox"
                                                    id="badge-check-{{ $badge->id }}"
                                                    name="badge_ids[]"
                                                    value="{{ $badge->id }}"
                                                    {{ $isSelected ? 'checked' : '' }}
                                                    class="badge-checkbox d-none"
                                                >
                                                
                                                <div class="badge-check-indicator">
                                                    <i class="fa fa-check"></i>
                                                </div>

                                                <div class="badge-icon-box">
                                                    <div class="fs-1">
                                                        @if($badge->icon && str_contains($badge->icon, ' '))
                                                            <i class="{{ $badge->icon }}"></i>
                                                        @elseif($badge->icon && str_starts_with($badge->icon, 'fa-'))
                                                            <i class="fa {{ $badge->icon }}"></i>
                                                        @elseif($badge->icon && str_starts_with($badge->icon, 'svg-'))
                                                            <svg class="icon {{ $badge->icon }}" style="width: 36px; height: 36px;"><use xlink:href="#{{ $badge->icon }}"></use></svg>
                                                        @else
                                                            <i class="fa fa-award"></i>
                                                        @endif
                                                    </div>
                                                </div>

                                                <h6 class="fw-black mb-2 text-dark text-truncate">{{ __('messages.' . $badge->name_key) }}</h6>
                                                <p class="text-muted smallest mb-3 fw-bold lh-sm text-truncate-2" style="min-height: 32px;">{{ __('messages.' . $badge->description_key) }}</p>
                                                
                                                <div class="mt-auto pt-2">
                                                    <span class="badge rounded-pill shadow-sm py-2 px-3 fw-black smaller letter-spacing-1 text-uppercase transition-all showcase-badge-pill {{ $isSelected ? 'bg-primary text-white' : 'bg-light text-muted border' }}">
                                                        <i class="fa {{ $isSelected ? 'fa-check-circle' : 'fa-plus' }} me-1"></i>
                                                        <span class="showcase-status-text">{{ $isSelected ? (__('messages.showcase') ?? 'Showcase') : (__('messages.select') ?? 'Select') }}</span>
                                                    </span>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const MAX_BADGES = 6;
        const checkboxes = document.querySelectorAll('.badge-checkbox');
        const countDisplay = document.getElementById('selected-count');

        function updateBadgeCount() {
            const checkedCount = document.querySelectorAll('.badge-checkbox:checked').length;
            if (countDisplay) {
                countDisplay.textContent = checkedCount;
            }
        }

        checkboxes.forEach(function(checkbox) {
            const card = checkbox.closest('.badge-selector-card');
            const pill = card ? card.querySelector('.showcase-badge-pill') : null;
            const statusText = card ? card.querySelector('.showcase-status-text') : null;
            const icon = pill ? pill.querySelector('i') : null;

            function syncCardState(isChecked) {
                if (!card) return;
                card.classList.toggle('is-selected', isChecked);
                if (pill) {
                    pill.classList.toggle('bg-primary', isChecked);
                    pill.classList.toggle('text-white', isChecked);
                    pill.classList.toggle('bg-light', !isChecked);
                    pill.classList.toggle('text-muted', !isChecked);
                    pill.classList.toggle('border', !isChecked);
                }
                if (icon) {
                    icon.className = isChecked ? 'fa fa-check-circle me-1' : 'fa fa-plus me-1';
                }
                if (statusText) {
                    statusText.textContent = isChecked ? 'Showcase' : 'Select';
                }
            }

            checkbox.addEventListener('change', function(e) {
                const totalChecked = document.querySelectorAll('.badge-checkbox:checked').length;
                if (this.checked && totalChecked > MAX_BADGES) {
                    this.checked = false;
                    syncCardState(false);
                    alert('You can select up to ' + MAX_BADGES + ' badges for your showcase.');
                    return;
                }

                syncCardState(this.checked);
                updateBadgeCount();
            });

            // Initial sync
            syncCardState(checkbox.checked);
        });

        updateBadgeCount();
    });
</script>
@endpush
