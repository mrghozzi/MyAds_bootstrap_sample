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
        var openImgUpload = document.getElementById('OpenImgUpload');
        var imguploadInput = document.getElementById('imgupload');
        if (openImgUpload && imguploadInput) {
            openImgUpload.addEventListener('click', function () {
                imguploadInput.click();
            });
        }
    });
</script>

<script>
    window.triggerCategoryUpdate = function (selectElement) {
        if (typeof jQuery === 'undefined') {
            console.warn("jQuery is not yet loaded, retrying in 100ms...");
            setTimeout(function() { window.triggerCategoryUpdate(selectElement); }, 100);
            return;
        }

        var $ = jQuery;
        var token = $('meta[name="csrf-token"]').attr('content');
        var cat_s = $(selectElement).val();
        var sc_cat = $('#sc_cat').val() || '';
        
        console.log("triggerCategoryUpdate running for: " + cat_s);

        if (!cat_s) {
            $("#storecat").html($("#storecat").attr('data-original') || $("#storecat").html());
            return;
        }

        // Store original markup for error recovery if not already stored
        if (!$("#storecat").attr('data-original')) {
            $("#storecat").attr('data-original', $("#storecat").html());
        }

        $("#storecat").html('<div class="d-flex align-items-center text-primary"><div class="spinner-border spinner-border-sm me-2"></div><small>Loading categories...</small></div>');

        $.ajax({
            type: "POST",
            url: "{{ route('store.categories') }}",
            data: { cat_s: cat_s, sc_cat: sc_cat, _token: token },
            cache: false,
            success: function (html) {
                console.log("AJAX Success for " + cat_s);
                $("#storecat").html(html);
                if (typeof syncCreateSummary === 'function') syncCreateSummary();
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: " + error);
                $("#storecat").html($("#storecat").attr('data-original'));
                if (typeof syncCreateSummary === 'function') syncCreateSummary();
            }
        });
    };
</script>

<script>
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

        document.querySelector('[data-store-create-version]').textContent = version && version.value ? version.value : 'v1.0';
        document.querySelector('[data-store-create-price]').textContent = price && price.value ? price.value : '--';
        document.querySelector('[data-store-create-category]').textContent = categoryText;
        document.querySelector('[data-store-create-source]').textContent = sourceText;
    }

    function initStoreReady() {
        if (typeof jQuery === 'undefined') {
            setTimeout(initStoreReady, 50);
            return;
        }

        var $ = jQuery;
        $(document).ready(function () {
            var token = $('meta[name="csrf-token"]').attr('content');

            $('#imgupload').change(function () {
                $("#showImgUpload").html('<div class="d-flex align-items-center justify-content-center text-primary"><div class="spinner-border spinner-border-sm me-2"></div><small>Uploading image...</small></div>');
                var file = this.files[0];
                var form = new FormData();
                form.append('fimg', file);
                form.append('_token', token);
                $.ajax({
                    url: "{{ route('status.upload_image') }}",
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form,
                    success: function (response) {
                        $('#showImgUpload').html(response);
                    }
                });
            });

            $('.sname').change(function () {
                $("#msg_name").html('<div class="spinner-border spinner-border-sm text-primary"></div>');
                var sname = $(this).val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('store.verify_name') }}",
                    data: { sname: sname, _token: token },
                    cache: false,
                    success: function (html) {
                        $("#msg_name").html(html);
                    }
                });
            });

            $('#store-version, #store-price').on('input', syncCreateSummary);
            document.addEventListener('click', function (event) {
                if (event.target.closest('[data-store-source-tab]')) {
                    window.setTimeout(syncCreateSummary, 0);
                }
            });

            syncCreateSummary();
        });
    }

    initStoreReady();
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
