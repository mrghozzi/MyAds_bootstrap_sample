@auth
<div id="forumComposer" class="forum-modern-composer" aria-hidden="true">
    <div class="forum-modern-composer-header" onclick="ForumComposer.minimize()">
        <div class="d-flex align-items-center gap-2">
            <i class="fa fa-pencil-square text-primary fs-5"></i>
            <span id="forumComposerTitleLabel" class="fw-bold text-dark fs-6">{{ __('messages.w_new_tpc') ?? 'Start a Discussion' }}</span>
        </div>
        <div class="d-flex align-items-center gap-1" onclick="event.stopPropagation()">
            <button type="button" class="btn btn-sm btn-light rounded-circle" onclick="ForumComposer.togglePreview()" title="{{ __('messages.composer_preview') ?? 'Toggle Preview' }}">
                <i class="fa fa-eye"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light rounded-circle" onclick="ForumComposer.toggleFullscreen()" title="{{ __('messages.composer_fullscreen') ?? 'Fullscreen' }}">
                <i class="fa fa-expand"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light rounded-circle" onclick="ForumComposer.minimize()" title="{{ __('messages.composer_minimize') ?? 'Minimize' }}">
                <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light rounded-circle" onclick="ForumComposer.close()" title="{{ __('messages.composer_close') ?? 'Close' }}">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>

    <div class="forum-modern-composer-body">
        <form id="forumComposerForm" method="POST" action="{{ route('forum.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="forumComposerAlert" class="alert alert-danger d-none mb-3 py-2 px-3 small"></div>

            <div class="row g-2 mb-3">
                <div class="col-md-5">
                    <select id="forumComposerCategory" name="cat" class="form-select rounded-3" required>
                        <option value="">{{ __('messages.composer_select_category') ?? 'Select Category...' }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" class="fw-bold">{{ $cat->name }}</option>
                            @if($cat->children && $cat->children->isNotEmpty())
                                @foreach($cat->children as $sub)
                                    <option value="{{ $sub->id }}">&nbsp;&nbsp;&nbsp;↳ {{ $sub->name }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-7">
                    <input 
                        type="text" 
                        id="forumComposerTitle" 
                        name="name" 
                        class="form-control rounded-3" 
                        placeholder="{{ __('messages.composer_discussion_title') ?? 'Discussion Title...' }}" 
                        required 
                        autocomplete="off"
                    >
                </div>
            </div>

            <!-- Forum Markdown Editor Box -->
            <div class="forum-modern-editor-box mb-3">
                <div class="forum-modern-editor-toolbar">
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '**', '**', 'bold text')" title="Bold"><i class="fa fa-bold"></i></button>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '*', '*', 'italic text')" title="Italic"><i class="fa fa-italic"></i></button>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '~~', '~~', 'strikethrough')" title="Strike"><i class="fa fa-strikethrough"></i></button>
                    <div class="forum-modern-tool-separator"></div>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '# ', '', 'Heading')" title="Heading"><i class="fa fa-header"></i></button>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '`', '`', 'code')" title="Code"><i class="fa fa-code"></i></button>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '\n```\n', '\n```\n', 'code block')" title="Code block"><i class="fa fa-file-code-o"></i></button>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '> ', '', 'quote text')" title="Quote"><i class="fa fa-quote-left"></i></button>
                    <div class="forum-modern-tool-separator"></div>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '[', '](https://)', 'link title')" title="Link"><i class="fa fa-link"></i></button>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.wrap(document.getElementById('forumComposerText'), '![alt](', ')', 'https://')" title="Image"><i class="fa fa-image"></i></button>
                    <button type="button" class="forum-modern-tool-btn" onclick="ForumEditor.insert(document.getElementById('forumComposerText'), '\n- Item 1\n- Item 2\n')" title="List"><i class="fa fa-list-ul"></i></button>
                </div>

                <div class="forum-modern-editor-split">
                    <textarea 
                        id="forumComposerText" 
                        name="txt" 
                        class="form-control" 
                        placeholder="{{ __('messages.forum_post_composer_placeholder') ?? 'Write your discussion content using Markdown...' }}" 
                        rows="7" 
                        required
                    ></textarea>
                    <div id="forumComposerPreview" class="forum-modern-editor-preview"></div>
                </div>
            </div>

            <!-- Composer Footer Actions -->
            <div class="d-flex align-items-center justify-content-between pt-2">
                <span class="text-muted smaller">
                    <i class="fa fa-info-circle me-1"></i> Markdown formatting supported
                </span>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-3 fw-bold small border" onclick="ForumComposer.close()">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit" id="forumComposerSubmitBtn" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa fa-paper-plane me-1"></i>
                        <span>{{ __('messages.post') ?? 'Post Discussion' }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endauth
