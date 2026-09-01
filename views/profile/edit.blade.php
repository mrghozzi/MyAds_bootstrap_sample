@extends('theme::layouts.master')

@push('head')
<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .avatar-wrapper {
        cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .avatar-wrapper:hover {
        transform: scale(1.04);
    }
    .avatar-camera-badge {
        position: absolute;
        bottom: 4px;
        inset-inline-end: 4px;
        width: 32px;
        height: 32px;
        background: var(--msg-primary, #615dfa);
        color: #ffffff;
        border: 2px solid #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .avatar-wrapper:hover .avatar-camera-badge {
        transform: scale(1.15);
        background: #524eee;
    }
    .cover-upload-btn {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        color: #2f3148;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    [data-theme="css_d"] .cover-upload-btn,
    [data-bs-theme="dark"] .cover-upload-btn {
        background: rgba(31, 38, 56, 0.85);
        color: #f4f7ff;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .cover-upload-btn:hover {
        background: #ffffff;
        color: var(--msg-primary, #615dfa);
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
@php
    $coverOption = \App\Models\Option::where('o_type', 'user')->where('o_order', $user->id)->first();
    $cover = $coverOption && $coverOption->o_mode != '0' ? $coverOption->o_mode : 'upload/cover.jpg';
@endphp

<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-5 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-user-edit fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.edit_profile') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.update_account_info') }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-user-circle fa-10x"></i>
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
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.account_details') }}</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @include('theme::billing.partials.alerts')

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-edit-form">
                        @csrf
                        
                        <!-- Visual Identity Section -->
                        <div class="mb-5">
                            <h6 class="fw-black text-muted text-uppercase smaller letter-spacing-1 mb-4">{{ __('messages.visual_identity') ?? 'Visual Identity' }}</h6>
                            
                            <div class="position-relative rounded-4 overflow-hidden shadow-sm border border-light mb-4 transition-all" style="height: 250px;">
                                <!-- Cover Image Display -->
                                <div id="cover-display" class="w-100 h-100 transition-all cursor-pointer" title="{{ __('messages.cover') }}" style="background: url({{ asset($cover) }}) center center / cover no-repeat;">
                                    <div class="w-100 h-100 bg-dark bg-opacity-10"></div>
                                </div>
                                
                                <!-- Cover Upload Button -->
                                <div class="position-absolute top-0 end-0 p-4">
                                    <button type="button" id="CoverUpload" class="btn cover-upload-btn btn-sm rounded-pill px-4 py-2 fw-black shadow-lg transition-all hover-translate-y">
                                        <i class="fa fa-camera me-2 text-primary"></i> {{ __('messages.cover') }}
                                    </button>
                                </div>

                                <!-- Avatar Upload Overlay -->
                                <div class="position-absolute bottom-0 start-0 p-4 mb-2">
                                    <div class="position-relative d-inline-block avatar-wrapper" id="AvatarUploadWrapper" title="{{ __('messages.avatar') ?? 'Change Avatar' }}">
                                        <div class="rounded-circle border border-5 border-white shadow-lg overflow-hidden position-relative" style="width: 120px; height: 120px;">
                                            <img id="avatar-display" src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}" class="w-100 h-100" style="object-fit: cover;">
                                            <div id="AvatarUpload" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-40 d-flex align-items-center justify-content-center opacity-0 hover-opacity-100 cursor-pointer transition-all">
                                                <i class="fa fa-camera text-white fs-4"></i>
                                            </div>
                                        </div>
                                        <div class="avatar-camera-badge" id="AvatarBadge">
                                            <i class="fa fa-camera"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Status Notification -->
                            <div id="upload-feedback" class="alert alert-info border-0 rounded-4 p-3 d-none align-items-center gap-2 mb-3 shadow-sm">
                                <i class="fa fa-circle-check text-info fs-5"></i>
                                <span class="smaller fw-bold" id="upload-feedback-text"></span>
                            </div>

                            <div class="alert alert-light border-0 rounded-4 p-3 d-flex align-items-center mb-0">
                                <i class="fa fa-info-circle text-primary me-2"></i>
                                <span class="smaller fw-bold text-muted">{{ __('messages.upload_reccomendation') ?? 'JPG, PNG or GIF. Max 2MB recommended for best performance.' }}</span>
                            </div>
                        </div>

                        <!-- Hidden File Inputs -->
                        <input type="file" id="Avatarload" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" class="d-none">
                        <input type="file" id="Coverload" name="cover" accept="image/jpeg,image/png,image/gif,image/webp" class="d-none">

                        <!-- Basic Info Section -->
                        <div class="mb-5">
                            <h6 class="fw-black text-muted text-uppercase smaller letter-spacing-1 mb-4">{{ __('messages.basic_information') ?? 'Basic Information' }}</h6>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="username" class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted">{{ __('messages.username') }}</label>
                                    <div class="input-group bg-light rounded-4 border border-light p-1">
                                        <span class="input-group-text bg-transparent border-0"><i class="fa fa-at text-primary opacity-50"></i></span>
                                        <input type="text" id="username" class="form-control bg-transparent border-0 px-2 fw-bold" value="{{ $user->username }}" disabled>
                                    </div>
                                    <div class="smallest text-muted mt-2 ps-2 fw-bold">
                                        <i class="fa fa-lock me-1 opacity-50"></i> {{ __('messages.username_cannot_be_changed') ?? 'Username is locked and cannot be changed.' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted">{{ __('messages.email') }}</label>
                                    <div class="input-group bg-light rounded-4 border border-light p-1">
                                        <span class="input-group-text bg-transparent border-0"><i class="fa fa-envelope text-primary opacity-50"></i></span>
                                        <input type="email" id="email" name="email" class="form-control bg-transparent border-0 px-2 fw-bold" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="mb-5 bg-light bg-opacity-50 p-4 p-md-5 rounded-4 border border-light">
                            <h6 class="fw-black text-muted text-uppercase smaller letter-spacing-1 mb-3">
                                <i class="fa fa-shield-halved me-2 text-primary"></i>{{ __('messages.security') }}
                            </h6>
                            <p class="text-muted smaller fw-bold mb-4">{{ __('messages.leave_blank_to_keep') }}</p>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted">{{ __('messages.new_password') }}</label>
                                    <input type="password" id="password" name="password" class="form-control bg-white rounded-pill px-4 py-3 border border-light shadow-sm fw-bold" autocomplete="new-password">
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted">{{ __('messages.confirm_password') }}</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control bg-white rounded-pill px-4 py-3 border border-light shadow-sm fw-bold" autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <!-- Bio Section -->
                        <div class="mb-5">
                            <h6 class="fw-black text-muted text-uppercase smaller letter-spacing-1 mb-4">
                                <i class="fa fa-pencil-alt me-2 text-primary"></i>{{ __('messages.about_me') }}
                            </h6>
                            <textarea id="about_me" name="about_me" class="form-control bg-light bg-opacity-50 rounded-4 p-4 border border-light shadow-none fw-bold" rows="6" placeholder="{{ __('messages.about_me_placeholder') }}">{{ old('about_me', $user->sig) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-center gap-3 pt-5 border-top">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg transition-all hover-translate-y">
                                <i class="fa fa-check-circle me-2"></i> {{ __('messages.save_changes') }}
                            </button>
                        </div>
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
        const avatarWrapper = document.getElementById('AvatarUploadWrapper');
        const avatarOverlay = document.getElementById('AvatarUpload');
        const avatarBadge = document.getElementById('AvatarBadge');
        const avatarInput = document.getElementById('Avatarload');
        const avatarDisplay = document.getElementById('avatar-display');

        const coverButton = document.getElementById('CoverUpload');
        const coverDisplay = document.getElementById('cover-display');
        const coverInput = document.getElementById('Coverload');

        const feedbackBox = document.getElementById('upload-feedback');
        const feedbackText = document.getElementById('upload-feedback-text');

        function showFeedback(message) {
            if (feedbackBox && feedbackText) {
                feedbackText.textContent = message;
                feedbackBox.classList.remove('d-none');
                feedbackBox.classList.add('d-flex');
            }
        }

        // Trigger Avatar File Input Click
        function triggerAvatarUpload(e) {
            if (e) e.preventDefault();
            if (avatarInput) avatarInput.click();
        }

        if (avatarWrapper) avatarWrapper.addEventListener('click', triggerAvatarUpload);
        if (avatarOverlay) avatarOverlay.addEventListener('click', triggerAvatarUpload);
        if (avatarBadge) avatarBadge.addEventListener('click', triggerAvatarUpload);

        // Trigger Cover File Input Click
        function triggerCoverUpload(e) {
            if (e) e.preventDefault();
            if (coverInput) coverInput.click();
        }

        if (coverButton) coverButton.addEventListener('click', triggerCoverUpload);

        // Handle Avatar File Selection & Live Preview
        if (avatarInput) {
            avatarInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    if (file.size > 5242880) { // 5MB limit
                        alert('Avatar file is too large. Please select an image under 5MB.');
                        this.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (avatarDisplay) {
                            avatarDisplay.src = e.target.result;
                        }
                        showFeedback('Selected new avatar: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB). Click "Save Changes" below to apply.');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Handle Cover File Selection & Live Preview
        if (coverInput) {
            coverInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    if (file.size > 8388608) { // 8MB limit
                        alert('Cover file is too large. Please select an image under 8MB.');
                        this.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (coverDisplay) {
                            coverDisplay.style.background = 'url(' + e.target.result + ') center center / cover no-repeat';
                        }
                        showFeedback('Selected new cover image: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB). Click "Save Changes" below to apply.');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
