@php
    $status = $status ?? 'open';
    $bgClass = match($status) {
        'open' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
        'awarded' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
        'in_progress' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
        'delivered' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
        'completed' => 'bg-success text-white',
        'cancelled', 'closed' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
        default => 'bg-secondary bg-opacity-10 text-secondary border',
    };
@endphp
<span class="badge rounded-pill px-3 py-2 small fw-bold {{ $bgClass }} orders-status-pill status-{{ $status }}">
    {{ __('messages.order_status_' . $status) }}
</span>
