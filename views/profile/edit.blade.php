@extends('theme::layouts.master')
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

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Visual Identity Section -->
                        <div class="mb-5">
                            <h6 class="fw-black text-muted text-uppercase smaller letter-spacing-1 mb-4">{{ __('messages.visual_identity') ?? 'Visual Identity' }}</h6>
                            
                            <div class="position-relative rounded-4 overflow-hidden shadow-sm border border-light mb-4 transition-all" style="height: 250px;">
                                <!-- Cover Image -->
                                <div id="cover-display" class="w-100 h-100 transition-all" style="background: url({{ asset($cover) }}) center center / cover no-repeat;">
                                    <div class="w-100 h-100 bg-dark bg-opacity-10"></div>
                                </div>
                                
                                <!-- Cover Upload Overlay -->
                                <div class="position-absolute top-0 end-0 p-4">
                                    <button type="button" id="CoverUpload" class="btn btn-white btn-sm rounded-pill px-4 py-2 fw-black shadow-lg border-0 transition-all hover-translate-y">
                                        <i class="fa fa-camera me-2 text-primary"></i> {{ __('messages.cover') }}
                                    </button>
                                </div>

                                <!-- Avatar Upload Overlay -->
                                <div class="position-absolute bottom-0 start-0 p-4 mb-2">
                                    <div class="position-relative d-inline-block">
                                        <div class="rounded-circle border border-5 border-white shadow-lg overflow-hidden position-relative hover-scale transition-all" style="width: 120px; height: 120px;">
                                            <img id="avatar-display" src="{{ $user->avatarUrl() }}" class="w-100 h-100" style="object-fit: cover;">
                                            <div id="AvatarUpload" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-40 d-flex align-items-center justify-content-center opacity-0 hover-opacity-100 cursor-pointer transition-all">
                                                <i class="fa fa-camera text-white fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-light border-0 rounded-4 p-3 d-flex align-items-center mb-0">
                                <i class="fa fa-info-circle text-primary me-2"></i>
                                <span class="smaller fw-bold text-muted">{{ __('messages.upload_reccomendation') ?? 'JPG, PNG or GIF. Max 2MB recommended for best performance.' }}</span>
                            </div>
                        </div>

                        <input type="file" id="Avatarload" name="avatar" accept=".jpg, .jpeg, .png, .gif" class="d-none">
                        <input type="file" id="Coverload" name="cover" accept=".jpg, .jpeg, .png, .gif" class="d-none">

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
