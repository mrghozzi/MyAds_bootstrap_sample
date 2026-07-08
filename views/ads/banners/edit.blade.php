@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%;" >
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}"  alt="overview-icon">
    <p class="section-banner-title">{{ __('messages.edit_banner') }}</p>
</div>

<div class="row mt-4">
    <div class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 text-center">
                <a href="{{ route('ads.banners.index') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold" >
                    <i class="fa fa-arrow-left me-2"></i>{{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <h4 class="fw-bold text-dark mb-4">{{ __('messages.edit_banner') }}</h4>
                <form action="{{ route('ads.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @php($bannerSizes = \App\Support\BannerSizeCatalog::ordered())
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.name') }}</label>
                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ $banner->name }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.url') }}</label>
                        <input type="url" name="url" class="form-control form-control-lg bg-light border-0" value="{{ $banner->url }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.size') }}</label>
                        <select name="px" class="form-select form-select-lg bg-light border-0">
                            @foreach($bannerSizes as $size)
                                <option value="{{ $size['value'] }}" {{ $banner->px == $size['value'] ? 'selected' : '' }}>{{ $size['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.img') }} (Version A)</label>
                        <input type="text" name="img" class="form-control form-control-lg bg-light border-0" value="{{ $banner->img }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">{{ __('messages.img') }} (Version B - Optional)</label>
                        <input type="text" name="img_b" class="form-control form-control-lg bg-light border-0" value="{{ $banner->img_b }}">
                        <div class="form-text small fw-bold text-muted mt-2">
                            <i class="fa fa-info-circle me-1"></i> A/B Testing: Provide a second image for optimization.
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
