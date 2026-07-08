@php
    $isUnread = in_array($notification->state, [0, 3], true);
@endphp

<a href="{{ route('notifications.show', $notification->id) }}" class="list-group-item list-group-item-action border-0 px-4 py-3 {{ $isUnread ? 'bg-primary-subtle border-start border-4 border-primary' : '' }}">
    <div class="d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="fa {{ $notification->logo ?: 'fa-bell' }} fs-5"></i>
        </div>
        <div class="flex-grow-1 min-width-0">
            <p class="mb-0 small {{ $isUnread ? 'fw-bold text-dark' : 'text-muted' }}">
                {{ $notification->name }}
            </p>
            <small class="text-muted smaller">
                <i class="fa fa-clock me-1 opacity-50"></i> {{ \Carbon\Carbon::createFromTimestamp($notification->time)->diffForHumans() }}
            </small>
        </div>
        @if($isUnread)
            <span class="badge bg-primary p-1 rounded-circle ms-2" style="width: 8px; height: 8px; padding: 0 !important;"></span>
        @endif
    </div>
</a>
