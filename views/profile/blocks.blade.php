@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-danger bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-ban fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.blocked_users') ?? 'Blocked Users' }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.manage_blocked_users_desc') ?? 'Manage members you have blocked from messaging or interacting with you.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-user-slash fa-10x"></i>
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
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-4" role="alert">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fa fa-check-circle text-success fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-1">{{ __('messages.success') ?? 'Success' }}</div>
                        <div class="small text-muted fw-bold">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center p-4" role="alert">
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="fa fa-times-circle text-danger fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-black text-dark smaller text-uppercase letter-spacing-1 mb-1">{{ __('messages.error') ?? 'Error' }}</div>
                        <div class="small text-muted fw-bold">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4 border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.blocked_users') ?? 'Blocked Users' }}</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if($blocks->isEmpty())
                        <div class="p-5 text-center bg-light bg-opacity-25 rounded-4 border border-light">
                            <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-4 border border-light">
                                <i class="fa fa-user-check fa-3x text-muted opacity-25"></i>
                            </div>
                            <h4 class="fw-black text-dark">{{ __('messages.no_blocked_users') ?? 'No blocked users' }}</h4>
                            <p class="text-muted small mb-0 fw-bold">{{ __('messages.blocked_users_empty_desc') ?? 'Members you block will be listed here.' }}</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-6 letter-spacing-1">
                                    <tr>
                                        <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.user') ?? 'User' }}</th>
                                        <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.block_type') ?? 'Type' }}</th>
                                        <th class="border-0 px-4 py-3 fw-black text-muted">{{ __('messages.block_expires') ?? 'Expires At' }}</th>
                                        <th class="border-0 px-4 py-3 fw-black text-muted text-end">{{ __('messages.actions') ?? 'Actions' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blocks as $block)
                                        <tr>
                                            <td class="px-4 py-3 border-light">
                                                @if($block->blockedUser)
                                                    <a href="{{ route('profile.show', $block->blockedUser->username) }}" class="text-decoration-none fw-bold text-dark d-flex align-items-center gap-2" target="_blank">
                                                        <div class="bg-light rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                            <i class="fa fa-user text-secondary smaller"></i>
                                                        </div>
                                                        {{ $block->blockedUser->username }}
                                                    </a>
                                                @else
                                                    <span class="text-muted fw-bold">{{ __('messages.deleted_user') ?? 'Deleted User' }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 border-light">
                                                @if($block->block_type === 'messages_only')
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 text-uppercase border border-secondary border-opacity-10">{{ __('messages.block_messages_only') ?? 'Messages Only' }}</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 smaller fw-black letter-spacing-1 text-uppercase border border-danger border-opacity-10">{{ __('messages.block_full_platform') ?? 'Full Platform' }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 border-light text-muted small fw-bold">
                                                @if($block->expires_at)
                                                    {{ $block->expires_at->diffForHumans() }} <span class="text-opacity-50">({{ $block->expires_at->format('Y-m-d') }})</span>
                                                @else
                                                    {{ __('messages.forever') ?? 'Forever' }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 border-light text-end">
                                                <form action="{{ route('profile.block.destroy', $block->blocked_user_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2 fw-black shadow-sm transition-all hover-translate-y" onclick="return confirm('{{ __('messages.confirm_unblock') ?? 'Are you sure you want to unblock this user?' }}')">
                                                        <i class="fa fa-user-check me-1"></i>
                                                        {{ __('messages.unblock') ?? 'Unblock' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
