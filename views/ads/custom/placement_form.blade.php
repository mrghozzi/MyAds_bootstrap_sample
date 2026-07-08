@extends('theme::layouts.master')

@section('content')
@include('theme::ads.custom.partials.styles')

@php
    $isEdit = $placement->exists;
@endphp

<div class="container py-4">
    <!-- Header Banner -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient text-white rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-rectangle-ad fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ $isEdit ? __('messages.custom_ads_edit_placement') : __('messages.custom_ads_new_placement') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.custom_ads_placement_form_intro') }}</p>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="mb-4">
        <a class="btn btn-outline-secondary" href="{{ route('ads.custom.index') }}">
            <i class="fa fa-arrow-left me-1"></i> {{ __('messages.custom_ads') }}
        </a>
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ $isEdit ? route('ads.custom.placements.update', $placement) : route('ads.custom.placements.store') }}">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('messages.name') }}</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $placement->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('messages.type') }}</label>
                        <select name="format" class="form-select @error('format') is-invalid @enderror" required>
                            @foreach($formats as $value => $label)
                                <option value="{{ $value }}" @selected(old('format', $placement->format) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('messages.size') }}</label>
                        <select name="size" class="form-select @error('size') is-invalid @enderror" required>
                            @foreach($sizes as $value => $label)
                                <option value="{{ $value }}" @selected(old('size', $placement->size) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">{{ __('messages.url') }}</label>
                        <input type="url" name="site_url" class="form-control @error('site_url') is-invalid @enderror" value="{{ old('site_url', $placement->site_url) }}" placeholder="https://example.com">
                        @error('site_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @if($isEdit)
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.status') }}</label>
                            <select name="status" class="form-select">
                                @foreach([\App\Models\CustomAdPlacement::STATUS_ACTIVE, \App\Models\CustomAdPlacement::STATUS_PAUSED, \App\Models\CustomAdPlacement::STATUS_DISABLED] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $placement->status) === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">{{ __('messages.description') }}</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $placement->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Colors Section -->
                <div class="card border-light bg-light bg-opacity-50 p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fa fa-palette me-1 text-primary"></i> {{ __('messages.custom_ads_styling_options') ?? 'Styling Options' }}</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('messages.custom_ads_background_color') }}</label>
                            <input type="color" name="background_color" class="form-control form-control-color w-100" value="{{ old('background_color', $placement->background_color ?: '#ffffff') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('messages.custom_ads_text_color') }}</label>
                            <input type="color" name="text_color" class="form-control form-control-color w-100" value="{{ old('text_color', $placement->text_color ?: '#1f2937') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('messages.custom_ads_accent_color') }}</label>
                            <input type="color" name="accent_color" class="form-control form-control-color w-100" value="{{ old('accent_color', $placement->accent_color ?: '#615dfa') }}">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="is_public" class="form-check-input" id="is_public" value="1" @checked(old('is_public', $placement->is_public ?? true))>
                        <label class="form-check-label fw-bold" for="is_public">
                            {{ __('messages.custom_ads_public_space') }}
                        </label>
                    </div>
                    <div class="form-text text-muted ps-4">{{ __('messages.custom_ads_public_space_help') }}</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ $isEdit ? __('messages.save') : __('messages.create') }}</button>
                    <a href="{{ route('ads.custom.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
