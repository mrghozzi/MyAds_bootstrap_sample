@extends('theme::layouts.master')

@section('content')
@php
    $updateLinkzipValue = old('linkzip', '');
    $latestVersionName = optional($files->first())->name ?: 'v1.0';
@endphp

@include('theme::store.partials.editor-assets')

<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%;">
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/marketplace-icon.png') }}">
    <p class="section-banner-title">{{ __('messages.update') }} | {{ $product->name }}</p>
    <p class="section-banner-text">{{ __('messages.Version_nbr') }} {{ $latestVersionName }}</p>
</div>

<div class="store-editor-page mt-4">
    <form id="addstore" method="post" action="{{ route('store.update.store', $product->name) }}">
        @csrf

        <div class="store-editor-layout">
            <div class="store-editor-main">
                <div class="card border-0 shadow-sm rounded-4 store-editor-card mb-4">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">{{ __('messages.update') }}</div>
                    <p class="px-4 text-muted small fw-bold mb-3">{{ $product->name }}</p>

                    <div class="card-body">
                        <div class="store-editor-alerts">
                            @if(session('error'))
                                <div class="alert alert-danger border-0 rounded-3 shadow-sm fw-bold"><i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}</div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger border-0 rounded-3 shadow-sm fw-bold">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="upd-version" class="form-label small fw-bold">{{ __('messages.Version_nbr') }}</label>
                                <input
                                    type="text"
                                    id="upd-version"
                                    name="vnbr"
                                    class="form-control form-control-lg bg-light border-0"
                                    value="{{ old('vnbr') }}"
                                    placeholder="{{ __('messages.version') }} | EX: v1.0"
                                    minlength="2"
                                    maxlength="12"
                                    pattern="^[-a-zA-Z0-9.]+$"
                                    required
                                >
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="upd-desc" class="form-label small fw-bold mb-0">{{ __('messages.desc') }}</label>
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold open-stackedit" data-target="#upd-desc">
                                        <i class="fa fa-pencil-square me-1"></i> {{ __('messages.edit_with_stackedit') ?? 'Edit with StackEdit' }}
                                    </button>
                                </div>
                                <textarea id="upd-desc" name="desc" class="form-control bg-light border-0 p-3" rows="8" minlength="10" maxlength="2400" required>{{ old('desc') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                @include('theme::store.partials.source-picker', [
                    'linkzipValue' => $updateLinkzipValue,
                    'linkInputId' => 'store-update-direct-link',
                ])

                <div class="card border-0 shadow-sm rounded-4 store-editor-card mb-4">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">{{ __('messages.img') }}</div>
                    <div class="card-body text-center px-4 pb-4">
                        <div id="store-img-ajax-alert" style="display:none;" class="mb-3 text-start"></div>
                        <div id="OpenImgUploadUpdate" class="p-4 rounded-4 bg-light border border-dashed text-center" style="cursor:pointer;">
                            <i class="fa fa-cloud-upload fs-1 text-primary mb-3"></i>
                            <p class="fw-bold mb-1">{{ __('messages.upload') }}</p>
                            <p class="small text-muted mb-0">{{ __('messages.img') }}</p>
                        </div>
                        <div id="showImgUploadUpdate" class="mt-3"><input type="text" name="img" value="{{ old('img') }}" class="d-none"></div>
                        <input type="file" id="imgupload_update" accept=".jpg, .jpeg, .png, .gif" class="d-none">
                        @if($product->o_mode)
                            <div class="mt-3 small text-muted fw-bold">
                                {{ __('messages.current') }}: <a href="{{ $product->o_mode }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">{{ $product->o_mode }}</a>
                            </div>
                        @endif
                        <div class="d-flex justify-content-end align-items-center pt-3 border-top mt-3">
                            <button type="button" id="btn-save-cover" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-3">
                                <i class="fa fa-floppy-o me-1"></i> {{ __('messages.save') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 store-editor-card mb-4">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5 d-flex justify-content-between align-items-center">
                        <span>{{ __('messages.media_and_demo') }}</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small fw-bold">{{ __('messages.optional') }}</span>
                    </div>
                    <p class="px-4 text-muted small fw-bold mb-3">{{ __('messages.screenshots_gallery') }} &amp; {{ __('messages.live_preview') }}</p>

                    <div class="card-body px-4 pb-4 pt-0">
                        <div id="store-media-ajax-alert" style="display:none;" class="mb-3"></div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="upd-demo-url" class="form-label small fw-bold text-dark">
                                    <i class="fa fa-external-link text-primary me-1"></i> {{ __('messages.live_preview_url') }}
                                </label>
                                <input
                                    type="url"
                                    id="upd-demo-url"
                                    name="demo_url"
                                    class="form-control form-control-lg bg-light border-0"
                                    value="{{ old('demo_url', $liveDemoUrl ?? '') }}"
                                    placeholder="https://preview.example.com"
                                >
                            </div>
                            <div class="col-md-6">
                                <label for="upd-video-url" class="form-label small fw-bold text-dark">
                                    <i class="fa fa-video-camera text-danger me-1"></i> {{ __('messages.video_preview_url') }}
                                </label>
                                <input
                                    type="url"
                                    id="upd-video-url"
                                    name="video_url"
                                    class="form-control form-control-lg bg-light border-0"
                                    value="{{ old('video_url', $videoPreviewUrl ?? '') }}"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-2">
                                <i class="fa fa-picture-o text-primary me-1"></i> {{ __('messages.screenshots_gallery') }}
                            </label>

                            <input type="hidden" name="has_screenshots_section" value="1">

                            <div class="store-screenshots-dropzone p-4 rounded-4 bg-light border border-dashed text-center" style="cursor: pointer;">
                                <i class="fa fa-cloud-upload fs-1 text-primary mb-2"></i>
                                <h6 class="fw-bold mb-1">{{ __('messages.add_screenshot') }}</h6>
                                <p class="small text-muted mb-3">PNG, JPG, WEBP &bull; Max 10MB</p>
                                <div class="d-flex gap-2 justify-content-center flex-wrap">
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill fw-bold px-3 shadow-sm" id="btn-browse-screenshots-update">
                                        <i class="fa fa-folder-open me-1"></i> {{ __('messages.upload') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-3" id="btn-add-screenshot-url-update">
                                        <i class="fa fa-link me-1"></i> {{ __('messages.ext_link') }}
                                    </button>
                                </div>
                                <input type="file" id="screenshots-file-input-update" multiple accept="image/*" class="d-none">
                            </div>

                            <div id="screenshots-preview-grid-update" class="row g-3 mt-2">
                                @if(isset($screenshots))
                                    @foreach($screenshots as $ss)
                                        <div class="col-6 col-sm-4 col-md-3 screenshot-preview-item" data-url="{{ $ss->url }}">
                                            <div class="position-relative overflow-hidden rounded-4 border ratio ratio-16x9 bg-dark shadow-sm">
                                                <img src="{{ Str::startsWith($ss->url, ['http://', 'https://']) ? $ss->url : asset($ss->url) }}" class="object-fit-cover w-100 h-100" onerror="this.src='{{ theme_asset('img/error_plug.png') }}'">
                                                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 p-0 d-flex align-items-center justify-content-center btn-remove-screenshot" style="width: 26px; height: 26px;" title="{{ __('messages.remove_screenshot') }}">
                                                    <i class="fa fa-times small"></i>
                                                </button>
                                                <input type="hidden" name="screenshots[]" value="{{ $ss->url }}">
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4 flex-wrap gap-2">
                            <span class="small text-muted" id="media-save-status"></span>
                            <button type="button" id="btn-save-media" class="btn btn-primary rounded-pill fw-bold px-4 shadow-sm">
                                <i class="fa fa-floppy-o me-1"></i> {{ __('messages.save') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 store-editor-card mb-4">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">{{ __('messages.price_pts') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="pts_update" class="form-label small fw-bold">{{ __('messages.price_pts') }} <span class="text-muted fw-normal">({{ __('messages.optional') }})</span></label>
                                <input
                                    type="number"
                                    id="pts_update"
                                    name="pts"
                                    class="form-control form-control-lg bg-light border-0"
                                    value="{{ old('pts') }}"
                                    placeholder="{{ __('messages.current') }}: {{ $product->o_order }}"
                                    min="0"
                                    max="999999"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="store-editor-aside">
                <div class="card border-0 shadow-sm rounded-4 store-editor-card store-editor-sticky mb-4">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5 text-truncate" title="{{ $product->name }}">{{ $product->name }}</div>
                    <div class="card-body">
                        <div class="store-editor-summary-list mb-4">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light mb-2">
                                <span class="small fw-bold text-muted text-uppercase">{{ __('messages.current') }}</span>
                                <strong class="text-dark text-end text-truncate" style="max-width: 120px;" title="{{ $product->name }}">{{ $product->name }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light mb-2">
                                <span class="small fw-bold text-muted text-uppercase">{{ __('messages.Version_nbr') }}</span>
                                <strong class="text-dark text-end" data-store-update-version>{{ old('vnbr') ?: $latestVersionName }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light mb-2">
                                <span class="small fw-bold text-muted text-uppercase">{{ __('messages.price_pts') }}</span>
                                <strong class="text-dark text-end" data-store-update-price>{{ old('pts') !== null && old('pts') !== '' ? old('pts') : $product->o_order }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light mb-2">
                                <span class="small fw-bold text-muted text-uppercase">{{ __('messages.file_versions') }}</span>
                                <strong class="text-dark text-end">{{ $files->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light">
                                <span class="small fw-bold text-muted text-uppercase">{{ __('messages.file') }}</span>
                                <strong class="text-dark text-end" data-store-update-source>{{ filter_var($updateLinkzipValue, FILTER_VALIDATE_URL) ? __('messages.ext_link') : __('messages.upload') }}</strong>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('store.show', $product->name) }}" class="btn btn-outline-secondary rounded-pill fw-bold text-truncate" title="{{ $product->name }}">{{ $product->name }}</a>
                            <button type="submit" name="submit" id="button" value="Publish" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                <i class="fa fa-floppy-o me-2"></i> {{ __('messages.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </form>

    <details class="card border-0 shadow-sm rounded-4 store-editor-card store-editor-history mb-4">
        <summary class="card-header bg-transparent border-bottom-0 d-flex justify-content-between align-items-center p-4 cursor-pointer" style="list-style: none;">
            <span class="fw-bold fs-5 text-dark">{{ __('messages.file_versions') }}</span>
            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-bold fs-6">{{ $files->count() }}</span>
        </summary>

        <div class="card-body pt-0 border-top mt-2">
            @if($files->count() > 0)
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small fw-bold text-uppercase">
                            <tr>
                                <th class="text-center py-3 border-0">ID</th>
                                <th class="text-center py-3 border-0">{{ __('messages.version') }}</th>
                                <th class="text-center py-3 border-0">{{ __('messages.download') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                                @php
                                    $fileHash = hash('crc32', $file->o_mode . $file->id);
                                    $fileDownloads = \App\Models\Short::where('sh_type', 7867)->where('tp_id', $file->id)->value('clik') ?? 0;
                                @endphp
                                <tr>
                                    <td class="text-center py-3 border-bottom border-light fw-bold text-dark">{{ $file->id }}</td>
                                    <td class="text-center py-3 border-bottom border-light fw-bold text-dark">{{ $file->name }}</td>
                                    <td class="text-center py-3 border-bottom border-light">
                                        <a href="{{ route('store.download.hash', $fileHash) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                            <i class="fa fa-download me-1"></i> {{ __('messages.download') }} 
                                            <span class="badge bg-white text-primary ms-1 rounded-pill">{{ $fileDownloads }}</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted fw-bold py-4 mb-0">{{ __('messages.no_files') }}</p>
            @endif
        </div>
    </details>
</div>
<style>
    .cursor-pointer { cursor: pointer; }
    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important; }
    details > summary::-webkit-details-marker { display: none; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || document.querySelector('input[name="_token"]')?.value
            || '';

        // Summary sync
        function syncUpdateSummary() {
            var version = document.getElementById('upd-version');
            var price = document.getElementById('pts_update');
            var sourcePicker = document.querySelector('[data-store-source-picker]');
            var sourceText = '{{ __('messages.upload') }}';

            if (sourcePicker && sourcePicker.dataset.mode === 'link') {
                sourceText = '{{ __('messages.ext_link') }}';
            }

            var verStrong = document.querySelector('[data-store-update-version]');
            if (verStrong) verStrong.textContent = version && version.value ? version.value : '{{ $latestVersionName }}';

            var priceStrong = document.querySelector('[data-store-update-price]');
            if (priceStrong) priceStrong.textContent = price && price.value ? price.value : '{{ $product->o_order }}';

            var sourceStrong = document.querySelector('[data-store-update-source]');
            if (sourceStrong) sourceStrong.textContent = sourceText;
        }

        var updVersionEl = document.getElementById('upd-version');
        var ptsUpdateEl = document.getElementById('pts_update');
        if (updVersionEl) updVersionEl.addEventListener('input', syncUpdateSummary);
        if (ptsUpdateEl) ptsUpdateEl.addEventListener('input', syncUpdateSummary);

        document.addEventListener('click', function (event) {
            if (event.target.closest('[data-store-source-tab]')) {
                window.setTimeout(syncUpdateSummary, 0);
            }
        });
        syncUpdateSummary();

        // Cover Image Uploader & Saver
        var openImgUploadUpdate = document.getElementById('OpenImgUploadUpdate');
        var imguploadUpdate = document.getElementById('imgupload_update');
        if (openImgUploadUpdate && imguploadUpdate) {
            openImgUploadUpdate.addEventListener('click', function () {
                imguploadUpdate.click();
            });
        }

        if (imguploadUpdate) {
            imguploadUpdate.addEventListener('change', function () {
                var file = this.files[0];
                if (!file) return;

                var showBox = document.getElementById('showImgUploadUpdate');
                if (showBox) {
                    showBox.innerHTML = "<div class='progress my-2'><div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width:100%'> Uploading </div></div>";
                }

                var formData = new FormData();
                formData.append('fimg', file);
                formData.append('_token', csrfToken);

                fetch("{{ route('status.upload_image') }}", {
                    method: "POST",
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(function (res) { return res.text(); })
                .then(function (html) {
                    if (showBox) showBox.innerHTML = html;
                })
                .catch(function () {
                    if (showBox) showBox.innerHTML = '<span class="text-danger small fw-bold">{{ __('messages.error_occurred') }}</span>';
                });
            });
        }

        var btnSaveCover = document.getElementById('btn-save-cover');
        if (btnSaveCover) {
            btnSaveCover.addEventListener('click', function () {
                var imgInput = document.querySelector('input[name="img"]');
                var imgVal = imgInput ? imgInput.value : '';
                var alertDiv = document.getElementById('store-img-ajax-alert');

                if (!imgVal) {
                    if (alertDiv) {
                        alertDiv.innerHTML = '<div class="alert alert-warning border-0 rounded-3 shadow-sm small fw-bold"><i class="fa fa-info-circle me-1"></i> {{ __('messages.select_file') ?? 'الرجاء اختيار صورة أولاً' }}</div>';
                        alertDiv.style.display = 'block';
                    }
                    return;
                }

                var origBtnHtml = btnSaveCover.innerHTML;
                btnSaveCover.disabled = true;
                btnSaveCover.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> {{ __('messages.saving') ?? 'جاري الحفظ...' }}';

                fetch("{{ route('store.update.media', $product->name) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        img: imgVal,
                        _token: csrfToken
                    })
                })
                .then(function (res) {
                    return res.json().then(function (data) {
                        return { ok: res.ok, data: data };
                    });
                })
                .then(function (res) {
                    btnSaveCover.disabled = false;
                    btnSaveCover.innerHTML = origBtnHtml;
                    if (alertDiv) {
                        if (res.ok && res.data && res.data.success) {
                            alertDiv.innerHTML = '<div class="alert alert-success border-0 rounded-3 shadow-sm small fw-bold"><i class="fa fa-check-circle me-1"></i> ' + (res.data.message || '{{ __('messages.updated_successfully') }}') + '</div>';
                            alertDiv.style.display = 'block';
                        } else {
                            alertDiv.innerHTML = '<div class="alert alert-danger border-0 rounded-3 shadow-sm small fw-bold"><i class="fa fa-exclamation-triangle me-1"></i> ' + (res.data?.message || res.data?.error || '{{ __('messages.error_occurred') }}') + '</div>';
                            alertDiv.style.display = 'block';
                        }
                    }
                })
                .catch(function () {
                    btnSaveCover.disabled = false;
                    btnSaveCover.innerHTML = origBtnHtml;
                    if (alertDiv) {
                        alertDiv.innerHTML = '<div class="alert alert-danger border-0 rounded-3 shadow-sm small fw-bold"><i class="fa fa-exclamation-triangle me-1"></i> {{ __('messages.error_occurred') }}</div>';
                        alertDiv.style.display = 'block';
                    }
                });
            });
        }

        // Screenshots Gallery
        var gridUpdate = document.getElementById('screenshots-preview-grid-update');
        var fileInputUpdate = document.getElementById('screenshots-file-input-update');
        var btnBrowseUpdate = document.getElementById('btn-browse-screenshots-update');
        var btnAddUrlUpdate = document.getElementById('btn-add-screenshot-url-update');
        var dropzoneUpdate = document.querySelector('.store-screenshots-dropzone');

        function addScreenshotItem(url, fullUrl) {
            if (!gridUpdate) return;
            var col = document.createElement('div');
            col.className = 'col-6 col-sm-4 col-md-3 screenshot-preview-item';
            col.dataset.url = url;
            col.innerHTML = '<div class="position-relative overflow-hidden rounded-4 border ratio ratio-16x9 bg-dark shadow-sm">' +
                '<img src="' + fullUrl + '" class="object-fit-cover w-100 h-100" onerror="this.src=\'{{ theme_asset("img/error_plug.png") }}\'">' +
                '<button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 p-0 d-flex align-items-center justify-content-center btn-remove-screenshot" style="width: 26px; height: 26px;" title="{{ __('messages.remove_screenshot') }}">' +
                    '<i class="fa fa-times small"></i>' +
                '</button>' +
                '<input type="hidden" name="screenshots[]" value="' + url + '">' +
            '</div>';
            gridUpdate.appendChild(col);
        }

        if (btnBrowseUpdate && fileInputUpdate) {
            btnBrowseUpdate.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                fileInputUpdate.click();
            });
        }

        function uploadScreenshotFiles(files) {
            if (!files || !files.length) return;
            for (var i = 0; i < files.length; i++) {
                (function(file) {
                    var tempId = 'ss-upd-uploading-' + Date.now() + '-' + Math.floor(Math.random() * 10000);
                    var col = document.createElement('div');
                    col.className = 'col-6 col-sm-4 col-md-3';
                    col.id = tempId;
                    col.innerHTML = '<div class="rounded-4 border border-primary border-dashed bg-light d-flex align-items-center justify-content-center ratio ratio-16x9">' +
                        '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                    '</div>';
                    if (gridUpdate) gridUpdate.appendChild(col);

                    var formData = new FormData();
                    formData.append('image', file);
                    formData.append('_token', csrfToken);

                    fetch("{{ route('store.upload_screenshot') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(function(res) {
                        return res.json().then(function(data) {
                            return { ok: res.ok, data: data };
                        });
                    })
                    .then(function(res) {
                        var el = document.getElementById(tempId);
                        if (el) el.remove();
                        if (res.ok && res.data && res.data.success && res.data.url) {
                            addScreenshotItem(res.data.url, res.data.full_url || res.data.url);
                        } else {
                            alert(res.data?.message || '{{ __('messages.error_occurred') }}');
                        }
                    })
                    .catch(function() {
                        var el = document.getElementById(tempId);
                        if (el) el.remove();
                        alert('{{ __('messages.error_occurred') }}');
                    });
                })(files[i]);
            }
        }

        if (fileInputUpdate) {
            fileInputUpdate.addEventListener('change', function() {
                uploadScreenshotFiles(this.files);
                this.value = '';
            });
        }

        if (btnAddUrlUpdate) {
            btnAddUrlUpdate.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var url = prompt('{{ __('messages.screenshot_url') }}:');
                if (url && url.trim()) {
                    url = url.trim();
                    addScreenshotItem(url, url);
                }
            });
        }

        // Drag & drop on dropzone
        if (dropzoneUpdate) {
            ['dragenter', 'dragover'].forEach(function(ev) {
                dropzoneUpdate.addEventListener(ev, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzoneUpdate.classList.add('bg-white', 'border-primary');
                }, false);
            });
            ['dragleave', 'drop'].forEach(function(ev) {
                dropzoneUpdate.addEventListener(ev, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzoneUpdate.classList.remove('bg-white', 'border-primary');
                }, false);
            });
            dropzoneUpdate.addEventListener('drop', function(e) {
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                    uploadScreenshotFiles(e.dataTransfer.files);
                }
            }, false);
        }

        // Remove screenshot handler
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-remove-screenshot');
            if (btn) {
                var item = btn.closest('.screenshot-preview-item');
                if (item) {
                    item.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.85)';
                    setTimeout(function() { item.remove(); }, 200);
                }
            }
        });

        // Dedicated Save Media & Live Preview Handler
        var btnSaveMedia = document.getElementById('btn-save-media');
        if (btnSaveMedia) {
            btnSaveMedia.addEventListener('click', function(e) {
                e.preventDefault();

                var demoUrl = document.getElementById('upd-demo-url')?.value || '';
                var videoUrl = document.getElementById('upd-video-url')?.value || '';
                var coverImg = document.querySelector('input[name="img"]')?.value || '';

                var screenshotInputs = document.querySelectorAll('#screenshots-preview-grid-update input[name="screenshots[]"]');
                var screenshots = [];
                screenshotInputs.forEach(function(inp) {
                    if (inp.value && inp.value.trim()) screenshots.push(inp.value.trim());
                });

                var origBtnHtml = btnSaveMedia.innerHTML;
                btnSaveMedia.disabled = true;
                btnSaveMedia.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> {{ __('messages.saving') ?? 'جاري الحفظ...' }}';

                var alertDiv = document.getElementById('store-media-ajax-alert');
                if (alertDiv) alertDiv.style.display = 'none';

                var payload = {
                    demo_url: demoUrl,
                    video_url: videoUrl,
                    screenshots: screenshots,
                    has_screenshots_section: 1,
                    img: coverImg,
                    _token: csrfToken
                };

                fetch("{{ route('store.update.media', $product->name) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return { ok: res.ok, data: data };
                    });
                })
                .then(function(result) {
                    btnSaveMedia.disabled = false;
                    btnSaveMedia.innerHTML = origBtnHtml;

                    if (alertDiv) {
                        if (result.ok && result.data && result.data.success) {
                            alertDiv.innerHTML = '<div class="alert alert-success border-0 rounded-3 shadow-sm small fw-bold"><i class="fa fa-check-circle me-1"></i> ' + (result.data.message || '{{ __('messages.media_updated_successfully') }}') + '</div>';
                            alertDiv.style.display = 'block';
                            alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        } else {
                            var errMsg = result.data?.message || result.data?.error || '{{ __('messages.error_occurred') }}';
                            if (result.data?.errors) {
                                errMsg += '<ul class="mb-0 ps-3 mt-1">';
                                Object.keys(result.data.errors).forEach(function(k) {
                                    result.data.errors[k].forEach(function(m) { errMsg += '<li>' + m + '</li>'; });
                                });
                                errMsg += '</ul>';
                            }
                            alertDiv.innerHTML = '<div class="alert alert-danger border-0 rounded-3 shadow-sm small fw-bold"><i class="fa fa-exclamation-triangle me-1"></i> ' + errMsg + '</div>';
                            alertDiv.style.display = 'block';
                            alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    }
                })
                .catch(function() {
                    btnSaveMedia.disabled = false;
                    btnSaveMedia.innerHTML = origBtnHtml;
                    if (alertDiv) {
                        alertDiv.innerHTML = '<div class="alert alert-danger border-0 rounded-3 shadow-sm small fw-bold"><i class="fa fa-exclamation-triangle me-1"></i> {{ __('messages.error_occurred') }}</div>';
                        alertDiv.style.display = 'block';
                        alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                });
            });
        }
    });
</script>

<script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stackedit = new Stackedit();
    document.querySelectorAll('.open-stackedit').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const textarea = document.querySelector(targetId);
            const articleName = '{{ $product->name }} Update Notes';
            
            stackedit.openFile({
                name: articleName,
                content: {
                    text: textarea.value
                }
            });

            const adjustIframe = () => {
                const iframe = document.querySelector('iframe[src*="stackedit.io"]');
                if (iframe) {
                    const header = document.querySelector('.header, .nxl-header');
                    if (header) {
                        const headerHeight = header.offsetHeight;
                        iframe.style.top = headerHeight + 'px';
                        iframe.style.height = `calc(100% - ${headerHeight}px)`;
                    } else {
                        iframe.style.top = '80px';
                        iframe.style.height = 'calc(100% - 80px)';
                    }
                } else {
                    setTimeout(adjustIframe, 50);
                }
            };
            adjustIframe();

            stackedit.off('fileChange');
            stackedit.on('fileChange', (file) => {
                textarea.value = file.content.text;
            });
        });
    });
});
</script>
@endsection
