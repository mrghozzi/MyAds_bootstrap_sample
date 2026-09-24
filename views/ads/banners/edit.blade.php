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
                <a href="{{ route('ads.banners.index') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-bold">
                    <i class="fa fa-arrow-left me-2"></i>{{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 pb-3 border-bottom">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="fa fa-edit text-primary me-2"></i>{{ __('messages.edit_banner') }}
                        </h4>
                        <span class="text-muted small">#{{ $banner->id }} &bull; {{ $banner->name }}</span>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold">
                        {{ $banner->px }}
                    </span>
                </div>

                <form action="{{ route('ads.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @php($bannerSizes = \App\Support\BannerSizeCatalog::ordered())

                    <div class="row g-4">
                        <!-- Left Column: Core Fields & Images -->
                        <div class="col-lg-7">
                            <div class="mb-4">
                                <label class="form-label small fw-bold">{{ __('messages.name') }}</label>
                                <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name', $banner->name) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">{{ __('messages.url') }}</label>
                                <input type="url" name="url" class="form-control form-control-lg bg-light border-0" value="{{ old('url', $banner->url) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">{{ __('messages.size') }}</label>
                                <select name="px" class="form-select form-select-lg bg-light border-0" required>
                                    @foreach($bannerSizes as $size)
                                        <option value="{{ $size['value'] }}" {{ old('px', $banner->px) == $size['value'] ? 'selected' : '' }}>{{ $size['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Banner Image (Version A) -->
                            <div class="card border bg-light rounded-4 mb-4">
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                        <label class="form-label small fw-bold mb-0 text-dark">
                                            <i class="fa fa-image text-primary me-1"></i> {{ __('messages.img') }} (Version A)
                                        </label>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" id="btnEditModeUrlA" class="btn btn-primary fw-bold" onclick="switchEditImgMode('A', 'url')">
                                                <i class="fa fa-link me-1"></i> {{ __('messages.enter_image_url') }}
                                            </button>
                                            <button type="button" id="btnEditModeUploadA" class="btn btn-outline-secondary fw-bold" onclick="switchEditImgMode('A', 'upload')">
                                                <i class="fa fa-cloud-upload-alt me-1"></i> {{ __('messages.upload_from_device') }}
                                            </button>
                                        </div>
                                    </div>

                                    <div id="editContainerUrlA">
                                        <input type="text" name="img" id="editInputUrlA" class="form-control bg-white border" value="{{ old('img', $banner->img) }}" placeholder="https://example.com/banner.png" oninput="updateEditPreview('A', this.value)">
                                    </div>
                                    <div id="editContainerUploadA" style="display: none;">
                                        <div class="border border-2 border-dashed rounded-3 p-3 text-center bg-white" style="cursor: pointer;" onclick="document.getElementById('editInputFileA').click();">
                                            <input type="file" name="img_file" id="editInputFileA" class="d-none" accept="image/*" onchange="handleEditFileSelected('A', this)">
                                            <i class="fa fa-cloud-upload-alt fs-3 text-primary mb-2 d-block"></i>
                                            <span id="editLabelFileA" class="fw-bold small text-dark d-block mb-1">{{ __('messages.upload_from_device') }}</span>
                                            <span class="text-muted small">JPG, PNG, GIF, WEBP, SVG (Max 5MB)</span>
                                        </div>
                                    </div>

                                    <div id="editPreviewBoxA" class="mt-3 text-center bg-white p-3 rounded-3 border">
                                        <span class="text-muted small fw-bold d-block mb-2">{{ __('messages.current_image') }} / {{ __('messages.preview') }}</span>
                                        <img id="editPreviewImgA" src="{{ $banner->img }}" alt="Version A" class="img-fluid rounded" style="max-height: 110px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                            <!-- Banner Image (Version B - Optional) -->
                            <div class="card border bg-light rounded-4 mb-4">
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                        <label class="form-label small fw-bold mb-0 text-dark">
                                            <i class="fa fa-copy text-info me-1"></i> {{ __('messages.img') }} (Version B - Optional)
                                        </label>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" id="btnEditModeUrlB" class="btn btn-primary fw-bold" onclick="switchEditImgMode('B', 'url')">
                                                <i class="fa fa-link me-1"></i> {{ __('messages.enter_image_url') }}
                                            </button>
                                            <button type="button" id="btnEditModeUploadB" class="btn btn-outline-secondary fw-bold" onclick="switchEditImgMode('B', 'upload')">
                                                <i class="fa fa-cloud-upload-alt me-1"></i> {{ __('messages.upload_from_device') }}
                                            </button>
                                        </div>
                                    </div>

                                    <div id="editContainerUrlB">
                                        <input type="text" name="img_b" id="editInputUrlB" class="form-control bg-white border" value="{{ old('img_b', $banner->img_b) }}" placeholder="https://..." oninput="updateEditPreview('B', this.value)">
                                    </div>
                                    <div id="editContainerUploadB" style="display: none;">
                                        <div class="border border-2 border-dashed rounded-3 p-3 text-center bg-white" style="cursor: pointer;" onclick="document.getElementById('editInputFileB').click();">
                                            <input type="file" name="img_file_b" id="editInputFileB" class="d-none" accept="image/*" onchange="handleEditFileSelected('B', this)">
                                            <i class="fa fa-cloud-upload-alt fs-3 text-info mb-2 d-block"></i>
                                            <span id="editLabelFileB" class="fw-bold small text-dark d-block mb-1">{{ __('messages.upload_from_device') }}</span>
                                            <span class="text-muted small">JPG, PNG, GIF, WEBP, SVG (Max 5MB)</span>
                                        </div>
                                    </div>

                                    <div id="editPreviewBoxB" class="mt-3 text-center bg-white p-3 rounded-3 border" style="{{ $banner->img_b ? '' : 'display: none;' }}">
                                        <span class="text-muted small fw-bold d-block mb-2">{{ __('messages.preview') }} (Version B)</span>
                                        <img id="editPreviewImgB" src="{{ $banner->img_b ?? '' }}" alt="Version B" class="img-fluid rounded" style="max-height: 110px; object-fit: contain;">
                                    </div>
                                    <div class="form-text small text-muted mt-2">
                                        <i class="fa fa-info-circle me-1"></i> A/B Testing: Provide a second image to automatically serve the best performing version.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Targeting Options -->
                        <div class="col-lg-5">
                            <div class="card border-0 bg-light rounded-4 p-4 mb-4">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="fa fa-globe text-primary me-2"></i>{{ __('messages.smart_form_target_countries') }}
                                </h6>
                                <div class="mb-3">
                                    <input type="text" name="countries" class="form-control bg-white border" value="{{ old('countries', $targetCountries) }}" placeholder="{{ __('messages.smart_form_countries_placeholder') }}">
                                    <div class="form-text small text-muted mt-1">{{ __('messages.smart_form_target_countries_help') }}</div>
                                </div>

                                <h6 class="fw-bold text-dark mb-3 mt-3">
                                    <i class="fa fa-laptop text-primary me-2"></i>{{ __('messages.smart_form_target_devices') }}
                                </h6>
                                <div class="d-flex flex-column gap-2">
                                    @foreach($deviceOptions as $value => $label)
                                        <label class="form-check d-flex align-items-center gap-2 p-2 bg-white rounded-3 border mb-0" style="cursor: pointer;">
                                            <input class="form-check-input ms-0 mt-0" type="checkbox" name="devices[]" value="{{ $value }}" {{ in_array($value, old('devices', $selectedDevices), true) ? 'checked' : '' }}>
                                            <span class="small fw-semibold text-dark">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa fa-save me-2"></i>{{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function switchEditImgMode(version, mode) {
    const containerUrl = document.getElementById('editContainerUrl' + version);
    const containerUpload = document.getElementById('editContainerUpload' + version);
    const btnUrl = document.getElementById('btnEditModeUrl' + version);
    const btnUpload = document.getElementById('btnEditModeUpload' + version);

    if (mode === 'url') {
        containerUrl.style.display = 'block';
        containerUpload.style.display = 'none';
        btnUrl.className = 'btn btn-primary fw-bold';
        btnUpload.className = 'btn btn-outline-secondary fw-bold';
        const val = document.getElementById('editInputUrl' + version).value;
        if (val) updateEditPreview(version, val);
    } else {
        containerUrl.style.display = 'none';
        containerUpload.style.display = 'block';
        btnUpload.className = 'btn btn-primary fw-bold';
        btnUrl.className = 'btn btn-outline-secondary fw-bold';
    }
}

function updateEditPreview(version, url) {
    const box = document.getElementById('editPreviewBox' + version);
    const img = document.getElementById('editPreviewImg' + version);
    if (!box || !img) return;
    const trimmed = (url || '').trim();
    if (trimmed) {
        img.src = trimmed;
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}

function handleEditFileSelected(version, input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const label = document.getElementById('editLabelFile' + version);
        if (label) label.textContent = file.name;

        const box = document.getElementById('editPreviewBox' + version);
        const img = document.getElementById('editPreviewImg' + version);
        if (box && img) {
            img.src = URL.createObjectURL(file);
            box.style.display = 'block';
        }
    }
}
</script>
@endsection
