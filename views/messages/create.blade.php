@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: url({{ theme_asset('img/banner/profile.png') }}) no-repeat 50%;">
    <p class="section-banner-title">{{ __('messages.send_message') }}</p>
</div>

<div class="row">
    <div class="col-lg-3 col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">{{ __('messages.actions') }}</div>
            <div class="card-body p-4">
                <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary rounded-pill w-100 fw-bold">{{ __('messages.back_to_inbox') }}</a>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="recipient" class="form-label small fw-bold">{{ __('messages.recipient') }}</label>
                        <input type="text" id="recipient" name="recipient" class="form-control form-control-lg bg-light border-0" value="{{ $recipient ?? '' }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label small fw-bold">{{ __('messages.message') }}</label>
                        <textarea id="message" name="message" class="form-control bg-light border-0" required style="height: 150px;"></textarea>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa fa-paper-plane me-2"></i> {{ __('messages.send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
