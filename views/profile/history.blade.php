@extends('theme::layouts.master')
@section('content')
@php
    $posTotal = isset($positiveTotal) ? (float) $positiveTotal : collect($history->items())->sum(fn ($item) => max(0, (float) $item->amount));
    $negTotal = isset($negativeTotal) ? abs((float) $negativeTotal) : abs(collect($history->items())->sum(fn ($item) => min(0, (float) $item->amount)));
    $totalCount = isset($totalTransactions) ? (int) $totalTransactions : (method_exists($history, 'total') ? $history->total() : $history->count());
    $netBalance = $posTotal - $negTotal;
    $currentFilter = $activeFilter ?? request('filter', 'all');
    $currentSearch = $activeSearch ?? request('search', '');
@endphp

<div class="container py-4">
    <!-- Page Header Hero -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 position-relative z-1">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-20 p-3 rounded-4 me-3 border border-white border-opacity-25 shadow-sm">
                    <i class="fa-solid fa-coins fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.pts_history') }}</h1>
                    <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.pts_history_desc') ?? 'Track your points earnings and expenditures.' }}</p>
                </div>
            </div>

            <!-- Quick Wallet Actions -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="{{ route('profile.history.export', ['filter' => $currentFilter, 'search' => $currentSearch]) }}" 
                   id="ptsExportBtn" 
                   class="btn btn-sm btn-light rounded-pill px-3 py-2 fw-bold text-primary shadow-sm" 
                   download>
                    <i class="fa-solid fa-file-arrow-down me-1"></i>
                    <span>{{ __('messages.pts_export_statement') }}</span>
                </a>
                <a href="{{ route('dashboard') }}#quick-transfer" 
                   class="btn btn-sm btn-outline-light rounded-pill px-3 py-2 fw-bold">
                    <i class="fa-solid fa-paper-plane me-1"></i>
                    <span>{{ __('messages.pts_quick_transfer') }}</span>
                </a>
                <a href="{{ route('visits.surf') }}" 
                   class="btn btn-sm btn-warning rounded-pill px-3 py-2 fw-bold text-dark shadow-sm" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   onclick="var w = window.open(this.href, 'SurfWindow', 'width=1024,height=768,scrollbars=yes,resizable=yes'); if (w) { w.focus(); return false; }">
                    <i class="fa-solid fa-bolt me-1"></i>
                    <span>{{ __('messages.pts_earn_more') }}</span>
                </a>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block pointer-events-none">
            <i class="fa-solid fa-receipt fa-10x"></i>
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
            @include('theme::billing.partials.alerts')

            @if(!empty($upgradeNotice))
                @include('theme::partials.upgrade_notice', ['upgradeNotice' => $upgradeNotice])
            @endif

            <!-- Summary KPI Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success h-100 transition-all hover-translate-y">
                        <div class="card-body p-3 p-md-4 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3 border border-success border-opacity-10 shadow-sm">
                                <i class="fa-solid fa-arrow-down-left text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-black text-uppercase smaller text-muted letter-spacing-1 mb-1">{{ __('messages.pts_total_earned') }}</h6>
                                <h3 class="fw-black text-success mb-0" id="positivePointsTotal">+{{ rtrim(rtrim(number_format($posTotal, 2), '0'), '.') }} <small class="smallest opacity-75">PTS</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger h-100 transition-all hover-translate-y">
                        <div class="card-body p-3 p-md-4 d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-4 me-3 border border-danger border-opacity-10 shadow-sm">
                                <i class="fa-solid fa-arrow-up-right text-danger fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-black text-uppercase smaller text-muted letter-spacing-1 mb-1">{{ __('messages.pts_total_spent') }}</h6>
                                <h3 class="fw-black text-danger mb-0" id="negativePointsTotal">-{{ rtrim(rtrim(number_format($negTotal, 2), '0'), '.') }} <small class="smallest opacity-75">PTS</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-primary h-100 transition-all hover-translate-y">
                        <div class="card-body p-3 p-md-4 d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3 border border-primary border-opacity-10 shadow-sm">
                                <i class="fa-solid fa-scale-balanced text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-black text-uppercase smaller text-muted letter-spacing-1 mb-1">{{ __('messages.pts_net_balance') }}</h6>
                                <h3 class="fw-black {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }} mb-0" id="netPointsBalance">
                                    {{ $netBalance > 0 ? '+' : '' }}{{ rtrim(rtrim(number_format($netBalance, 2), '0'), '.') }} <small class="smallest opacity-75">PTS</small>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ledger Card with Filter & Search Toolbar -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 border border-light position-relative">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-black mb-0 text-dark">
                        <i class="fa-solid fa-list-check me-2 text-primary"></i>
                        {{ __('messages.pts_history_title') }}
                    </h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2 fw-black smaller" id="pointsTotalTransactions">
                        <span id="pointsTotalCountNum">{{ $totalCount }}</span> {{ __('messages.pts_transaction_id') }}
                    </span>
                </div>

                <!-- Interactive Filter & Search Bar -->
                <div class="bg-light bg-opacity-50 py-3 px-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
                    <!-- Filter Chips -->
                    <div class="d-flex align-items-center gap-2 flex-wrap" id="ptsFilterChips">
                        <button type="button" class="btn btn-sm {{ $currentFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-1 fw-bold pts-filter-chip" data-filter="all">
                            <i class="fa-solid fa-layer-group me-1"></i> {{ __('messages.pts_filter_all') }}
                        </button>
                        <button type="button" class="btn btn-sm {{ $currentFilter === 'credits' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-1 fw-bold pts-filter-chip" data-filter="credits">
                            <i class="fa-solid fa-arrow-down-left me-1"></i> {{ __('messages.pts_filter_credits') }}
                        </button>
                        <button type="button" class="btn btn-sm {{ $currentFilter === 'debits' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-1 fw-bold pts-filter-chip" data-filter="debits">
                            <i class="fa-solid fa-arrow-up-right me-1"></i> {{ __('messages.pts_filter_debits') }}
                        </button>
                        <button type="button" class="btn btn-sm {{ $currentFilter === 'transfers' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-1 fw-bold pts-filter-chip" data-filter="transfers">
                            <i class="fa-solid fa-arrow-right-arrow-left me-1"></i> {{ __('messages.pts_filter_transfers') }}
                        </button>
                        <button type="button" class="btn btn-sm {{ $currentFilter === 'ads' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-1 fw-bold pts-filter-chip" data-filter="ads">
                            <i class="fa-solid fa-rectangle-ad me-1"></i> {{ __('messages.pts_filter_ads') }}
                        </button>
                        <button type="button" class="btn btn-sm {{ $currentFilter === 'rewards' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-1 fw-bold pts-filter-chip" data-filter="rewards">
                            <i class="fa-solid fa-gift me-1"></i> {{ __('messages.pts_filter_rewards') }}
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="position-relative" style="min-width: 200px; max-width: 260px;">
                        <input type="text" 
                               id="ptsSearchInput" 
                               class="form-control form-control-sm rounded-pill ps-4 pe-4" 
                               placeholder="{{ __('messages.pts_search_placeholder') }}" 
                               value="{{ $currentSearch }}" 
                               autocomplete="off">
                        <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y text-muted smaller ps-2" style="left: 4px; pointer-events: none;"></i>
                        <button type="button" 
                                id="ptsSearchClear" 
                                class="btn btn-sm btn-link text-muted position-absolute top-50 translate-middle-y text-decoration-none p-0 pe-2" 
                                style="right: 4px; {{ !empty($currentSearch) ? '' : 'display: none;' }}" 
                                title="Clear">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <!-- Card Content with Loading Overlay -->
                <div class="position-relative p-0">
                    <!-- Loading Overlay -->
                    <div id="pointsLoadingOverlay" class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex align-items-center justify-content-center z-2" style="display: none !important;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('messages.loading') ?? 'Loading...' }}</span>
                        </div>
                    </div>

                    <!-- Dynamic Table Content -->
                    <div id="pointsDynamicContent">
                        @include('theme::profile.partials.history_list', [
                            'history' => $history,
                            'featureAvailable' => $featureAvailable ?? true,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dynamicContainer = document.getElementById('pointsDynamicContent');
    const loadingOverlay = document.getElementById('pointsLoadingOverlay');
    const positiveElem = document.getElementById('positivePointsTotal');
    const negativeElem = document.getElementById('negativePointsTotal');
    const totalCountElem = document.getElementById('pointsTotalCountNum');
    const netBalanceElem = document.getElementById('netPointsBalance');
    const searchInput = document.getElementById('ptsSearchInput');
    const searchClear = document.getElementById('ptsSearchClear');
    const filterChipsHolder = document.getElementById('ptsFilterChips');
    const exportBtn = document.getElementById('ptsExportBtn');

    let currentFilter = @json($currentFilter);
    let currentSearch = @json($currentSearch);
    let searchDebounceTimer = null;

    function showLoading() {
        if (loadingOverlay) loadingOverlay.style.setProperty('display', 'flex', 'important');
    }

    function hideLoading() {
        if (loadingOverlay) loadingOverlay.style.setProperty('display', 'none', 'important');
    }

    function updateExportUrl() {
        if (!exportBtn) return;
        const exportBase = @json(route('profile.history.export'));
        const params = new URLSearchParams();
        if (currentFilter && currentFilter !== 'all') params.set('filter', currentFilter);
        if (currentSearch && currentSearch.trim() !== '') params.set('search', currentSearch.trim());
        const qs = params.toString();
        exportBtn.href = exportBase + (qs ? '?' + qs : '');
    }

    function loadHistory(url, pushState = true) {
        showLoading();

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.html !== undefined && dynamicContainer) {
                dynamicContainer.innerHTML = data.html;
            }

            if (data.positive_total !== undefined && positiveElem) {
                positiveElem.innerHTML = '+' + data.positive_total + ' <small class="smallest opacity-75">PTS</small>';
            }

            if (data.negative_total !== undefined && negativeElem) {
                negativeElem.innerHTML = '-' + data.negative_total + ' <small class="smallest opacity-75">PTS</small>';
            }

            if (data.total_count !== undefined && totalCountElem) {
                totalCountElem.textContent = data.total_count;
            }

            if (data.positive_total !== undefined && data.negative_total !== undefined && netBalanceElem) {
                const pos = parseFloat(data.positive_total) || 0;
                const neg = parseFloat(data.negative_total) || 0;
                const net = pos - neg;
                const formatted = (net > 0 ? '+' : '') + (Math.round(net * 100) / 100);
                netBalanceElem.innerHTML = formatted + ' <small class="smallest opacity-75">PTS</small>';
                netBalanceElem.className = 'fw-black mb-0 ' + (net >= 0 ? 'text-success' : 'text-danger');
            }

            if (pushState) {
                window.history.pushState({ path: url }, '', url);
            }

            updateExportUrl();
        })
        .catch(err => {
            console.error('AJAX History error:', err);
            window.location.href = url;
        })
        .finally(() => {
            hideLoading();
        });
    }

    function triggerHistoryQuery(page = 1) {
        const base = @json(route('profile.history'));
        const params = new URLSearchParams();
        if (currentFilter && currentFilter !== 'all') params.set('filter', currentFilter);
        if (currentSearch && currentSearch.trim() !== '') params.set('search', currentSearch.trim());
        if (page > 1) params.set('page', page);

        const qs = params.toString();
        const url = base + (qs ? '?' + qs : '');
        loadHistory(url, true);
    }

    // Filter Chips Click
    if (filterChipsHolder) {
        filterChipsHolder.addEventListener('click', function (e) {
            const btn = e.target.closest('.pts-filter-chip');
            if (!btn) return;

            const selected = btn.getAttribute('data-filter') || 'all';
            if (selected === currentFilter) return;

            currentFilter = selected;
            filterChipsHolder.querySelectorAll('.pts-filter-chip').forEach(c => {
                c.classList.remove('btn-primary');
                c.classList.add('btn-outline-secondary');
            });
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-primary');

            triggerHistoryQuery(1);
        });
    }

    // Search Input with Debounce
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const val = this.value;
            currentSearch = val;

            if (searchClear) {
                searchClear.style.display = val.trim().length > 0 ? 'inline-block' : 'none';
            }

            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                triggerHistoryQuery(1);
            }, 350);
        });
    }

    // Search Clear Button
    if (searchClear) {
        searchClear.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                currentSearch = '';
                searchInput.focus();
            }
            searchClear.style.display = 'none';
            triggerHistoryQuery(1);
        });
    }

    // Delegate Pagination Click
    document.addEventListener('click', function (e) {
        const link = e.target.closest('#pointsPaginationHolder .page-link');
        if (!link) return;

        const href = link.getAttribute('href');
        if (href && href !== '#' && !link.closest('.disabled') && !link.closest('.active')) {
            e.preventDefault();
            loadHistory(href, true);
            if (dynamicContainer) {
                window.scrollTo({ top: dynamicContainer.offsetTop - 120, behavior: 'smooth' });
            }
        }
    });

    // Popstate browser navigation
    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.path) {
            loadHistory(e.state.path, false);
        } else {
            loadHistory(window.location.href, false);
        }
    });
});
</script>
@endpush
@endsection
