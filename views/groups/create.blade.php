@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: linear-gradient(135deg, rgba(255,107,61,0.95), rgba(97,93,250,0.92));">
    <p class="section-banner-title">{{ __('messages.groups_create_title') }}</p>
    <p class="section-banner-text">{{ __('messages.groups_create_description') }}</p>
</div>

<div class="row g-4">
    <div class="col-lg-7 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
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

                <div class="mb-4 p-3 bg-light rounded-3 border">
                    <p class="mb-1"><strong>{{ __('messages.groups_creation_policy') }}:</strong> {{ __('messages.groups_policy_' . $eligibility['policy']) }}</p>
                    @if($eligibility['initial_status'] === \App\Models\Group::STATUS_PENDING_REVIEW)
                        <p class="mb-0 text-muted small"><i class="fa fa-info-circle me-1"></i> {{ __('messages.groups_creation_review_notice') }}</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('groups.store') }}" id="groupCreateForm">
                    @csrf

                    <!-- Name & Slug -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="group-name" class="form-label small fw-bold">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                            <input id="group-name" type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name') }}" placeholder="{{ __('messages.groups_title') }}" required oninput="updatePreview()">
                        </div>
                        <div class="col-md-6">
                            <label for="group-slug" class="form-label small fw-bold">{{ __('messages.slug') }}</label>
                            <input id="group-slug" type="text" name="slug" class="form-control form-control-lg bg-light border-0" value="{{ old('slug') }}" placeholder="my-community">
                        </div>
                    </div>

                    <!-- Category & Posting Permission -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="group-category" class="form-label small fw-bold">{{ __('messages.groups_category') }} <span class="text-danger">*</span></label>
                            <select id="group-category" name="category" class="form-select form-select-lg bg-light border-0" onchange="updatePreview()">
                                @foreach($categories as $catKey => $catLabelKey)
                                    <option value="{{ $catKey }}" {{ old('category', 'general') === $catKey ? 'selected' : '' }}>
                                        {{ str_starts_with($catLabelKey, 'messages.') ? __($catLabelKey) : __('messages.' . $catLabelKey) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="group-posting-permission" class="form-label small fw-bold">{{ __('messages.groups_posting_permission') }} <span class="text-danger">*</span></label>
                            <select id="group-posting-permission" name="posting_permission" class="form-select form-select-lg bg-light border-0">
                                <option value="all_members" {{ old('posting_permission', 'all_members') === 'all_members' ? 'selected' : '' }}>
                                    {{ __('messages.groups_posting_all') }}
                                </option>
                                <option value="moderators_only" {{ old('posting_permission') === 'moderators_only' ? 'selected' : '' }}>
                                    {{ __('messages.groups_posting_moderators') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- VIP Join Fee (PTS) -->
                    <div class="mb-4">
                        <label for="group-join-fee" class="form-label small fw-bold">{{ __('messages.groups_join_fee') }}</label>
                        <div class="input-group input-group-lg">
                            <input id="group-join-fee" type="number" min="0" max="100000" name="join_fee_pts" class="form-control bg-light border-0" value="{{ old('join_fee_pts', 0) }}" oninput="updatePreview()">
                            <span class="input-group-text bg-light border-0 fw-bold text-muted">PTS</span>
                        </div>
                        <small class="text-muted d-block mt-1">{{ __('messages.groups_join_fee_hint') }}</small>
                    </div>

                    <!-- Privacy Options -->
                    <div class="mb-4">
                        <label class="mb-3 d-block fw-bold small">{{ __('messages.groups_privacy') }} <span class="text-danger">*</span></label>
                        <div class="row gx-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="card border shadow-xs rounded-4 h-100 bg-light p-3" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="privacy" value="public" {{ old('privacy', 'public') === 'public' ? 'checked' : '' }} onchange="updatePreview()">
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
                                        <input class="form-check-input" type="radio" name="privacy" value="private_request" {{ old('privacy') === 'private_request' ? 'checked' : '' }} onchange="updatePreview()">
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
                        <input id="group-short-description" type="text" name="short_description" class="form-control form-control-lg bg-light border-0" value="{{ old('short_description') }}" placeholder="{{ __('messages.groups_short_description') }}" oninput="updatePreview()">
                    </div>

                    <!-- Full Description -->
                    <div class="mb-4">
                        <label for="group-description" class="form-label small fw-bold">{{ __('messages.description') }}</label>
                        <textarea id="group-description" name="description" class="form-control bg-light border-0" rows="5" placeholder="{{ __('messages.description') }}">{{ old('description') }}</textarea>
                    </div>

                    <!-- Group Rules -->
                    <div class="mb-4">
                        <label for="group-rules" class="form-label small fw-bold">{{ __('messages.groups_rules') }}</label>
                        <textarea id="group-rules" name="rules_markdown" class="form-control bg-light border-0" rows="5" placeholder="1. Be respectful&#10;2. No spam">{{ old('rules_markdown') }}</textarea>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fa fa-plus me-2"></i> {{ __('messages.groups_create_submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Preview Column -->
    <div class="col-lg-5 col-xl-4">
        <div class="position-sticky" style="top: 20px;">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <span class="fw-bold small text-uppercase text-muted letter-spacing-1">
                        <i class="fa fa-eye text-primary me-1"></i> {{ __('messages.preview') }}
                    </span>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small fw-bold">Live</span>
                </div>
                <!-- Card Cover -->
                <div class="position-relative" style="height: 120px; background: linear-gradient(135deg, #615dfa 0%, #23d2e2 100%);">
                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1" id="preview-chips">
                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1 small fw-bold shadow-xs" id="preview-pts" style="display: none;">
                            <i class="fa fa-coins me-1"></i> <span id="preview-pts-val">0</span> PTS
                        </span>
                        <span class="badge bg-dark bg-opacity-50 text-white rounded-pill px-2 py-1 small fw-bold" id="preview-privacy">
                            <i class="fa fa-globe me-1"></i> {{ __('messages.groups_public') }}
                        </span>
                    </div>
                    <div class="position-absolute top-100 start-0 translate-middle-y ms-3">
                        <img src="{{ asset('upload/group.png') }}" class="rounded-circle border border-4 border-white shadow-sm" width="64" height="64" alt="Group Preview" style="object-fit: cover; background: #fff;">
                    </div>
                </div>

                <div class="card-body pt-5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-1 small fw-bold" id="preview-cat">
                            {{ __('messages.groups_cat_general') }}
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark" id="preview-title">{{ __('messages.groups_title') }}</h5>
                    <p class="text-muted small mb-3" id="preview-desc" style="min-height: 40px;">
                        {{ __('messages.groups_short_description') }}
                    </p>
                    
                    <div class="d-flex justify-content-around border-top pt-3 text-center text-muted small">
                        <div>
                            <div class="fw-bold text-dark">1</div>
                            <div class="smaller">{{ __('messages.members') }}</div>
                        </div>
                        <div class="vr opacity-25"></div>
                        <div>
                            <div class="fw-bold text-dark">0</div>
                            <div class="smaller">{{ __('messages.posts') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <x-widget-column side="groups_left" />
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updatePreview() {
        const nameInput = document.getElementById('group-name');
        const descInput = document.getElementById('group-short-description');
        const catSelect = document.getElementById('group-category');
        const feeInput = document.getElementById('group-join-fee');
        const privacyRadio = document.querySelector('input[name="privacy"]:checked');

        const titleEl = document.getElementById('preview-title');
        const descEl = document.getElementById('preview-desc');
        const catEl = document.getElementById('preview-cat');
        const privacyEl = document.getElementById('preview-privacy');
        const ptsEl = document.getElementById('preview-pts');
        const ptsVal = document.getElementById('preview-pts-val');

        if (titleEl) {
            titleEl.textContent = nameInput && nameInput.value.trim() ? nameInput.value.trim() : '{{ __('messages.groups_title') }}';
        }
        if (descEl) {
            descEl.textContent = descInput && descInput.value.trim() ? descInput.value.trim() : '{{ __('messages.groups_short_description') }}';
        }
        if (catEl && catSelect && catSelect.selectedIndex >= 0) {
            catEl.textContent = catSelect.options[catSelect.selectedIndex].text.trim();
        }
        if (privacyEl && privacyRadio) {
            const isPublic = privacyRadio.value === 'public';
            privacyEl.innerHTML = `<i class="fa ${isPublic ? 'fa-globe' : 'fa-lock'} me-1"></i> ${isPublic ? '{{ __('messages.groups_public') }}' : '{{ __('messages.groups_private') }}'}`;
        }
        if (ptsEl && feeInput) {
            const fee = parseInt(feeInput.value, 10) || 0;
            if (fee > 0) {
                ptsEl.style.display = 'inline-block';
                if (ptsVal) ptsVal.textContent = fee;
            } else {
                ptsEl.style.display = 'none';
            }
        }
    }
    document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endpush
