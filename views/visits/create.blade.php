@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%;" >
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}"  alt="overview-icon">
    <p class="section-banner-title">{{ __('messages.addWebsite') }}</p>
</div>

<div class="row mt-4">
    <div class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 text-center">
                <a href="{{ route('visits.index') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold" >
                    <i class="fa fa-arrow-left me-2"></i>{{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <h4 class="fw-bold text-dark mb-4">{{ __('messages.addWebsite') }}</h4>
                <form action="{{ route('visits.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.name') }}</label>
                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.url') }}</label>
                        <input type="url" name="url" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.duration') }}</label>
                        <select name="tims" class="form-select form-select-lg bg-light border-0">
                            <option value="1">10s (1 Point)</option>
                            <option value="2">20s (2 Points)</option>
                            <option value="3">30s (5 Points)</option>
                            <option value="4">60s (10 Points)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa fa-save me-2"></i>{{ __('messages.save') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
