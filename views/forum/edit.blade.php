@extends('theme::layouts.master')
@include('theme::forum._assets')

@section('content')
<div class="forum-rdx forum-rdx-form">
<!-- SECTION BANNER -->
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%;">
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/discussion-icon.png') }}">
    <p class="section-banner-title">{{ (isset($status) && $status->s_type == 4) ? __('messages.edit_gallery_post') : (isset($topic) ? __('messages.edit_topic') : __('messages.w_new_tpc')) }}</p>
</div>
<!-- /SECTION BANNER -->

<!-- ADS -->
@include('theme::partials.ads', ['id' => 4])

<div class="row g-4 flex-column-reverse flex-lg-row">
    <div class="col-lg-3">
        @include('theme::partials.widgets', ['place' => 3])
    </div>

    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 mb-4 forum-rdx-form-shell">
            <div class="card-header bg-transparent pt-4 pb-0 border-bottom-0">
                <h5 class="fw-bold mb-0 forum-rdx-form-header">{{ (isset($status) && $status->s_type == 4) ? __('messages.edit_gallery_post') : (isset($topic) ? __('messages.edit_topic') : __('messages.w_new_tpc')) }}</h5>
            </div>
            
            <div class="card-body p-4 p-md-5">
                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ isset($topic) ? route('forum.update', $topic->id) : route('forum.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($topic))
                        <input type="hidden" name="id" value="{{ $topic->id }}">
                    @endif

                    @if((int) ($topic->cat ?? 0) > 0)
                    <div class="mb-4">
                        <label for="name" class="form-label small fw-bold">{{ __('messages.sbj') }}</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-edit text-muted"></i></span>
                            <input type="text" id="name" name="name" class="form-control border-start-0 ps-0" value="{{ old('name', $topic->name ?? '') }}" required>
                        </div>
                    </div>
                    @else
                        <input type="hidden" name="name" value="{{ $topic->name ?? 'text' }}">
                    @endif

                    <div class="mb-4">
                        <label for="txt" class="form-label small fw-bold">{{ __('messages.content') }}</label>
                        <textarea id="editor1" name="txt" class="form-control" rows="16" required>{{ old('txt', $topic->txt ?? '') }}</textarea>
                    </div>

                    @if((int) ($topic->cat ?? 0) > 0 && (!isset($status) || $status->s_type != 4))
                    <div class="mb-4">
                        <label for="category" class="form-label small fw-bold"><i class="fa fa-folder text-muted me-1"></i>{{ __('messages.category_fallback') }}</label>
                        <select id="category" name="cat" class="form-select form-select-lg" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('cat', $topic->cat ?? '') == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <input type="hidden" name="cat" value="{{ $topic->cat ?? 0 }}">
                    @endif
                    
                    @if(!isset($topic))
                    <div class="mb-4">
                        <label for="type" class="form-label small fw-bold"><i class="fa fa-list text-muted me-1"></i>{{ __('messages.type') }}</label>
                        <select id="type" name="type" class="form-select form-select-lg" onchange="toggleImageUpload(this.value)">
                            <option value="100">{{ __('messages.spread') }}</option>
                            <option value="4">{{ __('messages.img') }}</option>
                        </select>
                    </div>

                    <div class="mb-4" id="image-upload-row" style="display: none;">
                        <label for="img" class="form-label small fw-bold">{{ __('messages.upload_image') }}</label>
                        <input type="file" id="img" name="img" class="form-control form-control-lg" accept="image/*">
                    </div>
                    @endif

                    @if((int) ($forumSettings['attachments_enabled'] ?? 1) === 1)
                    <div class="mb-4 p-4 bg-light rounded-4 border forum-rdx-attachment-box">
                        <label for="attachments" class="form-label small fw-bold">{{ __('messages.attachments') }}</label>
                        <input
                            type="file"
                            id="attachments"
                            name="attachments[]"
                            class="form-control"
                            multiple
                            accept=".{{ str_replace(',', ',.', $forumSettings['allowed_attachment_extensions'] ?? '') }}"
                        >
                        <div class="form-text mt-2 text-muted small">
                            <i class="fa fa-info-circle me-1"></i>
                            {{ __('messages.max_attachments_per_topic') }}: {{ $forumSettings['max_attachments_per_topic'] ?? 5 }} |
                            {{ __('messages.max_attachment_size') }}: {{ $forumSettings['max_attachment_size_kb'] ?? 10240 }} KB
                        </div>

                        @if(isset($topic) && $topic->attachments && $topic->attachments->isNotEmpty())
                        <div class="mt-4 pt-3 border-top">
                            <p class="small fw-bold mb-2">{{ __('messages.current_attachments') }}</p>
                            @foreach($topic->attachments as $attachment)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="delete_attachments[]" value="{{ $attachment->id }}" id="delete_att_{{ $attachment->id }}">
                                    <label class="form-check-label text-danger" for="delete_att_{{ $attachment->id }}">
                                        {{ __('messages.delete') }}: <span class="text-dark">{{ $attachment->original_name }} ({{ $attachment->human_size }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold d-flex align-items-center gap-2">
                            <i class="fa fa-paper-plane"></i>
                            {{ __('messages.spread') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/default.min.css" />
<script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/sceditor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/formats/xhtml.min.js"></script>
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var textarea = document.getElementById('editor1');
        if (textarea) {
            sceditor.create(textarea, {
                format: 'xhtml',
                style: 'https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/content/default.min.css',
                emoticons: {
                    dropdown: {
                        @foreach(\App\Models\Emoji::limit(10)->get() as $emoji)
                            '{{ $emoji->name }}': '{{ asset($emoji->img) }}',
                        @endforeach
                    }
                }
            });
        }
    });

    function toggleImageUpload(type) {
        var imageRow = document.getElementById('image-upload-row');
        if (imageRow) {
            if (type == '4') {
                imageRow.style.display = 'block';
            } else {
                imageRow.style.display = 'none';
            }
        }
    }
</script>
</div>
@endsection
