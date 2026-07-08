@extends('theme::layouts.master')

@section('title', __('messages.sign_in'))

@section('content')
<div class="auth-page-wrapper py-5 min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #f5f7ff 0%, #e8ebf5 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-5">
                            <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                                <i class="fa fa-user-lock fa-3x text-primary"></i>
                            </div>
                            <h2 class="fw-bold text-dark">{{ __('messages.sign_in') }}</h2>
                            <p class="text-muted">{{ __('messages.welcome_back') }}</p>
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

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="username" class="form-label small fw-bold text-muted text-uppercase mb-2">{{ __('messages.usermail') }}</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0"><i class="fa fa-envelope text-muted opacity-50"></i></span>
                                    <input type="text" class="form-control bg-light border-0 fs-6" id="username" name="username" value="{{ old('username') }}" required placeholder="Username or Email">
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <label for="password" class="form-label small fw-bold text-muted text-uppercase">{{ __('messages.password') }}</label>
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none fw-bold">{{ __('messages.forgot_password') }}</a>
                                </div>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-0"><i class="fa fa-lock text-muted opacity-50"></i></span>
                                    <input type="password" class="form-control bg-light border-0 fs-6" id="password" name="password" required placeholder="••••••••">
                                </div>
                            </div>
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember" checked>
                                    <label class="form-check-label small text-muted" for="remember">{{ __('messages.remember_me') }}</label>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm rounded-pill py-3" name="login">
                                    {{ __('messages.login') }} <i class="fa fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>

                        @if(env('FACEBOOK_CLIENT_ID') || env('GOOGLE_CLIENT_ID') || env('ADSTN_CLIENT_ID'))
                            <div class="position-relative my-5">
                                <hr class="text-muted">
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small text-uppercase fw-bold">{{ __('messages.login_with_social') }}</span>
                            </div>
                            <div class="d-grid gap-3">
                                @if(env('GOOGLE_CLIENT_ID'))
                                    <a href="{{ route('social.redirect', 'google') }}" class="btn btn-outline-danger btn-lg rounded-pill d-flex align-items-center justify-content-center fs-6 py-2 border-2">
                                        <i class="fa-brands fa-google me-2"></i> Continue with Google
                                    </a>
                                @endif
                                @if(env('FACEBOOK_CLIENT_ID'))
                                    <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-outline-primary btn-lg rounded-pill d-flex align-items-center justify-content-center fs-6 py-2 border-2">
                                        <i class="fa-brands fa-facebook me-2"></i> Continue with Facebook
                                    </a>
                                @endif
                                @if(env('ADSTN_CLIENT_ID'))
                                    <a href="{{ route('social.redirect', 'adstn') }}" class="btn btn-outline-secondary btn-lg rounded-pill d-flex align-items-center justify-content-center fs-6 py-2 border-2">
                                        <i class="fa-brands fa-buysellads me-2"></i> Continue with ADStn
                                    </a>
                                @endif
                            </div>
                        @endif

                        <div class="text-center mt-5">
                            <p class="text-muted mb-0">{{ __('messages.donthaacc') }} 
                                <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none ms-1">{{ __('messages.sign_up') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-muted small">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
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
        width: 40%;
        height: 40%;
        background: radial-gradient(circle, rgba(97, 93, 250, 0.05) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
    }
    .auth-page-wrapper::after {
        content: "";
        position: absolute;
        bottom: -10%;
        right: -10%;
        width: 40%;
        height: 40%;
        background: radial-gradient(circle, rgba(35, 210, 226, 0.05) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
    }
    .input-group-text {
        padding-left: 1.25rem;
        padding-right: 0.75rem;
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
</style>
@endsection
