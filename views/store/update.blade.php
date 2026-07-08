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
                    <div class="card-body text-center">
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
    function syncUpdateSummary() {
        var version = document.getElementById('upd-version');
        var price = document.getElementById('pts_update');
        var sourcePicker = document.querySelector('[data-store-source-picker]');
        var sourceText = '{{ __('messages.upload') }}';

        if (sourcePicker && sourcePicker.dataset.mode === 'link') {
            sourceText = '{{ __('messages.ext_link') }}';
        }

        document.querySelector('[data-store-update-version]').textContent = version && version.value ? version.value : '{{ $latestVersionName }}';
        document.querySelector('[data-store-update-price]').textContent = price && price.value ? price.value : '{{ $product->o_order }}';
        document.querySelector('[data-store-update-source]').textContent = sourceText;
    }

    document.getElementById('OpenImgUploadUpdate').addEventListener('click', function () {
        document.getElementById('imgupload_update').click();
    });

    $(document).ready(function () {
        var token = $('meta[name="csrf-token"]').attr('content');

        $('#imgupload_update').change(function () {
            $("#showImgUploadUpdate").html("<div class='progress'><div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width:100%'> Uploading </div></div>");
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
                    $('#showImgUploadUpdate').html(response);
                }
            });
        });

        $('#upd-version, #pts_update').on('input', syncUpdateSummary);
        document.addEventListener('click', function (event) {
            if (event.target.closest('[data-store-source-tab]')) {
                window.setTimeout(syncUpdateSummary, 0);
            }
        });

        syncUpdateSummary();
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
