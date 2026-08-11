@extends('theme::layouts.master')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    
    <div class="text-center mb-5">
        <h2 class="fw-black text-danger display-6 mb-2">
            <i class="fa fa-ban me-2"></i>{{ __('messages.block_user') ?? 'Block User' }}
        </h2>
        <p class="text-muted fs-5 mb-0">{{ __('messages.block_warning') ?? 'This action will prevent the user from interacting with you.' }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa fa-check-circle me-2"></i><strong>{{ session('success') }}</strong>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i><strong>{{ session('error') }}</strong>
        </div>
    @endif

    <div class="row g-4">
        <!-- USER PREVIEW CARD -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center h-100">
                <div class="ratio ratio-21x9 bg-secondary" style="background-image: url('{{ asset($user->cover_url ?? 'themes/default/assets/img/cover/04.jpg') }}'); background-size: cover; background-position: center;"></div>
                <div class="card-body position-relative pt-0 px-4 pb-4">
                    <div class="position-absolute top-0 start-50 translate-middle" style="margin-top: -10px;">
                        <img src="{{ $user->avatarUrl() }}" class="rounded-circle border border-4 border-white shadow-sm" width="100" height="100" alt="{{ $user->username }}" style="object-fit: cover;">
                    </div>
                    <div style="margin-top: 60px;">
                        <h4 class="fw-bold mb-1"><a href="{{ route('profile.show', $user->username) }}" class="text-dark text-decoration-none hover-primary">{{ $user->username }}</a></h4>
                        <p class="text-muted small mb-0">{{ $user->name ?? __('messages.member') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- BLOCK FORM -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="card-body d-flex flex-column h-100">
                    <form action="{{ route('profile.block.store', $user->username) }}" method="POST" class="d-flex flex-column h-100">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->username }}">
                        
                        <div class="mb-4">
                            <label for="block_type" class="form-label fw-bold">
                                <i class="fa fa-shield-alt text-primary me-2"></i>{{ __('messages.block_type') ?? 'Block Type' }}
                            </label>
                            <select id="block_type" name="block_type" class="form-select py-2 fw-semibold" required>
                                <option value="messages_only">{{ __('messages.block_messages_only') ?? 'Block Messages Only' }}</option>
                                <option value="full_platform">{{ __('messages.block_full_platform') ?? 'Full Platform Block' }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="duration" class="form-label fw-bold">
                                <i class="fa fa-calendar-alt text-primary me-2"></i>{{ __('messages.block_duration') ?? 'Duration (Days)' }}
                            </label>
                            <input type="number" id="duration" name="duration" class="form-control py-2 fw-semibold" placeholder="{{ __('messages.forever') ?? 'Forever (Leave empty)' }}" min="1">
                        </div>
                        
                        <div class="d-flex gap-3 mt-auto">
                            <a href="{{ route('profile.show', $user->username) }}" class="btn btn-outline-secondary w-100 py-3 fw-bold">{{ __('messages.cancel') ?? 'Cancel' }}</a>
                            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold" style="background: linear-gradient(135deg, #FF4B4B 0%, #D42828 100%); border: none; box-shadow: 0 10px 20px rgba(255, 75, 75, 0.3);">
                                <i class="fa fa-ban me-1"></i>{{ __('messages.block') ?? 'Block' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
