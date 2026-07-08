@php
    $linkzipValue = $linkzipValue ?? '';
    $sourceMode = $sourceMode ?? (filter_var($linkzipValue, FILTER_VALIDATE_URL) ? 'link' : 'upload');
    $uploadValue = $sourceMode === 'upload' ? $linkzipValue : '';
    $linkValue = $sourceMode === 'link' ? $linkzipValue : '';
    $uploadLabel = $uploadValue ? basename($uploadValue) : '';
    $linkInputId = $linkInputId ?? 'store-direct-link';
@endphp

<div class="card border-0 shadow-sm rounded-4 store-editor-card mb-4">
    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">{{ __('messages.file') }}</div>
    <div class="card-body p-4">
        <div
            class="store-source-picker"
            data-store-source-picker
            data-initial-mode="{{ $sourceMode }}"
            data-link-value="{{ $linkValue }}"
            data-upload-value="{{ $uploadValue }}"
            data-uploading-label="{{ __('messages.uploading') }}"
            data-upload-error-label="{{ __('zipfile') }}"
        >
            <input type="hidden" name="linkzip" value="{{ $linkzipValue }}" data-store-source-final>

            <div class="store-source-picker__toggle">
                <button type="button" class="button secondary store-source-picker__button" data-store-source-tab="upload" aria-pressed="{{ $sourceMode === 'upload' ? 'true' : 'false' }}">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    {{ __('messages.upload') }}
                </button>
                <button type="button" class="button secondary store-source-picker__button" data-store-source-tab="link" aria-pressed="{{ $sourceMode === 'link' ? 'true' : 'false' }}">
                    <i class="fa fa-link" aria-hidden="true"></i>
                    {{ __('messages.ext_link') }}
                </button>
            </div>

            <div class="store-source-picker__status">
                <span
                    class="store-source-picker__status-badge"
                    data-store-source-status
                    data-upload-label="{{ __('messages.upload') }}"
                    data-link-label="{{ __('messages.ext_link') }}"
                >
                    {{ $sourceMode === 'upload' ? __('messages.upload') : __('messages.ext_link') }}
                </span>
                <p
                    class="store-source-picker__hint"
                    data-store-source-hint
                    data-upload-hint="{{ __('zipfile') }}"
                    data-link-hint="{{ __('messages.ext_link_hint') }}"
                >
                    {{ $sourceMode === 'upload' ? __('zipfile') : __('messages.ext_link_hint') }}
                </p>
            </div>

            <div class="store-source-picker__panel" data-store-source-panel="upload">
                <input
                    type="file"
                    class="form-control store-source-picker__file-input"
                    accept=".zip"
                    data-store-source-upload-input
                    data-store-source-upload-url="{{ route('store.upload_zip') }}"
                >
                <div class="store-source-picker__upload-result" data-store-source-upload-result>
                    @if($uploadValue !== '')
                        <div class="store-source-upload-result" data-upload-path="{{ $uploadValue }}" data-upload-name="{{ $uploadLabel }}">
                            <img src="{{ theme_asset('img/fzip.png') }}" alt="{{ __('messages.file') }}">
                            <div>
                                <p class="store-source-upload-result__name">{{ $uploadLabel }}</p>
                                <p class="store-source-upload-result__meta">{{ $uploadValue }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="store-source-picker__panel" data-store-source-panel="link">
                <div class="form-input small active store-source-picker__link-input-wrap">
                    <label for="{{ $linkInputId }}">{{ __('messages.ext_link') }}</label>
                    <input
                        type="url"
                        id="{{ $linkInputId }}"
                        class="form-control"
                        placeholder="https://example.com/file.zip"
                        value="{{ $linkValue }}"
                        data-store-source-link-input
                    >
                </div>
            </div>
        </div>
    </div>
</div>
