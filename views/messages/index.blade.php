@extends('theme::layouts.master')

@php
    $partnerConversationRouteKey = $partnerConversationRouteKey ?? ($partner ? \App\Models\Message::encodeConversationRouteKey(auth()->id(), $partner) : null);
@endphp

@section('content')
<div class="container py-4">
    <div class="row g-4" style="height: calc(100vh - 150px); min-height: 600px;">
        <!-- Conversations List -->
        <div class="col-lg-4 col-md-5 h-100">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column border border-light">
                <div class="card-header bg-white py-4 px-4 border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-black mb-0 text-dark">{{ __('messages.messages') }}</h4>
                        @if($conversations->count() > 0)
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 smaller fw-black">{{ $conversations->count() }} {{ __('messages.chats') ?? 'Chats' }}</span>
                        @endif
                    </div>
                    <!-- Search -->
                    <div class="search-box position-relative">
                        <i class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted opacity-50"></i>
                        <input type="text" class="form-control bg-light border-0 rounded-pill py-2 ps-5" placeholder="{{ __('messages.search') }}..." id="chat-search">
                    </div>
                </div>
                
                <div class="card-body p-0 flex-grow-1 overflow-auto">
                    <div class="list-group list-group-flush border-top border-light" id="conversation-list">
                        @forelse($conversations as $conversation)
                            @php
                                $otherUser = $conversation['user'];
                                $lastMessage = $conversation['message'];
                                $conversationKey = $conversation['route_key'] ?? \App\Models\Message::encodeConversationRouteKey(auth()->id(), $otherUser);
                                $isCurrent = $partner && (int) $partner->id === (int) $otherUser->id;
                                $isUnread = $conversation['unread'];
                                $previewSource = trim(strip_tags((string) ($lastMessage->text ?? '')));
                                if ($previewSource === '' && !empty($lastMessage->attachment_path)) {
                                    $previewSource = __('messages.file');
                                }
                                $previewText = \Illuminate\Support\Str::limit($previewSource, 96);
                                $timeLabel = \Carbon\Carbon::createFromTimestamp($lastMessage->time)->diffForHumans(null, true);
                            @endphp
                            <a href="{{ route('messages.show', $conversationKey) }}" 
                               class="list-group-item list-group-item-action border-0 px-4 py-3 transition-all {{ $isCurrent ? 'bg-primary bg-opacity-5' : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <div class="avatar-wrap p-1 bg-white rounded-circle shadow-sm border border-light">
                                            <img src="{{ $otherUser->avatarUrl() }}" alt="" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                        </div>
                                        @if($otherUser->isOnline())
                                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle shadow-sm" style="width: 14px; height: 14px;"></span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="fw-black mb-0 text-truncate {{ $isUnread ? 'text-dark' : 'text-muted' }} fs-6">{{ $otherUser->username }}</h6>
                                            <small class="text-muted smaller fw-bold opacity-75">{{ $timeLabel }}</small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0 smaller text-truncate flex-grow-1 {{ $isUnread ? 'fw-black text-dark' : 'text-muted opacity-75' }}">
                                                @if($lastMessage->us_env === auth()->id()) <i class="fa fa-reply me-1 smaller opacity-50"></i> @endif
                                                {{ $previewText }}
                                            </p>
                                            @if($isUnread)
                                                <span class="badge bg-primary rounded-pill smaller fw-black ms-2"><i class="fa fa-circle fs-6"></i></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-5 text-center bg-light bg-opacity-50 m-4 rounded-4">
                                <i class="fa fa-comment-dots fa-3x mb-3 text-muted opacity-25"></i>
                                <p class="mb-0 text-muted fw-bold">{{ __('messages.no_conversations_found') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-lg-8 col-md-7 h-100">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden d-flex flex-column border border-light">
                @if($partner)
                    <!-- Chat Header -->
                    <div class="card-header bg-white py-3 px-4 border-bottom shadow-sm z-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('profile.show', $partner->username) }}" class="me-3 position-relative">
                                    <div class="avatar-wrap p-1 bg-white rounded-circle shadow-sm border border-light">
                                        <img src="{{ $partner->avatarUrl() }}" alt="" class="rounded-circle" width="45" height="45" style="object-fit: cover;">
                                    </div>
                                    @if($partner->isOnline())
                                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle shadow-sm" style="width: 12px; height: 12px;"></span>
                                    @endif
                                </a>
                                <div>
                                    <h6 class="fw-black mb-0">
                                        <a href="{{ route('profile.show', $partner->username) }}" class="text-dark text-decoration-none hover-primary">{{ $partner->username }}</a>
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <span class="badge rounded-circle bg-{{ $partner->isOnline() ? 'success' : 'secondary' }} p-1 me-2" style="width: 8px; height: 8px;"></span>
                                        <small class="text-muted fw-black smaller opacity-75">
                                            {{ $partner->isOnline() ? __('messages.online') : __('messages.offline') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle p-2 shadow-sm border" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v text-muted"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2">
                                    <li><a class="dropdown-item py-2 rounded-3 fw-bold smaller" href="{{ route('profile.show', $partner->username) }}"><i class="fa fa-user me-2 text-primary"></i> {{ __('messages.view_profile') }}</a></li>
                                    <li><hr class="dropdown-divider bg-light"></li>
                                    <li><button class="dropdown-item py-2 rounded-3 fw-bold smaller text-danger" onclick="confirm('Are you sure?')"><i class="fa fa-trash-alt me-2"></i> {{ __('messages.delete_conversation') }}</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="card-body p-4 overflow-auto bg-light bg-opacity-25" id="chat-messages-container">
                        <div class="text-center mb-5">
                            <span class="badge bg-white text-muted border border-light py-2 px-4 rounded-pill smaller fw-black shadow-sm text-uppercase letter-spacing-1">
                                <i class="fa fa-shield-alt me-2 text-success"></i> {{ __('messages.end_to_end_encrypted') }}
                            </span>
                        </div>

                        <div class="d-flex flex-column gap-4" id="chat-stack">
                            @foreach($messages as $msg)
                                @php
                                    $isMe = (int) $msg->us_env === (int) auth()->id();
                                    $attachmentPath = $msg->attachment_path ?? null;
                                    $attachmentName = trim((string) ($msg->attachment_name ?? ''));
                                    $attachmentSize = (int) ($msg->attachment_size ?? 0);
                                    $attachmentLabel = $attachmentName !== '' ? $attachmentName : basename((string) $attachmentPath);
                                    $attachmentExtension = strtolower(pathinfo($attachmentLabel, PATHINFO_EXTENSION));
                                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'avif'];
                                    $isImageAttachment = !empty($attachmentPath) && in_array($attachmentExtension, $imageExtensions, true);
                                    $attachmentInlineUrl = !empty($attachmentPath) ? route('messages.attachment', ['id' => $msg->id_msg, 'inline' => 1]) : '#';
                                    $attachmentDownloadUrl = !empty($attachmentPath) ? route('messages.attachment', ['id' => $msg->id_msg, 'download' => 1]) : '#';
                                @endphp
                                <div class="message-wrapper d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="message-content" style="max-width: 80%;">
                                        <div class="message-bubble p-3 rounded-4 shadow-sm {{ $isMe ? 'bg-primary text-white rounded-bottom-end-0 border-0' : 'bg-white text-dark rounded-bottom-start-0 border border-light' }}">
                                            @if(!empty($attachmentPath))
                                                @if($isImageAttachment)
                                                    <a href="{{ $attachmentInlineUrl }}" target="_blank" class="d-block mb-3 overflow-hidden rounded-3 shadow-sm">
                                                        <img src="{{ $attachmentInlineUrl }}" alt="{{ $attachmentLabel }}" class="img-fluid transition-all hover-scale" style="max-height: 300px;">
                                                    </a>
                                                @else
                                                    <div class="file-attachment d-flex align-items-center p-3 mb-3 rounded-3 {{ $isMe ? 'bg-white bg-opacity-10 border border-white border-opacity-25' : 'bg-light border border-light' }}">
                                                        <div class="rounded-circle bg-{{ $isMe ? 'white' : 'primary' }} bg-opacity-20 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fa fa-file-alt {{ $isMe ? 'text-white' : 'text-primary' }}"></i>
                                                        </div>
                                                        <div class="flex-grow-1 min-width-0 me-3">
                                                            <div class="smaller fw-black text-truncate {{ $isMe ? 'text-white' : 'text-dark' }}">{{ $attachmentLabel }}</div>
                                                            @if($attachmentSize > 0)
                                                                <small class="smallest {{ $isMe ? 'text-white-50' : 'text-muted' }}">{{ number_format($attachmentSize / 1024, 1) }} KB</small>
                                                            @endif
                                                        </div>
                                                        <a href="{{ $attachmentDownloadUrl }}" class="btn btn-sm p-2 rounded-circle {{ $isMe ? 'btn-light text-primary' : 'btn-primary' }}"><i class="fa fa-download"></i></a>
                                                    </div>
                                                @endif
                                            @endif
                                            
                                            <p class="mb-0 fs-6 lh-base">{!! nl2br(e($msg->text)) !!}</p>
                                        </div>
                                        <div class="mt-1 px-1 {{ $isMe ? 'text-end' : 'text-start' }}">
                                            <small class="smallest text-muted fw-bold opacity-50">{{ \Carbon\Carbon::createFromTimestamp($msg->time)->format('H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Chat Footer -->
                    <div class="card-footer bg-white p-4 border-top">
                        <form action="{{ route('messages.store', $partnerConversationRouteKey) }}" method="POST" enctype="multipart/form-data" id="chat-form">
                            @csrf
                            <div class="chat-input-wrapper bg-light rounded-4 p-2 border border-light shadow-sm">
                                <div class="d-flex align-items-end gap-2">
                                    <label class="btn btn-white border-0 rounded-pill p-2 flex-shrink-0 transition-all hover-translate-y shadow-sm" title="{{ __('messages.attach_file') ?? 'Attach' }}">
                                        <i class="fa fa-paperclip text-muted"></i>
                                        <input type="file" name="attachment" class="d-none" id="chat-file-input">
                                    </label>
                                    <div class="flex-grow-1">
                                        <textarea name="message" class="form-control border-0 bg-transparent py-2 fs-6 shadow-none" rows="1" placeholder="{{ __('messages.type_a_message') }}..." required style="resize: none;"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary rounded-pill p-2 px-3 flex-shrink-0 shadow-sm transition-all hover-translate-y">
                                        <i class="fa fa-paper-plane me-1"></i> <span class="smaller fw-black d-none d-sm-inline-block">{{ __('messages.send') }}</span>
                                    </button>
                                </div>
                                <div id="file-preview" class="mt-2 px-3 py-2 bg-white rounded-pill border border-light d-none align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-file-alt text-primary me-2 smaller"></i>
                                        <span id="file-name" class="smallest fw-bold text-muted text-truncate" style="max-width: 200px;"></span>
                                    </div>
                                    <button type="button" class="btn btn-link btn-sm text-danger p-0 ms-2 text-decoration-none" id="clear-file"><i class="fa fa-times-circle"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-5 bg-light bg-opacity-50">
                        <div class="rounded-circle bg-white shadow-sm p-5 mb-4 border border-light">
                            <i class="fa fa-comments fa-4x text-primary opacity-25"></i>
                        </div>
                        <h4 class="fw-black text-dark mb-2">{{ __('messages.select_conversation') }}</h4>
                        <p class="text-muted fs-6 mb-4">{{ __('messages.select_conversation_desc') }}</p>
                        <button class="btn btn-primary rounded-pill px-4 fw-black shadow-sm transition-all hover-translate-y" data-bs-toggle="modal" data-bs-target="#newChatModal">
                            <i class="fa fa-plus me-2"></i> {{ __('messages.new_message') ?? 'New Message' }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .smaller { font-size: 0.85rem; }
    .smallest { font-size: 0.7rem; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-3px); }
    .hover-scale:hover { transform: scale(1.02); }
    .hover-primary:hover { color: #615dfa !important; }
    .min-width-0 { min-width: 0; }
    .rounded-bottom-end-0 { border-bottom-right-radius: 0 !important; }
    .rounded-bottom-start-0 { border-bottom-left-radius: 0 !important; }
    #chat-messages-container { scrollbar-width: thin; scrollbar-color: #dee2e6 transparent; }
    #chat-messages-container::-webkit-scrollbar { width: 6px; }
    #chat-messages-container::-webkit-scrollbar-thumb { background-color: #dee2e6; border-radius: 10px; }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto scroll to bottom
        const container = document.getElementById('chat-messages-container');
        if (container) container.scrollTop = container.scrollHeight;

        // Auto resize textarea
        const textarea = document.querySelector('textarea[name="message"]');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
                if (this.scrollHeight > 150) this.style.overflowY = 'auto';
                else this.style.overflowY = 'hidden';
            });
        }

        // File preview
        const fileInput = document.getElementById('chat-file-input');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const clearFile = document.getElementById('clear-file');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    fileName.textContent = this.files[0].name;
                    filePreview.classList.remove('d-none');
                    filePreview.classList.add('d-flex');
                }
            });
            clearFile.addEventListener('click', function() {
                fileInput.value = '';
                filePreview.classList.add('d-none');
                filePreview.classList.remove('d-flex');
            });
        }

        // Conversation search filter
        const searchInput = document.getElementById('chat-search');
        const conversationList = document.getElementById('conversation-list');
        if (searchInput && conversationList) {
            searchInput.addEventListener('keyup', function() {
                const term = this.value.toLowerCase();
                const items = conversationList.querySelectorAll('.list-group-item');
                items.forEach(item => {
                    const text = item.innerText.toLowerCase();
                    if (text.includes(term)) item.style.display = 'block';
                    else item.style.display = 'none';
                });
            });
        }
    });
</script>
@endpush
