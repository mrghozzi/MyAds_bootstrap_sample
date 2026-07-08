@php
    $status = (string) ($status ?? 'draft');
    $statusIcons = [
        'active' => 'fa-circle-check',
        'draft' => 'fa-pen-to-square',
        'pending_review' => 'fa-clock',
        'rejected' => 'fa-circle-xmark',
        'suspended' => 'fa-ban',
    ];
@endphp

@php
    $statusClasses = [
        'active' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
        'draft' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25',
        'pending_review' => 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25',
        'rejected' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
        'suspended' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25',
    ];
    $badgeClass = $statusClasses[$status] ?? 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25';
@endphp

<span class="badge rounded-pill {{ $badgeClass }} px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
    <i class="fa {{ $statusIcons[$status] ?? 'fa-circle-info' }}"></i>
    <span>{{ __('messages.app_status_' . $status) }}</span>
</span>
