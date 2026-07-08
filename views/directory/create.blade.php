@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-info bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex align-items-center">
            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                <i class="fa fa-sitemap fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-bold mb-1">{{ __('messages.addwebsitdir') }}</h1>
                <p class="mb-0 text-white-50 small">{{ __('messages.seo_directory_description') }}</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Left -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-3">
                    <a href="{{ route('directory.index') }}" class="btn btn-outline-secondary w-100 fw-bold rounded-pill">
                        <i class="fa fa-arrow-left me-2"></i> {{ __('messages.back') }}
                    </a>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-info bg-opacity-10">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-info text-uppercase small mb-3"><i class="fa fa-lightbulb me-1"></i> {{ __('Tip') }}</h6>
                    <p class="small text-muted mb-0 lh-base">
                        {{ __('Just enter the website URL and we will try to fetch the title, description and tags for you automatically!') }}
                    </p>
                </div>
            </div>

            <x-widget-column side="directory_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
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

                    @auth
                    <form method="POST" action="{{ route('directory.store') }}" id="add-site-form">
                        @csrf
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="url" class="form-label fw-bold text-muted small text-uppercase">{{ __('messages.url') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3" id="url-icon-container">
                                        <i class="fa fa-link text-primary" id="url-icon"></i>
                                        <div class="spinner-border spinner-border-sm text-primary d-none" id="url-loader" role="status"></div>
                                    </span>
                                    <input type="url" id="url" name="url" class="form-control border-start-0 rounded-end-3 py-2" value="{{ old('url') }}" required placeholder="https://example.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold text-muted small text-uppercase">{{ __('messages.name') }}</label>
                                <input type="text" id="name" name="name" class="form-control rounded-3 py-2" value="{{ old('name') }}" required placeholder="{{ __('messages.name') }}...">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-muted small text-uppercase">{{ __('messages.text_p') }}</label>
                            <textarea id="description" name="txt" class="form-control rounded-4 py-3" rows="5" placeholder="{{ __('messages.text_p') }}...">{{ old('txt') }}</textarea>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label for="categ" class="form-label fw-bold text-muted small text-uppercase">{{ __('messages.cat') }}</label>
                                <select id="categ" name="categ" class="form-select rounded-3 py-2">
                                    @foreach($mainCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @foreach($subCategories->get($cat->id, collect()) as $sub)
                                            <option value="{{ $sub->id }}">_{{ $sub->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tag" class="form-label fw-bold text-muted small text-uppercase">{{ __('messages.tag') }}</label>
                                <input type="text" id="tag" name="tag" class="form-control rounded-3 py-2" value="{{ old('tag') }}" placeholder="tag1, tag2...">
                            </div>
                        </div>

                        <input type="hidden" name="s_type" value="1" />
                        <div class="d-flex justify-content-end pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fa fa-paper-plane me-2"></i> {{ __('messages.spread') }}
                            </button>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-warning rounded-4 p-4 text-center" role="alert">
                            <i class="fa fa-exclamation-triangle fa-2x mb-3 d-block"></i>
                            <p class="mb-3">{{ __('messages.must_be_logged_in') ?? 'You must be logged in to add a website.' }}</p>
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">{{ __('messages.login') }}</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlInput = document.getElementById('url');
    const nameInput = document.getElementById('name');
    const descInput = document.getElementById('description');
    const tagInput = document.getElementById('tag');
    const urlLoader = document.getElementById('url-loader');
    const urlIcon = document.getElementById('url-icon');

    let timeout = null;

    urlInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const url = this.value;
        
        if (!url || !url.startsWith('http')) return;

        timeout = setTimeout(() => {
            fetchMetadata(url);
        }, 1000);
    });

    function fetchMetadata(url) {
        if(urlIcon) urlIcon.classList.add('d-none');
        if(urlLoader) urlLoader.classList.remove('d-none');

        fetch('{{ route("directory.fetch_metadata") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ url: url })
        })
        .then(response => response.json())
        .then(data => {
            if (data.title && !nameInput.value) {
                nameInput.value = data.title;
            }
            if (data.description && !descInput.value) {
                descInput.value = data.description;
            }
            if (data.tags && !tagInput.value) {
                tagInput.value = data.tags;
            }
        })
        .catch(error => console.error('Error fetching metadata:', error))
        .finally(() => {
            if(urlIcon) urlIcon.classList.remove('d-none');
            if(urlLoader) urlLoader.classList.add('d-none');
        });
    }
});
</script>
@endsection
