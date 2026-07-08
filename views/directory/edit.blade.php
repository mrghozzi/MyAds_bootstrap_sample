@extends('theme::layouts.master')

@section('content')
<div class="section-banner" style="background: url({{ theme_asset('img/banner/Newsfeed.png') }}) no-repeat 50%;" >
    <img class="section-banner-icon" src="{{ theme_asset('img/banner/newsfeed-icon.png') }}"  alt="overview-icon">
    <p class="section-banner-title">{{ __('messages.EditWebsite') }}</p>
</div>

<div class="row g-4 py-4">
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <a href="{{ route('directory.show', $listing->id) }}" class="btn btn-light w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                    <i class="fa fa-arrow-left"></i>
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">
                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @auth
                <form method="POST" action="{{ route('directory.update', $listing->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label small fw-bold">{{ __('messages.name') }}</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-edit text-muted"></i></span>
                                <input type="text" id="name" name="name" class="form-control border-start-0 ps-0" value="{{ old('name', $listing->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="url" class="form-label small fw-bold">{{ __('messages.url') }}</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-link text-muted"></i></span>
                                <input type="text" id="url" name="url" class="form-control border-start-0 ps-0" value="{{ old('url', $listing->url) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label small fw-bold">{{ __('messages.text_p') }}</label>
                        <textarea id="description" name="txt" class="form-control" rows="5">{{ old('txt', $listing->txt) }}</textarea>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="profile-status" class="form-label small fw-bold">{{ __('messages.cat') }}</label>
                            <select id="profile-status" name="categ" class="form-select form-select-lg">
                                @foreach($mainCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ (old('categ', $listing->cat) == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @foreach($subCategories->get($cat->id, collect()) as $sub)
                                        <option value="{{ $sub->id }}" {{ (old('categ', $listing->cat) == $sub->id) ? 'selected' : '' }}>&nbsp;&nbsp;— {{ $sub->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tag" class="form-label small fw-bold">{{ __('messages.tag') }}</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-tag text-muted"></i></span>
                                <input type="text" id="tag" name="tag" class="form-control border-start-0 ps-0" value="{{ old('tag', $listing->metakeywords) }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold d-flex align-items-center gap-2">
                            <i class="fa fa-save"></i>
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
                @else
                    <div class="alert alert-warning border-0 rounded-3 mb-0" role="alert">
                        <a href="{{ route('login') }}" class="alert-link">{{ __('messages.login') }}</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
