@extends('theme::layouts.master')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card border-0 shadow-sm p-4" style="width: 100%; max-width: 480px; border-radius: 16px;">
        <div class="card-body text-center">
            <div class="mb-4 text-primary bg-primary bg-opacity-10 p-3 rounded-circle d-inline-flex">
                <i class="fa fa-shield-halved fa-2x"></i>
            </div>
            <h2 class="card-title fw-bold h4 mb-2">{{ __('messages.two_factor_auth') ?? 'Two-Factor Authentication' }}</h2>
            <p class="card-text text-muted small mb-4">
                {{ __('messages.two_factor_description') ?? 'Please enter the verification code sent to your email to continue.' }}
            </p>

            <form action="{{ url('/two-factor-challenge') }}" method="POST" class="text-start">
                @csrf
                
                <div class="mb-4">
                    <label for="code" class="form-label fw-bold text-center d-block">{{ __('messages.verification_code') ?? 'Verification Code' }}</label>
                    <input type="text" id="code" name="code" class="form-control text-center fw-bold fs-4 @error('code') is-invalid @enderror" required autocomplete="one-time-code" autofocus style="letter-spacing: 8px;">
                    @error('code')
                        <div class="invalid-feedback text-center mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    {{ __('messages.verify') ?? 'Verify' }}
                </button>
            </form>

            <div class="mt-4 pt-3 border-top">
                <form action="{{ route('two-factor.resend') }}" method="POST">
                    @csrf
                    <p class="text-muted small mb-0">
                        {{ __('messages.didnt_receive_code') ?? "Didn't receive the code?" }}
                        <button type="submit" class="btn btn-link text-primary p-0 fw-bold align-baseline" style="text-decoration: none;">
                            {{ __('messages.resend_code') ?? 'Resend Code' }}
                        </button>
                    </p>
                </form>
                
                @if(session('success'))
                    <div class="alert alert-success mt-2 mb-0 py-2 small" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="mt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted p-0 small text-decoration-none">
                        {{ __('messages.cancel_and_logout') ?? 'Cancel and Logout' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
