@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%;" >
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}"  alt="overview-icon">
    <p class="section-banner-title">{{ __('messages.add_link') }}</p>
</div>

<div class="row mt-4">
    <div class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 text-center">
                <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold" >
                    <i class="fa fa-arrow-left me-2"></i>{{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <h4 class="fw-bold text-dark mb-4">{{ __('messages.add_link') }}</h4>
                <form action="{{ route('ads.links.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.name') }} (Version A)</label>
                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.name') }} (Version B - Optional)</label>
                        <input type="text" name="name_b" class="form-control form-control-lg bg-light border-0">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.url') }}</label>
                        <input type="url" name="url" class="form-control form-control-lg bg-light border-0" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.desc') }} (Version A)</label>
                        <textarea name="txt" class="form-control form-control-lg bg-light border-0" rows="3" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.desc') }} (Version B - Optional)</label>
                        <textarea name="txt_b" class="form-control form-control-lg bg-light border-0" rows="3"></textarea>
                        <div class="form-text small fw-bold text-muted mt-2">
                            <i class="fa fa-info-circle me-1"></i> A/B Testing: Provide a second version for optimization.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.smart_form_target_countries') }}</label>
                        <input type="text" name="countries" class="form-control form-control-lg bg-light border-0" placeholder="{{ __('messages.smart_form_countries_placeholder') }}">
                        <div class="form-text small text-muted">{{ __('messages.smart_form_target_countries_help') }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.smart_form_target_devices') }}</label>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach(($deviceOptions ?? []) as $value => $label)
                                <label class="btn btn-outline-secondary btn-sm rounded-pill d-inline-flex align-items-center gap-2">
                                    <input type="checkbox" name="devices[]" value="{{ $value }}">
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
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
