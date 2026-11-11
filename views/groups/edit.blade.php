@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: linear-gradient(135deg, rgba(255,107,61,0.95), rgba(97,93,250,0.92));">
    <p class="section-banner-title">{{ __('messages.groups_edit_title') }}</p>
    <p class="section-banner-text">{{ __('messages.groups_edit_description') }}</p>
</div>

<div class="row g-4">
    <div class="col-lg-3 col-md-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">{{ __('messages.Settings') }}</div>
            <div class="card-body p-4">
                <div class="sidebar-menu">
                    <a class="sidebar-menu-item active" href="{{ route('groups.edit', $group) }}">
                        <svg class="sidebar-menu-item-icon icon-settings"><use xlink:href="#svg-settings"></use></svg>
                        {{ __('messages.general_settings') }}
                    </a>
                    <a class="sidebar-menu-item" href="{{ route('groups.show', $group) }}">
                        <svg class="sidebar-menu-item-icon icon-group"><use xlink:href="#svg-group"></use></svg>
                        {{ __('messages.back_to_group') }}
                    </a>
                </div>
            </div>
        </div>
        
        <x-widget-column side="groups_left" />
    </div>

    <div class="col-lg-9 col-md-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 fw-bold fs-5">{{ __('messages.groups_edit_title') }}</div>
            <div class="card-body p-4 p-md-5">
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 fw-bold mb-4">
                        <ul style="margin: 0; padding-inline-start: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                        <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('groups.update', $group) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    @php
                        $cover = $group->cover_path ?: 'upload/cover.jpg';
                        $avatar = $group->avatar_path ?: 'upload/avatar.png';
                    @endphp

                    <!-- SUPERDESIGN GROUP PREVIEW -->
                    <div class="user-preview small fixed-height mb-4" style="border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: visible;">
                        <figure class="user-preview-cover liquid" style="background: url({{ asset($cover) }}) center center / cover no-repeat; position: relative; border-top-left-radius: 12px; border-top-right-radius: 12px; height: 160px;">
                            <!-- Cover Edit Button -->
                            <div id="CoverUpload" style="position: absolute; top: 16px; right: 16px; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); color: #fff; padding: 8px 16px; border-radius: 20px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.2); font-size: 13px; font-weight: 600; z-index: 10;" onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">
                                <svg class="icon-camera" style="width: 16px; height: 16px; fill: currentColor;"><use xlink:href="#svg-camera"></use></svg>
                                <span>{{ __('messages.cover') }}</span>
                            </div>
                            
                            <img id="cover-preview" src="{{ asset($cover) }}" alt="cover-preview" style="display: none;">
                        </figure>
                        
                        <div class="user-preview-info">
                            <div class="user-short-description small">
                                <div class="user-short-description-avatar user-avatar">
                                    <div class="user-avatar-border">
                                        <div class="hexagon-100-110" style="width: 100px; height: 110px; position: relative;"><canvas style="position: absolute; top: 0px; left: 0px;" width="100" height="110"></canvas></div>
                                    </div>
                                    <div class="user-avatar-content">
                                        <div class="hexagon-image-68-74" data-src="{{ asset($avatar) }}" style="width: 68px; height: 74px; position: relative;"><canvas style="position: absolute; top: 0px; left: 0px;" width="68" height="74"></canvas></div>
                                    </div>
                                    <div class="user-avatar-progress-border">
                                        <div class="hexagon-border-84-92" style="width: 84px; height: 92px; position: relative;"><canvas style="position: absolute; top: 0px; left: 0px;" width="84" height="92"></canvas></div>
                                    </div>
                                    
                                    <!-- Avatar Edit Button -->
                                    <div id="AvatarUpload" style="position: absolute; bottom: 0px; right: 0px; width: 32px; height: 32px; background: var(--primary-color, #23d2e2); border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; color: #fff; box-shadow: 0 0 0 3px var(--widget-box-bg, #fff); z-index: 20; transition: transform 0.2s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                                        <svg class="icon-camera" style="width: 14px; height: 14px; fill: currentColor;"><use xlink:href="#svg-camera"></use></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /SUPERDESIGN GROUP PREVIEW -->
                    
                    <!-- HIDDEN UPLOADS -->
                    <input type="file" id="Avatarload" name="avatar" accept=".jpg, .jpeg, .png, .gif, .webp" style="display:none">
                    <input type="file" id="Coverload" name="cover" accept=".jpg, .jpeg, .png, .gif, .webp" style="display:none">

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="group-name" class="form-label small fw-bold">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input id="group-name" type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name', $group->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="group-slug" class="form-label small fw-bold">{{ __('messages.slug') }}</label>
                            <input id="group-slug" type="text" name="slug" class="form-control form-control-lg bg-light border-0" value="{{ old('slug', $group->slug) }}">
                        </div>
                    </div>

                    <!-- Category & Posting Permission -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="group-category" class="form-label small fw-bold">{{ __('messages.groups_category') }} <span class="text-danger">*</span></label>
                            <select id="group-category" name="category" class="form-select form-select-lg bg-light border-0">
                                @foreach($categories as $catKey => $catLabelKey)
                                    <option value="{{ $catKey }}" {{ old('category', $group->category) === $catKey ? 'selected' : '' }}>
                                        {{ str_starts_with($catLabelKey, 'messages.') ? __($catLabelKey) : __('messages.' . $catLabelKey) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="group-posting-permission" class="form-label small fw-bold">{{ __('messages.groups_posting_permission') }} <span class="text-danger">*</span></label>
                            <select id="group-posting-permission" name="posting_permission" class="form-select form-select-lg bg-light border-0">
                                <option value="all_members" {{ old('posting_permission', $group->posting_permission) === 'all_members' ? 'selected' : '' }}>
                                    {{ __('messages.groups_posting_all') }}
                                </option>
                                <option value="moderators_only" {{ old('posting_permission', $group->posting_permission) === 'moderators_only' ? 'selected' : '' }}>
                                    {{ __('messages.groups_posting_moderators') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- VIP Join Fee (PTS) -->
                    <div class="mb-4">
                        <label for="group-join-fee" class="form-label small fw-bold">{{ __('messages.groups_join_fee') }}</label>
                        <div class="input-group input-group-lg">
                            <input id="group-join-fee" type="number" min="0" max="100000" name="join_fee_pts" class="form-control bg-light border-0" value="{{ old('join_fee_pts', $group->join_fee_pts ?? 0) }}">
                            <span class="input-group-text bg-light border-0 fw-bold text-muted">PTS</span>
                        </div>
                        <small class="text-muted d-block mt-1">{{ __('messages.groups_join_fee_hint') }}</small>
                    </div>

                    <!-- Privacy -->
                    <div class="mb-4">
                        <label class="mb-3 d-block fw-bold small">{{ __('messages.groups_privacy') }} <span class="text-danger">*</span></label>
                        <div class="row gx-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="card border shadow-xs rounded-4 h-100 bg-light p-3" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="privacy" value="public" {{ old('privacy', $group->privacy) === 'public' ? 'checked' : '' }}>
                                        <span class="form-check-label fw-bold d-block">
                                            <i class="fa fa-globe text-primary me-1"></i> {{ __('messages.groups_public') }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 ps-4">{{ __('messages.groups_public_hint') }}</p>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="card border shadow-xs rounded-4 h-100 bg-light p-3" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="privacy" value="private_request" {{ old('privacy', $group->privacy) === 'private_request' ? 'checked' : '' }}>
                                        <span class="form-check-label fw-bold d-block">
                                            <i class="fa fa-lock text-warning me-1"></i> {{ __('messages.groups_private') }}
                                        </span>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 ps-4">{{ __('messages.groups_private_hint') }}</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Short Description -->
                    <div class="mb-4">
                        <label for="group-short-description" class="form-label small fw-bold">{{ __('messages.groups_short_description') }}</label>
                        <input id="group-short-description" type="text" name="short_description" class="form-control form-control-lg bg-light border-0" value="{{ old('short_description', $group->short_description) }}">
                    </div>

                    <!-- Full Description -->
                    <div class="mb-4">
                        <label for="group-description" class="form-label small fw-bold">{{ __('messages.description') }}</label>
                        <textarea id="group-description" name="description" class="form-control bg-light border-0" rows="5">{{ old('description', $group->description) }}</textarea>
                    </div>

                    <!-- Rules -->
                    <div class="mb-4">
                        <label for="group-rules" class="form-label small fw-bold">{{ __('messages.groups_rules') }}</label>
                        <textarea id="group-rules" name="rules_markdown" class="form-control bg-light border-0" rows="5">{{ old('rules_markdown', $group->rules_markdown) }}</textarea>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa fa-save me-2"></i> {{ __('messages.save_changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DANGER ZONE: DELETE GROUP -->
        @if($group->owner_id === Auth::id() || Auth::user()?->isAdmin())
            <div class="card border border-danger border-opacity-25 shadow-sm rounded-4 bg-danger bg-opacity-10 mb-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="fw-bold text-danger mb-1">
                            <i class="fa fa-triangle-exclamation me-2"></i> {{ __('messages.groups_delete_group') }}
                        </h5>
                        <p class="text-muted small mb-0">{{ __('messages.groups_delete_confirm') }}</p>
                    </div>
                    <form method="POST" action="{{ route('groups.destroy', $group) }}" onsubmit="return confirm('{{ __('messages.groups_delete_confirm') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                            <i class="fa fa-trash me-2"></i> {{ __('messages.groups_delete_group') }}
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(typeof initHexagons === 'function') {
            initHexagons();
        }

        const avatarBox = document.getElementById('AvatarUpload');
        const avatarInput = document.getElementById('Avatarload');
        if(avatarBox && avatarInput) {
            avatarBox.addEventListener('click', () => avatarInput.click());
        }

        const coverBox = document.getElementById('CoverUpload');
        const coverInput = document.getElementById('Coverload');
        if(coverBox && coverInput) {
            coverBox.addEventListener('click', () => coverInput.click());
        }

        avatarInput.addEventListener('change', function(e) {
            if(this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const hexImg = document.querySelector('.user-preview .hexagon-image-68-74');
                    if (hexImg) {
                        hexImg.style.backgroundImage = 'url(' + e.target.result + ')';
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        coverInput.addEventListener('change', function(e) {
            if(this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const coverPreview = document.querySelector('.user-preview-cover.liquid');
                    if (coverPreview) {
                        coverPreview.style.backgroundImage = 'url(' + e.target.result + ')';
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush
@endsection
