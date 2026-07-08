@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: linear-gradient(135deg, rgba(255,107,61,0.95), rgba(97,93,250,0.92));">
    <p class="section-banner-title">{{ __('messages.groups_create_title') }}</p>
    <p class="section-banner-text">{{ __('messages.groups_create_description') }}</p>
</div>

<div class="row">
    <div class="col-lg-3 d-none d-lg-block">
        <x-widget-column side="groups_left" />
    </div>

    <div class="col-lg-6 col-md-8 mx-auto mb-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 fw-bold">
                        <ul style="margin: 0; padding-inline-start: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <p class="mb-2"><strong>{{ __('messages.groups_creation_policy') }}:</strong> {{ __('messages.groups_policy_' . $eligibility['policy']) }}</p>
                    @if($eligibility['initial_status'] === \App\Models\Group::STATUS_PENDING_REVIEW)
                        <p class="mb-0 text-muted">{{ __('messages.groups_creation_review_notice') }}</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('groups.store') }}">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label for="group-name" class="form-label small fw-bold">{{ __('messages.name') }}</label>
                            <input id="group-name" type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="group-slug" class="form-label small fw-bold">{{ __('messages.slug') }}</label>
                            <input id="group-slug" type="text" name="slug" class="form-control form-control-lg bg-light border-0" value="{{ old('slug') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="group-short-description" class="form-label small fw-bold">{{ __('messages.groups_short_description') }}</label>
                        <input id="group-short-description" type="text" name="short_description" class="form-control form-control-lg bg-light border-0" value="{{ old('short_description') }}">
                    </div>

                    <div class="mb-4">
                        <label class="mb-3 d-block fw-bold small">{{ __('messages.groups_privacy') }}</label>
                        <div class="row gx-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="card border-0 shadow-sm rounded-4 h-100 bg-light" style="cursor: pointer;">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="privacy" value="public" {{ old('privacy', 'public') === 'public' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold">{{ __('messages.groups_public') }}</label>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">{{ __('messages.groups_public_hint') }}</p>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="card border-0 shadow-sm rounded-4 h-100 bg-light" style="cursor: pointer;">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="privacy" value="private_request" {{ old('privacy') === 'private_request' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold">{{ __('messages.groups_private') }}</label>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">{{ __('messages.groups_private_hint') }}</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="group-description" class="form-label small fw-bold">{{ __('messages.description') }}</label>
                        <textarea id="group-description" name="description" class="form-control bg-light border-0" rows="6">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="group-rules" class="form-label small fw-bold">{{ __('messages.groups_rules') }}</label>
                        <textarea id="group-rules" name="rules_markdown" class="form-control bg-light border-0" rows="6">{{ old('rules_markdown') }}</textarea>
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

    <div class="col-lg-3 d-none d-lg-block">
        <x-widget-column side="groups_right" />
    </div>
</div>
@endsection
