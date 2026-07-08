@php
    $smartAd = $smartAd ?? new \App\Models\SmartAd();
    $targetCountries = $targetCountries ?? '';
    $selectedDevices = $selectedDevices ?? [];
    $deviceOptions = $deviceOptions ?? [];
    $formAction = $formAction ?? route('ads.smart.store');
    $formMethod = $formMethod ?? 'POST';
    $submitLabel = $submitLabel ?? __('messages.smart_form_save');
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-inline-start: 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

<form action="{{ $formAction }}" method="POST">
    @csrf
    @if($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">{{ __('messages.smart_form_landing_url') }}</label>
                        <input type="url" name="landing_url" class="form-control form-control-lg bg-light border-0" value="{{ old('landing_url', $smartAd->landing_url) }}" required>
                        <small class="form-text text-muted mt-2 d-block">{{ __('messages.smart_form_landing_help') }}</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">{{ __('messages.smart_form_headline_override') }}</label>
                        <input type="text" name="headline_override" class="form-control form-control-lg bg-light border-0" value="{{ old('headline_override', $smartAd->headline_override) }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">{{ __('messages.smart_form_description_override') }}</label>
                        <textarea name="description_override" class="form-control bg-light border-0" rows="5">{{ old('description_override', $smartAd->description_override) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">{{ __('messages.smart_form_image_override') }}</label>
                        <input type="text" name="image" class="form-control form-control-lg bg-light border-0" value="{{ old('image', $smartAd->image) }}">
                        <small class="form-text text-muted mt-2 d-block">{{ __('messages.smart_form_image_help') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">{{ __('messages.smart_form_target_countries') }}</label>
                        <input type="text" name="countries" class="form-control form-control-lg bg-light border-0" value="{{ old('countries', $targetCountries) }}" placeholder="{{ __('messages.smart_form_countries_placeholder') }}">
                        <small class="form-text text-muted mt-2 d-block">{{ __('messages.smart_form_target_countries_help') }}</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">{{ __('messages.smart_form_target_devices') }}</label>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($deviceOptions as $value => $label)
                                <label class="border bg-white px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2" style="cursor: pointer;">
                                    <input type="checkbox" name="devices[]" class="form-check-input mt-0" value="{{ $value }}" {{ in_array($value, old('devices', $selectedDevices), true) ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <small class="form-text text-muted mt-2 d-block">{{ __('messages.smart_form_target_devices_help') }}</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">{{ __('messages.smart_form_manual_keywords') }}</label>
                        <textarea name="manual_keywords" class="form-control bg-light border-0" rows="5" placeholder="{{ __('messages.smart_form_keywords_placeholder') }}">{{ old('manual_keywords', $smartAd->manual_keywords) }}</textarea>
                        <small class="form-text text-muted mt-2 d-block">{{ __('messages.smart_form_manual_keywords_help') }}</small>
                    </div>

                    @if(isset($smartAd) && $smartAd->exists)
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">{{ __('messages.smart_form_extracted_topic') }}</label>
                            <textarea class="form-control bg-light border-0" rows="5" readonly>{{ $smartAd->extracted_keywords }}</textarea>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%); border: 1px solid #eef2ff;">
                        <p class="mb-2 fw-bold text-primary text-uppercase small tracking-wide">{{ __('messages.smart_banner_output') }}</p>
                        <p class="mb-0 text-muted">{{ __('messages.smart_banner_output_help') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #f6fffe 0%, #effcfb 100%); border: 1px solid #e5f8f8;">
                        <p class="mb-2 fw-bold text-success text-uppercase small tracking-wide">{{ __('messages.smart_native_fallback') }}</p>
                        <p class="mb-0 text-muted">{{ __('messages.smart_native_fallback_help') }}</p>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 flex-wrap pt-3 border-top">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold">{{ $submitLabel }}</button>
                <a href="{{ route('ads.smart.index') }}" class="btn btn-light btn-lg rounded-pill px-4 fw-bold">{{ __('messages.smart_back_to_list') }}</a>
            </div>
        </div>
    </div>
</form>
