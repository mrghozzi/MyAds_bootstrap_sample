@extends('theme::layouts.master')
@include('theme::forum._assets')

@php
    $activeEditor = \App\Services\RichTextEditorService::getActiveEditor();
@endphp

@section('content')
<div class="container py-4">
    @if($activeEditor === 'quill')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    @elseif($activeEditor === 'sceditor')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/default.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/sceditor.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/formats/xhtml.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sceditor@3/minified/jquery.sceditor.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sceditor@3/languages/{{ app()->getLocale() }}.js"></script>
    @endif
    @php
        \App\Helpers\Hooks::do_action('render_custom_editor_assets', $activeEditor);
    @endphp

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
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label fw-bold mb-0">{{ __('messages.content') }}</label>
                                <span class="badge bg-light text-muted border text-uppercase smaller">{{ strtoupper($activeEditor) }}</span>
                            </div>
                            @if($activeEditor === 'myads_markdown')
                                <div class="forum-modern-editor-box">
                                    <div class="forum-modern-editor-toolbar">
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '**', '**', 'bold text')" title="Bold"><i class="fa fa-bold"></i></button>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '*', '*', 'italic text')" title="Italic"><i class="fa fa-italic"></i></button>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '~~', '~~', 'strikethrough')" title="Strike"><i class="fa fa-strikethrough"></i></button>
                                        <div class="forum-modern-tool-separator"></div>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '# ', '', 'Heading')" title="Heading"><i class="fa fa-header"></i></button>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '`', '`', 'code')" title="Code"><i class="fa fa-code"></i></button>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '\n```\n', '\n```\n', 'code block')" title="Code block"><i class="fa fa-file-code-o"></i></button>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '> ', '', 'quote text')" title="Quote"><i class="fa fa-quote-left"></i></button>
                                        <div class="forum-modern-tool-separator"></div>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '[', '](https://)', 'link title')" title="Link"><i class="fa fa-link"></i></button>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('editor1'), '![alt](', ')', 'https://')" title="Image"><i class="fa fa-image"></i></button>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.insert(document.getElementById('editor1'), '\n- Item 1\n- Item 2\n')" title="List"><i class="fa fa-list-ul"></i></button>
                                        <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.insert(document.getElementById('editor1'), ' 😊 ')" title="Emoji"><i class="fa fa-smile-o"></i></button>
                                        <button type="button" class="forum-modern-tool-btn ms-auto" onclick="toggleSplitPreview()" title="{{ __('messages.preview') ?? 'Preview' }}"><i class="fa fa-eye"></i> <span class="smaller ms-1 d-none d-sm-inline">{{ __('messages.preview') ?? 'Preview' }}</span></button>
                                    </div>
                                    <div class="forum-modern-editor-split">
                                        <textarea id="editor1" name="txt" rows="14" class="form-control rounded-0" placeholder="{{ __('messages.forum_post_composer_placeholder') ?? 'Write your discussion content using Markdown...' }}" required>{{ old('txt', $topic->txt ?? '') }}</textarea>
                                        <div id="editorPreview" class="forum-modern-editor-preview"></div>
                                    </div>
                                </div>
                            @elseif($activeEditor === 'quill')
                                <div id="quill-editor" style="min-height: 250px;">{!! old('txt', $topic->txt ?? '') !!}</div>
                                <input type="hidden" name="txt" id="editor1" value="{{ old('txt', $topic->txt ?? '') }}">
                            @else
                                <textarea id="editor1" name="txt" rows="16" class="form-control">{{ old('txt', $topic->txt ?? '') }}</textarea>
                            @endif
                        </div>

                        <div class="row g-4 mb-4">
                            @if(!isset($editType) || $editType != 7867)
                                <div class="col-md-6">
                                    <label for="categ" class="form-label fw-bold">{{ __('messages.category_fallback') ?? 'Category' }}</label>
                                    <select id="categ" name="categ" class="form-select rounded-3" required>
                                        @php
                                            $rootCategories = $categories->whereNull('parent_id');
                                            if ($rootCategories->isEmpty()) {
                                                $rootCategories = $categories->where('parent_id', 0);
                                            }
                                            if ($rootCategories->isEmpty()) {
                                                $rootCategories = $categories;
                                            }
                                            $selectedCatId = (int) old('categ', $topic->cat ?? ($currentCategory->id ?? ''));
                                        @endphp
                                        @foreach($rootCategories as $category)
                                            <option value="{{ $category->id }}" class="fw-bold" {{ $selectedCatId === (int) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @if($category->children && $category->children->isNotEmpty())
                                                @foreach($category->children as $sub)
                                                    <option value="{{ $sub->id }}" {{ $selectedCatId === (int) $sub->id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp;&nbsp;↳ {{ $sub->name }}
                                                    </option>
                                                @endforeach
                                            @endif
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
function toggleSplitPreview() {
    const preview = document.getElementById('editorPreview');
    const textarea = document.getElementById('editor1');
    if (!preview || !textarea) return;
    if (preview.classList.contains('is-visible')) {
        preview.classList.remove('is-visible');
    } else {
        preview.classList.add('is-visible');
        if (typeof ForumEditor !== 'undefined') {
            preview.innerHTML = ForumEditor.toHtml(textarea.value);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var activeEditor = '{{ $activeEditor }}';
    var textarea = document.getElementById('editor1');

    if (activeEditor === 'myads_markdown') {
        const preview = document.getElementById('editorPreview');
        if (textarea && preview) {
            textarea.addEventListener('input', function() {
                if (preview.classList.contains('is-visible') && typeof ForumEditor !== 'undefined') {
                    preview.innerHTML = ForumEditor.toHtml(this.value);
                }
            });
        }
    } else if (activeEditor === 'quill' && typeof Quill !== 'undefined') {
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: '{{ __('messages.content') }}...'
        });
        quill.on('text-change', function() {
            var hiddenInput = document.getElementById('editor1');
            if (hiddenInput) {
                hiddenInput.value = quill.root.innerHTML;
            }
        });
    } else if (activeEditor === 'sceditor' && typeof sceditor !== 'undefined' && textarea) {
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
    }

    @php
        \App\Helpers\Hooks::do_action('render_custom_editor_js', 'editor1', $activeEditor);
    @endphp
});
</script>
@endsection
