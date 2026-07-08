@extends('theme::layouts.master')

@section('title', __('messages.sign_up'))

@section('content')
<div class="auth-page-wrapper py-5 min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #f5f7ff 0%, #e8ebf5 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5">
                            <div class="bg-success bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                                <i class="fa fa-user-plus fa-3x text-success"></i>
                            </div>
                            <h2 class="fw-bold text-dark">{{ __('messages.creayoacc') }}</h2>
                            <p class="text-muted">{{ __('messages.join_community') }}</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="email" class="form-label small fw-bold text-muted text-uppercase mb-2">{{ __('messages.email') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fa fa-envelope text-muted opacity-50"></i></span>
                                        <input type="email" class="form-control bg-light border-0" id="email" name="email" value="{{ old('email') }}" required placeholder="example@mail.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="username" class="form-label small fw-bold text-muted text-uppercase mb-2">{{ __('messages.username') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fa fa-user text-muted opacity-50"></i></span>
                                        <input type="text" class="form-control bg-light border-0" id="username" name="username" value="{{ old('username') }}" required placeholder="Username">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label small fw-bold text-muted text-uppercase mb-2">{{ __('messages.password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fa fa-lock text-muted opacity-50"></i></span>
                                        <input type="password" class="form-control bg-light border-0" id="password" name="password" required placeholder="••••••••">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label small fw-bold text-muted text-uppercase mb-2">{{ __('messages.rep_password') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fa fa-shield-alt text-muted opacity-50"></i></span>
                                        <input type="password" class="form-control bg-light border-0" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <label for="capt" class="form-label small fw-bold text-muted text-uppercase mb-2">{{ __('messages.verification_code') }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text p-0 bg-light border-0 overflow-hidden">
                                            <img src="{{ route('captcha.generate') }}" id="captcha-img" class="h-100" style="cursor: pointer; min-width: 100px; max-height: 46px;" title="Click to refresh" onclick="document.getElementById('captcha-img').src='{{ route('captcha.generate') }}?'+Math.random()">
                                        </span>
                                        <input type="text" class="form-control bg-light border-0 py-2" id="capt" name="capt" required placeholder="Type code here">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check custom-check">
                                        <input type="checkbox" class="form-check-input" id="agree-terms" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }} required>
                                        <label class="form-check-label small text-muted" for="agree-terms">
                                            {{ __('messages.agree_terms') }}
                                            <a href="{{ route('privacy') }}" target="_blank" class="text-primary fw-bold text-decoration-none">{{ __('messages.privacy_policy') }}</a>
                                            {{ __('messages.and') }}
                                            <a href="{{ route('terms') }}" target="_blank" class="text-primary fw-bold text-decoration-none">{{ __('messages.terms_conditions') }}</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm rounded-pill py-3" name="submit">
                                            {{ __('messages.sign_up') }} <i class="fa fa-user-plus ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        @if(env('FACEBOOK_CLIENT_ID') || env('GOOGLE_CLIENT_ID') || env('ADSTN_CLIENT_ID'))
                            <div class="position-relative my-5">
                                <hr class="text-muted">
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small text-uppercase fw-bold">{{ __('messages.login_with_social') }}</span>
                            </div>
                            <div class="row g-3">
                                @if(env('GOOGLE_CLIENT_ID'))
                                    <div class="col-md-4">
                                        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-outline-danger w-100 rounded-pill d-flex align-items-center justify-content-center py-2 border-2 transition-all">
                                            <i class="fa-brands fa-google me-2"></i> Google
                                        </a>
                                    </div>
                                @endif
                                @if(env('FACEBOOK_CLIENT_ID'))
                                    <div class="col-md-4">
                                        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-outline-primary w-100 rounded-pill d-flex align-items-center justify-content-center py-2 border-2 transition-all">
                                            <i class="fa-brands fa-facebook me-2"></i> Facebook
                                        </a>
                                    </div>
                                @endif
                                @if(env('ADSTN_CLIENT_ID'))
                                    <div class="col-md-4">
                                        <a href="{{ route('social.redirect', 'adstn') }}" class="btn btn-outline-secondary w-100 rounded-pill d-flex align-items-center justify-content-center py-2 border-2 transition-all">
                                            <i class="fa-brands fa-buysellads me-2"></i> ADStn
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="text-center mt-5">
                            <p class="text-muted mb-0">{{ __('messages.alrehaacc') }} 
                                <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none ms-1">{{ __('messages.login') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .auth-page-wrapper {
        position: relative;
        overflow: hidden;
    }
    .auth-page-wrapper::before {
        content: "";
        position: absolute;
        top: -10%;
        left: -10%;
        width: 30%;
        height: 30%;
        background: radial-gradient(circle, rgba(97, 93, 250, 0.05) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
    }
    .auth-page-wrapper::after {
        content: "";
        position: absolute;
        bottom: -10%;
        right: -10%;
        width: 30%;
        height: 30%;
        background: radial-gradient(circle, rgba(35, 210, 226, 0.05) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
    }
    .form-control:focus {
        box-shadow: none;
        background-color: #fff !important;
        border-color: #615dfa !important;
    }
    .btn-primary {
        background-color: #615dfa;
        border-color: #615dfa;
    }
    .btn-primary:hover {
        background-color: #4a46e9;
        border-color: #4a46e9;
        transform: translateY(-2px);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .custom-check .form-check-input:checked {
        background-color: #615dfa;
        border-color: #615dfa;
    }
</style>
@endsection
