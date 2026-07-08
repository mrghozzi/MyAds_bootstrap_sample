@extends('theme::layouts.master')
@section('content')
@php
    $positiveTotal = collect($history->items())->sum(fn ($item) => max(0, (float) $item->amount));
    $negativeTotal = collect($history->items())->sum(fn ($item) => min(0, (float) $item->amount));
@endphp

<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-history fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.pts_history') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.pts_history_desc') ?? 'Track your points earnings and expenditures.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-coins fa-10x"></i>
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

            <!-- Summary Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-success h-100 transition-all hover-translate-y">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3 border border-success border-opacity-10 shadow-sm">
                                <i class="fa fa-arrow-up text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-black text-uppercase smaller text-muted letter-spacing-1 mb-1">{{ __('messages.pts_earned') ?? 'Earned' }}</h6>
                                <h2 class="fw-black text-success mb-0">+{{ rtrim(rtrim(number_format($positiveTotal, 2), '0'), '.') }} <small class="smallest opacity-75">PTS</small></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-danger h-100 transition-all hover-translate-y">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-4 me-3 border border-danger border-opacity-10 shadow-sm">
                                <i class="fa fa-arrow-down text-danger fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-black text-uppercase smaller text-muted letter-spacing-1 mb-1">{{ __('messages.pts_spent') ?? 'Spent' }}</h6>
                                <h2 class="fw-black text-danger mb-0">{{ rtrim(rtrim(number_format($negativeTotal, 2), '0'), '.') }} <small class="smallest opacity-75">PTS</small></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.pts_history') }}</h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2 fw-black smaller">{{ $history->total() }} {{ __('messages.records') ?? 'Records' }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">#{{ __('messages.id') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.name') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.date') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1 text-end">{{ __('messages.pts') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $item)
                                @php
                                    $description = __('messages.' . $item->description_key);
                                    $description = $description !== 'messages.' . $item->description_key ? $description : $item->description_key;
                                    $amount = (float) $item->amount;
                                @endphp
                                <tr class="transition-all hover-bg-light">
                                    <td class="px-4 smaller text-muted fw-black">#{{ $item->id }}</td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 d-flex align-items-center justify-content-center border border-primary border-opacity-10 shadow-sm" style="width: 38px; height: 38px;">
                                                <i class="fa fa-bolt text-primary smaller"></i>
                                            </div>
                                            <div>
                                                <div class="fw-black text-dark fs-6">{{ $description }}</div>
                                                @if($item->is_legacy)
                                                    <span class="badge bg-light text-muted border border-light rounded-pill smallest fw-bold px-2">{{ __('messages.legacy_points') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 smaller text-muted fw-bold">
                                        {{ \Carbon\Carbon::createFromTimestamp($item->created_at_ts)->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-4 text-end fw-black {{ $amount >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $amount > 0 ? '+' : '' }}{{ rtrim(rtrim(number_format($amount, 2), '0'), '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-5 text-center bg-light bg-opacity-25">
                                        <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-3 border border-light">
                                            <i class="fa fa-history fa-2x text-muted opacity-25"></i>
                                        </div>
                                        <h5 class="fw-black text-dark">{{ ($featureAvailable ?? true) ? __('messages.no_history') : __('messages.upgrade_legacy_mode_notice') }}</h5>
                                        <p class="text-muted small mb-0">{{ __('messages.pts_history_empty_desc') ?? 'Activity involving your points will appear here.' }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($history->hasPages())
                    <div class="p-4 border-top bg-white d-flex justify-content-center">
                        {{ $history->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
