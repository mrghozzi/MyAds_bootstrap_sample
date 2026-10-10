@extends('theme::layouts.master')

@section('content')
@php
    $createLinkzipValue = old('linkzip', '');
@endphp

@include('theme::store.partials.editor-assets')

<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                <i class="fa fa-cart-plus fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-bold mb-1">{{ __('messages.add_product') }}</h1>
                <p class="mb-0 text-white-50 small">{{ __('messages.landing_community_store_desc') }}</p>
            </div>
        </div>
    </div>

    <form id="addstore" method="post" action="{{ route('store.store') }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Main Form Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.product_details') }}</h6>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="store-editor-alerts">
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="store-name" class="form-label fw-bold">{{ __('messages.titer') }}</label>
                            <input
                                type="text"
                                id="store-name"
                                class="form-control form-control-lg rounded-3 sname"
                                name="name"
                                value="{{ old('name') }}"
                                minlength="3"
                                maxlength="35"
                                pattern="^[-a-zA-Z0-9_]+$"
                                required
                                placeholder="Product_Name_No_Spaces"
                            >
                            <div id="msg_name" class="mt-2">
                                <input type="text" style="visibility:hidden; height:0;" value="{{ old('vname') }}" name="vname" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="store-desc" class="form-label fw-bold">{{ __('messages.desc') }}</label>
                            <input type="text" id="store-desc" class="form-control rounded-3" name="desc" value="{{ old('desc') }}" minlength="10" maxlength="2400" required placeholder="{{ __('messages.desc') }}...">
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="store-version" class="form-label fw-bold">{{ __('messages.Version_nbr') }}</label>
                                <input
                                    type="text"
                                    id="store-version"
                                    name="vnbr"
                                    class="form-control rounded-3"
                                    value="{{ old('vnbr') }}"
                                    placeholder="EX: v1.0"
                                    minlength="2"
                                    maxlength="12"
                                    pattern="^[-a-zA-Z0-9.]+$"
                                    required
                                >
                            </div>
                            <div class="col-md-6">
                                <label for="store-price" class="form-label fw-bold">{{ __('messages.price_pts') }}</label>
                                <input
                                    type="text"
                                    id="store-price"
                                    name="pts"
                                    class="form-control rounded-3"
                                    value="{{ old('pts') }}"
                                    placeholder="{{ __('messages.pmbno') }}"
                                    minlength="1"
                                    maxlength="6"
                                    pattern="[0-9]+"
                                    required
                                >
                            </div>
                        </div>

                        <div class="mb-4">
                            <div id="storecat">
                                @include('theme::store.partials.category-selector')
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="editor1" class="form-label fw-bold d-flex justify-content-between align-items-center">
                                {{ __('messages.topic') }}
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 open-stackedit" data-target="#editor1">
                                    <i class="fa fa-pencil-square me-1"></i> {{ __('messages.edit_with_stackedit') ?? 'Edit with StackEdit' }}
                                </button>
                            </label>
                            <textarea name="txt" id="editor1" rows="15" class="form-control rounded-4 p-3 mt-2" required placeholder="{{ __('messages.topic') }}...">{{ old('txt') }}</textarea>
                        </div>
                    </div>
                </div>

                @include('theme::store.partials.source-picker', [
                    'linkzipValue' => $createLinkzipValue,
                    'linkInputId' => 'store-create-direct-link',
                ])

                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.img') }}</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div id="OpenImgUpload" class="border-2 border-dashed rounded-4 p-5 cursor-pointer bg-light transition-all hover-bg-white border-primary" style="cursor: pointer;">
                            <i class="fa fa-cloud-upload fa-3x text-primary mb-3"></i>
                            <h5 class="fw-bold">{{ __('messages.upload') }}</h5>
                            <p class="text-muted small mb-0">{{ __('messages.img') }}</p>
                        </div>
                        <div id="showImgUpload" class="mt-4">
                            <input type="text" name="img" value="{{ old('img') }}" style="display:none" required>
                        </div>
                        <input type="file" id="imgupload" accept=".jpg, .jpeg, .png, .gif" style="display:none">
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.media_and_demo') }}</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small fw-bold">{{ __('messages.optional') }}</span>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <p class="text-muted small fw-bold mb-3">{{ __('messages.screenshots_gallery') }} &amp; {{ __('messages.live_preview') }}</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="store-demo-url" class="form-label small fw-bold text-dark">
                                    <i class="fa fa-external-link text-primary me-1"></i> {{ __('messages.live_preview_url') }}
                                </label>
                                <input
                                    type="url"
                                    id="store-demo-url"
                                    name="demo_url"
                                    class="form-control rounded-3 bg-light border-0"
                                    value="{{ old('demo_url') }}"
                                    placeholder="https://preview.example.com"
                                >
                            </div>
                            <div class="col-md-6">
                                <label for="store-video-url" class="form-label small fw-bold text-dark">
                                    <i class="fa fa-video-camera text-danger me-1"></i> {{ __('messages.video_preview_url') }}
                                </label>
                                <input
                                    type="url"
                                    id="store-video-url"
                                    name="video_url"
                                    class="form-control rounded-3 bg-light border-0"
                                    value="{{ old('video_url') }}"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-2">
                                <i class="fa fa-picture-o text-primary me-1"></i> {{ __('messages.screenshots_gallery') }}
                            </label>

                            <div class="store-screenshots-dropzone p-4 rounded-4 bg-light border border-dashed text-center" style="cursor: pointer;">
                                <i class="fa fa-cloud-upload fs-1 text-primary mb-2"></i>
                                <h6 class="fw-bold mb-1">{{ __('messages.add_screenshot') }}</h6>
                                <p class="small text-muted mb-3">PNG, JPG, WEBP &bull; Max 10MB</p>
                                <div class="d-flex gap-2 justify-content-center flex-wrap">
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill fw-bold px-3 shadow-sm" id="btn-browse-screenshots">
                                        <i class="fa fa-folder-open me-1"></i> {{ __('messages.upload') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-3" id="btn-add-screenshot-url">
                                        <i class="fa fa-link me-1"></i> {{ __('messages.ext_link') }}
                                    </button>
                                </div>
                                <input type="file" id="screenshots-file-input" multiple accept="image/*" class="d-none">
                            </div>

                            <div id="screenshots-preview-grid" class="row g-3 mt-2">
                                <!-- Uploaded screenshots preview items -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Summary Card -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top" style="top: 2rem; z-index: 10;">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h6 class="fw-bold mb-0 text-uppercase small text-muted">{{ __('messages.summary') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold">{{ __('messages.Version_nbr') }}</span>
                                <strong class="text-primary" data-store-create-version>{{ old('vnbr') ?: 'v1.0' }}</strong>
                            </div>
                            <div class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold">{{ __('messages.price_pts') }}</span>
                                <strong class="text-success" data-store-create-price>{{ old('pts') ?: '--' }}</strong>
                            </div>
                            <div class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold">{{ __('messages.cat') }}</span>
                                <strong class="text-info" data-store-create-category>{{ $selectedStoreCategory ? __('messages.' . $selectedStoreCategory) : '--' }}</strong>
                            </div>
                            <div class="list-group-item bg-transparent px-0 py-3 d-flex justify-content-between align-items-center border-bottom-0">
                                <span class="text-muted small fw-bold">{{ __('messages.file') }}</span>
                                <strong class="text-warning" data-store-create-source>{{ filter_var($createLinkzipValue, FILTER_VALIDATE_URL) ? __('messages.ext_link') : __('messages.upload') }}</strong>
                            </div>
                        </div>
                        
                        <hr class="my-4 opacity-10">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="submit" id="button" value="Publish" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                                <i class="fa fa-check-circle me-2"></i> {{ __('messages.save') }}
                            </button>
                            <a href="{{ route('store.index') }}" class="btn btn-light rounded-pill fw-bold">
                                {{ __('messages.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-info bg-opacity-10">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-info text-uppercase small mb-3"><i class="fa fa-question-circle me-1"></i> {{ __('Help') }}</h6>
                        <p class="small text-muted mb-0 lh-base">
                            Need help setting up your product? Check our <a href="https://github.com/mrghozzi/myads/wiki/store:update" target="_blank" class="text-info fw-bold text-decoration-none">documentation</a> for best practices and tips.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || document.querySelector('input[name="_token"]')?.value
            || '';

        // Cover Image Upload trigger
        var openImgUpload = document.getElementById('OpenImgUpload');
        var imguploadInput = document.getElementById('imgupload');
        if (openImgUpload && imguploadInput) {
            openImgUpload.addEventListener('click', function () {
                imguploadInput.click();
            });
        }

        var showImgUpload = document.getElementById('showImgUpload');
        if (imguploadInput) {
            imguploadInput.addEventListener('change', function () {
                var file = this.files[0];
                if (!file) return;

                if (showImgUpload) {
                    showImgUpload.innerHTML = '<div class="d-flex align-items-center justify-content-center text-primary"><div class="spinner-border spinner-border-sm me-2"></div><small>Uploading image...</small></div>';
                }

                var formData = new FormData();
                formData.append('fimg', file);
                formData.append('_token', csrfToken);

                fetch("{{ route('status.upload_image') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(function (res) { return res.text(); })
                .then(function (response) {
                    if (showImgUpload) showImgUpload.innerHTML = response;
                })
                .catch(function () {
                    if (showImgUpload) showImgUpload.innerHTML = '<span class="text-danger small fw-bold">{{ __('messages.error_occurred') }}</span>';
                });
            });
        }

        // Store Name Verification
        var snameInputs = document.querySelectorAll('.sname');
        var msgName = document.getElementById('msg_name');
        snameInputs.forEach(function (inp) {
            inp.addEventListener('change', function () {
                var sname = this.value;
                if (!sname || !sname.trim()) return;

                if (msgName) msgName.innerHTML = '<div class="spinner-border spinner-border-sm text-primary"></div>';

                var formData = new FormData();
                formData.append('sname', sname);
                formData.append('_token', csrfToken);

                fetch("{{ route('store.verify_name') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(function (res) { return res.text(); })
                .then(function (html) {
                    if (msgName) msgName.innerHTML = html;
                })
                .catch(function () {
                    if (msgName) msgName.innerHTML = '';
                });
            });
        });

        // Screenshots Gallery Uploader
        var screenshotsGrid = document.getElementById('screenshots-preview-grid');
        var btnBrowseScreenshots = document.getElementById('btn-browse-screenshots');
        var screenshotsFileInput = document.getElementById('screenshots-file-input');
        var btnAddScreenshotUrl = document.getElementById('btn-add-screenshot-url');
        var dropzoneCreate = document.querySelector('.store-screenshots-dropzone');

        function addScreenshotItem(url, fullUrl) {
            if (!screenshotsGrid) return;
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
            screenshotsGrid.appendChild(col);
        }

        if (btnBrowseScreenshots && screenshotsFileInput) {
            btnBrowseScreenshots.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                screenshotsFileInput.click();
            });
        }

        function uploadScreenshotFiles(files) {
            if (!files || !files.length) return;
            for (var i = 0; i < files.length; i++) {
                (function (file) {
                    var tempId = 'ss-uploading-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
                    var col = document.createElement('div');
                    col.className = 'col-6 col-sm-4 col-md-3';
                    col.id = tempId;
                    col.innerHTML = '<div class="rounded-4 border border-primary border-dashed bg-light d-flex align-items-center justify-content-center ratio ratio-16x9"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
                    if (screenshotsGrid) screenshotsGrid.appendChild(col);

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
                    .then(function (res) {
                        return res.json().then(function (data) {
                            return { ok: res.ok, data: data };
                        });
                    })
                    .then(function (res) {
                        var el = document.getElementById(tempId);
                        if (el) el.remove();
                        if (res.ok && res.data && res.data.success && res.data.url) {
                            addScreenshotItem(res.data.url, res.data.full_url || res.data.url);
                        } else {
                            alert(res.data?.message || '{{ __('messages.error_occurred') }}');
                        }
                    })
                    .catch(function () {
                        var el = document.getElementById(tempId);
                        if (el) el.remove();
                        alert('{{ __('messages.error_occurred') }}');
                    });
                })(files[i]);
            }
        }

        if (screenshotsFileInput) {
            screenshotsFileInput.addEventListener('change', function () {
                uploadScreenshotFiles(this.files);
                this.value = '';
            });
        }

        if (btnAddScreenshotUrl) {
            btnAddScreenshotUrl.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var url = prompt('{{ __('messages.screenshot_url') }}:');
                if (url && url.trim()) {
                    url = url.trim();
                    addScreenshotItem(url, url);
                }
            });
        }

        if (dropzoneCreate) {
            ['dragenter', 'dragover'].forEach(function (ev) {
                dropzoneCreate.addEventListener(ev, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzoneCreate.classList.add('bg-white', 'border-primary');
                }, false);
            });
            ['dragleave', 'drop'].forEach(function (ev) {
                dropzoneCreate.addEventListener(ev, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzoneCreate.classList.remove('bg-white', 'border-primary');
                }, false);
            });
            dropzoneCreate.addEventListener('drop', function (e) {
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                    uploadScreenshotFiles(e.dataTransfer.files);
                }
            }, false);
        }

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-remove-screenshot');
            if (btn) {
                var item = btn.closest('.screenshot-preview-item');
                if (item) {
                    item.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.85)';
                    setTimeout(function () { item.remove(); }, 200);
                }
            }
        });

        // Summary sync
        var storeVersion = document.getElementById('store-version');
        var storePrice = document.getElementById('store-price');
        if (storeVersion) storeVersion.addEventListener('input', syncCreateSummary);
        if (storePrice) storePrice.addEventListener('input', syncCreateSummary);
        document.addEventListener('click', function (event) {
            if (event.target.closest('[data-store-source-tab]')) {
                window.setTimeout(syncCreateSummary, 0);
            }
        });
        syncCreateSummary();
    });

    window.triggerCategoryUpdate = function (selectElement) {
        var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            || document.querySelector('input[name="_token"]')?.value
            || '';
        var cat_s = selectElement ? selectElement.value : '';
        var sc_cat = document.getElementById('sc_cat')?.value || '';
        var storeCatEl = document.getElementById('storecat');

        if (!storeCatEl) return;

        if (!cat_s) {
            storeCatEl.innerHTML = storeCatEl.getAttribute('data-original') || storeCatEl.innerHTML;
            return;
        }

        if (!storeCatEl.getAttribute('data-original')) {
            storeCatEl.setAttribute('data-original', storeCatEl.innerHTML);
        }

        storeCatEl.innerHTML = '<div class="d-flex align-items-center text-primary"><div class="spinner-border spinner-border-sm me-2"></div><small>Loading categories...</small></div>';

        var formData = new FormData();
        formData.append('cat_s', cat_s);
        formData.append('sc_cat', sc_cat);
        formData.append('_token', token);

        fetch("{{ route('store.categories') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token
            },
            body: formData
        })
        .then(function (res) { return res.text(); })
        .then(function (html) {
            storeCatEl.innerHTML = html;
            if (typeof syncCreateSummary === 'function') syncCreateSummary();
        })
        .catch(function (err) {
            console.error('Category load error:', err);
            storeCatEl.innerHTML = storeCatEl.getAttribute('data-original');
            if (typeof syncCreateSummary === 'function') syncCreateSummary();
        });
    };

    function syncCreateSummary() {
        var version = document.getElementById('store-version');
        var price = document.getElementById('store-price');
        var category = document.getElementById('cat_s');
        var sourcePicker = document.querySelector('[data-store-source-picker]');
        var categoryText = '--';
        var sourceText = '{{ __('messages.upload') }}';

        if (category && category.options && category.selectedIndex >= 0 && category.value) {
            categoryText = category.options[category.selectedIndex].text;
        }

        if (sourcePicker && sourcePicker.dataset.mode === 'link') {
            sourceText = '{{ __('messages.ext_link') }}';
        }

        var verEl = document.querySelector('[data-store-create-version]');
        if (verEl) verEl.textContent = version && version.value ? version.value : 'v1.0';

        var priceEl = document.querySelector('[data-store-create-price]');
        if (priceEl) priceEl.textContent = price && price.value ? price.value : '--';

        var catEl = document.querySelector('[data-store-create-category]');
        if (catEl) catEl.textContent = categoryText;

        var srcEl = document.querySelector('[data-store-create-source]');
        if (srcEl) srcEl.textContent = sourceText;
    }
</script>

<script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stackedit = new Stackedit();
    document.querySelectorAll('.open-stackedit').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const textarea = document.querySelector(targetId);
            const nameInput = document.getElementById('store-name');
            const articleName = nameInput && nameInput.value ? nameInput.value : 'Product Content';
            
            stackedit.openFile({
                name: articleName,
                content: {
                    text: textarea.value
                }
            });

            const adjustIframe = () => {
                const iframe = document.querySelector('iframe[src*="stackedit.io"]');
                if (iframe) {
                    iframe.style.top = '0';
                    iframe.style.height = '100%';
                    iframe.style.zIndex = '9999';
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

<style>
.cursor-pointer { cursor: pointer; }
.transition-all { transition: all 0.3s ease; }
.hover-bg-white:hover { background-color: #fff !important; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
.border-dashed { border-style: dashed !important; }
</style>
@endsection
