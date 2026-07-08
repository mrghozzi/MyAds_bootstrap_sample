@extends('theme::layouts.master')

@section('content')
@php
    $canUploadReceipt = $order->gateway === 'bank_transfer' && in_array($order->status, ['pending_receipt', 'rejected'], true);
    $systemEnabled = \App\Support\SubscriptionSettings::isEnabled();
    $user = auth()->user();
@endphp

<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-file-invoice-dollar fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.billing_order_details_member_title') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ $order->order_number }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-receipt fa-10x"></i>
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

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden transition-all hover-translate-y">
                <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="fw-black mb-1 text-dark">{{ __('messages.billing_order_summary_title') }}</h5>
                        <p class="text-muted smaller fw-black text-uppercase letter-spacing-1 mb-0">{{ $order->order_number }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        @include('theme::billing.partials.status_badge', ['status' => $order->status])
                        @if($systemEnabled)
                            <a href="{{ route('billing.plans') }}" class="btn btn-primary rounded-pill px-4 fw-black shadow-sm transition-all hover-translate-y">
                                <i class="fa fa-crown me-2 text-warning"></i> {{ __('messages.billing_back_to_plans') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="p-3 bg-light bg-opacity-50 rounded-4 border border-light text-center h-100">
                                <div class="smaller fw-black text-muted text-uppercase letter-spacing-1 mb-2">{{ __('messages.plan') }}</div>
                                <div class="fw-black text-dark text-truncate">{{ data_get($order->plan_snapshot, 'name', __('messages.billing_subscription_plan')) }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-3 bg-light bg-opacity-50 rounded-4 border border-light text-center h-100">
                                <div class="smaller fw-black text-muted text-uppercase letter-spacing-1 mb-2">{{ __('messages.gateway') }}</div>
                                <div class="fw-black text-dark text-truncate">{{ data_get($order->meta, 'gateway_label', $order->gatewayLabel()) }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-3 bg-light bg-opacity-50 rounded-4 border border-light text-center h-100">
                                <div class="smaller fw-black text-muted text-uppercase letter-spacing-1 mb-2">{{ __('messages.amount') }}</div>
                                <div class="fw-black text-primary">{{ number_format((float) $order->display_amount, 2) }} {{ $order->currency_code }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="p-3 bg-light bg-opacity-50 rounded-4 border border-light text-center h-100">
                                <div class="smaller fw-black text-muted text-uppercase letter-spacing-1 mb-2">{{ __('messages.date') }}</div>
                                <div class="fw-black text-dark smaller">{{ optional($order->created_at)->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->gateway === 'bank_transfer')
                <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden transition-all hover-translate-y">
                    <div class="card-header bg-white py-4 px-4 border-bottom">
                        <h5 class="fw-black mb-0 text-dark">{{ __('messages.billing_bank_transfer_instructions_title') }}</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        @if(!empty($bankTransferConfig['instructions']))
                            <div class="alert alert-info border-0 shadow-sm rounded-4 p-4 mb-4" role="alert">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                                        <i class="fa fa-info-circle text-info"></i>
                                    </div>
                                    <h6 class="fw-black text-dark mb-0 text-uppercase letter-spacing-1 smaller">{{ __('messages.instructions') ?? 'Instructions' }}</h6>
                                </div>
                                <div class="small text-muted fw-bold lh-lg">{!! nl2br(e((string) $bankTransferConfig['instructions'])) !!}</div>
                            </div>
                        @endif
                        @if(!empty($bankTransferConfig['note']))
                            <div class="bg-light bg-opacity-50 rounded-4 p-4 border border-light">
                                <div class="d-flex align-items-center mb-2 text-muted fw-black smaller text-uppercase letter-spacing-1">
                                    <i class="fa fa-sticky-note me-2"></i> {{ __('messages.note') ?? 'Note' }}
                                </div>
                                <div class="small text-muted fw-bold">{!! nl2br(e((string) $bankTransferConfig['note'])) !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($order->admin_note)
                <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden transition-all hover-translate-y">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="fa fa-comment-dots text-primary"></i>
                            </div>
                            <h5 class="fw-black text-dark mb-0">{{ __('messages.billing_admin_note_label') }}</h5>
                        </div>
                        <p class="small text-muted fw-bold mb-0 lh-lg">{{ $order->admin_note }}</p>
                    </div>
                </div>
            @endif

            @php($receiptUrl = $order->receiptUrl())

            @if($receiptUrl)
                <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden transition-all hover-translate-y">
                    <div class="card-header bg-white py-4 px-4 border-bottom">
                        <h5 class="fw-black mb-0 text-dark">{{ __('messages.billing_receipt_current_title') }}</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="position-relative mb-4 overflow-hidden rounded-4 border border-light shadow-sm">
                            <img src="{{ $receiptUrl }}" alt="{{ __('messages.billing_receipt_title') }}" class="img-fluid transition-all hover-scale">
                        </div>
                        @if($order->receipt_note)
                            <div class="bg-light bg-opacity-50 rounded-4 p-4 border border-light">
                                <div class="fw-black smaller text-muted text-uppercase letter-spacing-1 mb-2">{{ __('messages.billing_receipt_note_label') }}</div>
                                <p class="small text-dark fw-bold mb-0">{{ $order->receipt_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($canUploadReceipt)
                <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light overflow-hidden transition-all hover-translate-y">
                    <div class="card-header bg-white py-4 px-4 border-bottom">
                        <h5 class="fw-black mb-0 text-dark">{{ __('messages.billing_upload_receipt_title') }}</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('billing.orders.receipt.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted">{{ __('messages.billing_receipt_title') }}</label>
                                <div class="bg-light p-4 rounded-4 border border-dashed text-center position-relative transition-all hover-bg-light">
                                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.webp" required class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" id="receipt-upload">
                                    <div class="upload-placeholder">
                                        <i class="fa fa-cloud-upload-alt fa-3x text-muted opacity-25 mb-3"></i>
                                        <p class="small fw-black text-muted mb-0" id="file-name-display">{{ __('messages.click_to_upload') ?? 'Click to upload or drag & drop' }}</p>
                                        <p class="smallest text-muted opacity-50 mt-1">JPG, PNG, WEBP (Max 5MB)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted">{{ __('messages.billing_receipt_note_label') }}</label>
                                <textarea name="receipt_note" class="form-control bg-light border-0 rounded-4 p-3 shadow-none fw-bold" rows="4" placeholder="{{ __('messages.billing_receipt_note_placeholder') }}">{{ old('receipt_note', $order->receipt_note) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg transition-all hover-translate-y">
                                <i class="fa fa-upload me-2"></i> {{ __('messages.billing_upload_receipt_cta') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Transaction Log -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.billing_transaction_log_title') }}</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.date') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.billing_transaction_type_label') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.status') }}</th>
                                <th class="px-4 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->transactions as $transaction)
                                <tr class="transition-all hover-bg-light">
                                    <td class="px-4 smaller text-muted fw-bold">{{ optional($transaction->processed_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-4">
                                        <span class="smaller fw-black text-dark">{{ $transaction->transactionTypeLabel() }}</span>
                                    </td>
                                    <td class="px-4">@include('theme::billing.partials.status_badge', ['status' => $transaction->status])</td>
                                    <td class="px-4 fw-black text-primary">{{ number_format((float) $transaction->amount, 2) }} {{ $transaction->currency_code }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-5 text-center bg-light bg-opacity-25">
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
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.8rem; }
    .smallest { font-size: 0.7rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .hover-scale:hover { transform: scale(1.02); }
    .hover-bg-light:hover { background-color: #f8f9fa !important; }
    .bg-opacity-20 { background-color: rgba(255, 255, 255, 0.2) !important; }
    .cursor-pointer { cursor: pointer; }
    .border-dashed { border: 2px dashed #dee2e6 !important; }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('receipt-upload');
        const fileNameDisplay = document.getElementById('file-name-display');
        if (fileInput && fileNameDisplay) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    fileNameDisplay.textContent = this.files[0].name;
                    fileNameDisplay.classList.add('text-primary');
                }
            });
        }
    });
</script>
@endpush
@endsection
@endsection
