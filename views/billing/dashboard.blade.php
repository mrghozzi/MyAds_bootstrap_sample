@extends('theme::layouts.master')

@section('content')
@php
    $entitlementService = app(\App\Services\Billing\SubscriptionEntitlementService::class);
@endphp

<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-wallet fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.billing_dashboard_title') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.billing_dashboard_intro') }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-credit-card fa-10x"></i>
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

            @if($upgradeNotice)
                <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-4" role="alert">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fa fa-info-circle text-warning fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-1">{{ __('messages.notice') ?? 'Notice' }}</div>
                        <div class="small text-muted">{!! $upgradeNotice !!}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(!$systemEnabled)
                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-4" role="alert">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fa fa-exclamation-circle text-info fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-1">{{ __('messages.info') ?? 'Information' }}</div>
                        <div class="small text-muted">{{ __('messages.billing_system_disabled_member_notice') }}</div>
                    </div>
                </div>
            @endif

            <!-- Dashboard Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden">
                <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap gap-4">
                    <div class="flex-grow-1">
                        <h4 class="fw-black mb-2 text-dark">{{ __('messages.billing_dashboard_title') }}</h4>
                        <p class="text-muted small mb-0 fs-6">{{ __('messages.billing_dashboard_help') }}</p>
                    </div>
                    @if($systemEnabled)
                        <a href="{{ $plansUrl }}" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-crown me-2 text-warning"></i> {{ __('messages.billing_back_to_plans') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Subscriptions Grid -->
            <div class="row g-4 mb-4">
                <!-- Current Subscription -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden border border-light transition-all hover-translate-y">
                        <div class="card-header bg-white py-3 px-4 border-bottom">
                            <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.billing_current_subscription_title') }}</h6>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            @if($currentSubscription)
                                @php($currentBenefits = $entitlementService->memberBenefitLines((array) ($currentSubscription->entitlements_snapshot ?? [])))
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-black text-primary mb-0">{{ $currentSubscription->plan_name }}</h3>
                                    @include('theme::billing.partials.status_badge', ['status' => $currentSubscription->status])
                                </div>
                                <div class="mb-5 d-grid gap-2">
                                    <div class="smaller text-muted fw-bold d-flex align-items-center"><i class="fa fa-calendar-check me-3 text-primary opacity-50" style="width: 20px;"></i>{{ __('messages.billing_starts_at_label') }}: <span class="text-dark ms-2">{{ optional($currentSubscription->starts_at)->format('Y-m-d H:i') ?: '-' }}</span></div>
                                    <div class="smaller text-muted fw-bold d-flex align-items-center"><i class="fa fa-calendar-times me-3 text-danger opacity-50" style="width: 20px;"></i>{{ __('messages.billing_ends_at_label') }}: <span class="text-dark ms-2">{{ optional($currentSubscription->ends_at)->format('Y-m-d H:i') ?: __('messages.billing_lifetime') }}</span></div>
                                </div>
                                @if(!empty($currentBenefits))
                                    <div class="bg-light bg-opacity-50 rounded-4 p-4 border border-light">
                                        <h6 class="fw-black smaller text-uppercase text-muted letter-spacing-1 mb-3">{{ __('messages.billing_plan_benefits_title') }}</h6>
                                        <ul class="list-unstyled mb-0 d-grid gap-2">
                                            @foreach($currentBenefits as $benefit)
                                                <li class="smaller fw-bold text-dark"><i class="fa fa-check-circle text-success me-2"></i>{{ $benefit }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <div class="rounded-circle bg-light p-4 d-inline-flex mb-4 shadow-sm">
                                        <i class="fa fa-box-open fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <h5 class="fw-black text-muted mb-0">{{ __('messages.billing_no_active_subscription') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Queued Subscription -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden border border-light transition-all hover-translate-y">
                        <div class="card-header bg-white py-3 px-4 border-bottom">
                            <h6 class="fw-black mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.billing_queued_subscription_title') }}</h6>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            @if($queuedSubscription)
                                @php($queuedBenefits = $entitlementService->memberBenefitLines((array) ($queuedSubscription->entitlements_snapshot ?? [])))
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h3 class="fw-black text-info mb-0">{{ $queuedSubscription->plan_name }}</h3>
                                    @include('theme::billing.partials.status_badge', ['status' => $queuedSubscription->status])
                                </div>
                                <div class="mb-5 d-grid gap-2">
                                    <div class="smaller text-muted fw-bold d-flex align-items-center"><i class="fa fa-clock me-3 text-info opacity-50" style="width: 20px;"></i>{{ __('messages.billing_starts_at_label') }}: <span class="text-dark ms-2">{{ optional($queuedSubscription->starts_at)->format('Y-m-d H:i') ?: '-' }}</span></div>
                                    <div class="smaller text-muted fw-bold d-flex align-items-center"><i class="fa fa-calendar-times me-3 text-info opacity-50" style="width: 20px;"></i>{{ __('messages.billing_ends_at_label') }}: <span class="text-dark ms-2">{{ optional($queuedSubscription->ends_at)->format('Y-m-d H:i') ?: __('messages.billing_lifetime') }}</span></div>
                                </div>
                                @if(!empty($queuedBenefits))
                                    <div class="bg-light bg-opacity-50 rounded-4 p-4 border border-light">
                                        <h6 class="fw-black smaller text-uppercase text-muted letter-spacing-1 mb-3">{{ __('messages.billing_plan_benefits_title') }}</h6>
                                        <ul class="list-unstyled mb-0 d-grid gap-2">
                                            @foreach($queuedBenefits as $benefit)
                                                <li class="smaller fw-bold text-dark"><i class="fa fa-check-circle text-info me-2"></i>{{ $benefit }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <div class="rounded-circle bg-light p-4 d-inline-flex mb-4 shadow-sm">
                                        <i class="fa fa-history fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <h5 class="fw-black text-muted mb-0">{{ __('messages.billing_no_queued_subscription') }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.billing_orders_title') }}</h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2 fw-black smaller">{{ $orders->total() }} {{ __('messages.orders') ?? 'Orders' }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.billing_order_number_label') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.plan') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.amount') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.status') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.date') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1 text-end">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="transition-all hover-bg-light">
                                    <td class="px-4 smaller text-muted fw-black">#{{ $order->order_number }}</td>
                                    <td class="px-4">
                                        <span class="fw-black text-dark">{{ data_get($order->plan_snapshot, 'name', __('messages.billing_subscription_plan')) }}</span>
                                    </td>
                                    <td class="px-4">
                                        <span class="fw-black text-primary">{{ number_format((float) $order->display_amount, 2) }} {{ $order->currency_code }}</span>
                                    </td>
                                    <td class="px-4">@include('theme::billing.partials.status_badge', ['status' => $order->status])</td>
                                    <td class="px-4 smaller text-muted fw-bold">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 text-end">
                                        <a href="{{ route('billing.orders.show', $order->id) }}" class="btn btn-light btn-sm rounded-pill px-4 fw-black border shadow-sm transition-all hover-translate-y">{{ __('messages.view') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-5 text-center bg-light bg-opacity-25">
                                        <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-3 border border-light">
                                            <i class="fa fa-receipt fa-2x text-muted opacity-25"></i>
                                        </div>
                                        <p class="mb-0 fw-black text-muted">{{ __('messages.no_data') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="p-4 border-top bg-white d-flex justify-content-center">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

            <!-- Transactions Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.billing_transactions_title') }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.date') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.billing_transaction_type_label') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.amount') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.status') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.billing_external_reference_label') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr class="transition-all hover-bg-light">
                                    <td class="px-4 smaller text-muted fw-bold">{{ optional($transaction->processed_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-4">
                                        <span class="smaller fw-black text-dark">{{ $transaction->transactionTypeLabel() }}</span>
                                    </td>
                                    <td class="px-4 fw-black text-primary">{{ number_format((float) $transaction->amount, 2) }} {{ $transaction->currency_code }}</td>
                                    <td class="px-4">@include('theme::billing.partials.status_badge', ['status' => $transaction->status])</td>
                                    <td class="px-4 smaller text-muted fw-bold">{{ $transaction->external_transaction_id ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-5 text-center bg-light bg-opacity-25">
                                        <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-3 border border-light">
                                            <i class="fa fa-history fa-2x text-muted opacity-25"></i>
                                        </div>
                                        <p class="mb-0 fw-black text-muted">{{ __('messages.no_data') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                    <div class="p-4 border-top bg-white d-flex justify-content-center">
                        {{ $transactions->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .hover-bg-light:hover { background-color: #f8f9fa !important; }
    .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2) !important; }
</style>
@endsection
