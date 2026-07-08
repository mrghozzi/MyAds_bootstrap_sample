@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/default.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/sceditor.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/formats/xhtml.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/jquery.sceditor.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sceditor@3/languages/{{ app()->getLocale() }}.js"></script>

    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                <i class="fa {{ isset($topic) ? 'fa-edit' : 'fa-plus-circle' }} fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-bold mb-1">{{ isset($topic) ? __('messages.e_topic') : __('messages.w_new_tpc') }}</h1>
                <p class="mb-0 text-white-50 small">{{ __('messages.forum') }} / {{ isset($topic) ? __('messages.edit') : __('messages.create') }}</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ isset($topic) ? route('forum.update', $topic->id) : route('forum.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($topic))
                            <input type="hidden" name="id" value="{{ $topic->id }}">
                        @endif

                        @if(isset($editType) && $editType == 7867)
                            <input type="hidden" name="name" value="{{ old('name', $topic->name ?? '') }}" />
                        @else
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">{{ __('messages.sbj') }}</label>
                                <input type="text" id="name" name="name" class="form-control form-control-lg rounded-3" value="{{ old('name', $topic->name ?? '') }}" placeholder="{{ __('messages.sbj') }}..." required>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('messages.content') }}</label>
                            <textarea id="editor1" name="txt" rows="16" class="form-control">{{ old('txt', $topic->txt ?? '') }}</textarea>
                        </div>

                        <div class="row g-4 mb-4">
                            @if(!isset($editType) || $editType != 7867)
                                <div class="col-md-6">
                                    <label for="categ" class="form-label fw-bold">{{ __('messages.category_fallback') ?? 'Category' }}</label>
                                    <select id="categ" name="categ" class="form-select rounded-3">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (old('categ', $topic->cat ?? ($currentCategory->id ?? '')) == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if((int) ($forumSettings['attachments_enabled'] ?? 1) === 1)
                                <div class="col-md-6">
                                    <label for="attachments" class="form-label fw-bold">{{ __('messages.attachments') }}</label>
                                    <input type="file" id="attachments" name="attachments[]" class="form-control rounded-3" multiple accept=".{{ str_replace(',', ',.', $forumSettings['allowed_attachment_extensions'] ?? '') }}">
                                    <div class="form-text smaller">
                                        {{ __('messages.max_attachments_per_topic') }}: {{ $forumSettings['max_attachments_per_topic'] ?? 5 }} |
                                        {{ __('messages.max_attachment_size') }}: {{ $forumSettings['max_attachment_size_kb'] ?? 10240 }} KB
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if(isset($topic) && $topic->attachments && $topic->attachments->isNotEmpty())
                            <div class="card border bg-light rounded-4 p-3 mb-4">
                                <h6 class="fw-bold mb-3 small text-uppercase text-muted">{{ __('messages.current_attachments') }}</h6>
                                <div class="row g-2">
                                    @foreach($topic->attachments as $attachment)
                                        <div class="col-md-6">
                                            <div class="form-check bg-white p-3 rounded-3 border">
                                                <input class="form-check-input ms-0 me-2" type="checkbox" name="delete_attachments[]" value="{{ $attachment->id }}" id="attach{{ $attachment->id }}">
                                                <label class="form-check-label text-truncate d-block" for="attach{{ $attachment->id }}" title="{{ $attachment->original_name }}">
                                                    {{ __('messages.delete') }}: {{ $attachment->original_name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <hr class="my-4 opacity-10">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn btn-light rounded-pill px-4 fw-bold">{{ __('messages.cancel') }}</a>
                            <button type="submit" name="submit" value="Publish" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fa fa-paper-plane me-2"></i> {{ __('messages.spread') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $dropdownEmojis = ($emojis ?? collect())->take(10);
    $moreEmojis = ($emojis ?? collect())->slice(10);
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    var textarea = document.getElementById('editor1');
    if (!textarea || typeof sceditor === 'undefined') {
        return;
    }
    sceditor.create(textarea, {
        format: 'xhtml',
        locale: '{{ app()->getLocale() }}',
        emoticons: {
            dropdown: {
                @foreach($dropdownEmojis as $emoji)
                    '{{ $emoji->name }}': '{{ asset($emoji->img) }}',
                @endforeach
            }@if($moreEmojis->isNotEmpty()),
            more: {
                @foreach($moreEmojis as $emoji)
                    '{{ $emoji->name }}': '{{ asset($emoji->img) }}',
                @endforeach
            }@endif
        },
        style: 'https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/content/default.min.css',
        width: '100%',
        height: '400px'
    });
});
</script>
@endsection
