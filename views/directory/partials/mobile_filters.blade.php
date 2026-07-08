@php
    $currentCategoryId = isset($category) ? $category->id : null;
@endphp

<div class="directory-mobile-nav-shell d-lg-none mb-4">
    <div class="card border-0 shadow-sm rounded-4 directory-mobile-filters-card">
        <div class="card-body p-3">
            <div class="directory-mobile-nav-actions d-flex flex-column gap-3">
                <a href="{{ route('directory.create') }}" class="btn btn-outline-primary rounded-pill fw-bold w-100 directory-mobile-add-btn">
                    <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;{{ __('messages.addWebsite') }}
                </a>

                @if(isset($categoryBoard) && $categoryBoard->isNotEmpty())
                    <div class="directory-mobile-category-dropdown position-relative">
                        <select class="form-select rounded-pill directory-cat-select" onchange="if(this.value) window.location.href=this.value;">
                            <option value="{{ route('directory.index') }}" {{ is_null($currentCategoryId) ? 'selected' : '' }}>
                                {{ __('messages.All') }} {{ __('messages.cat_s') }}
                            </option>
                            @foreach($categoryBoard as $entry)
                                <option value="{{ route('directory.category.legacy', $entry['category']->id) }}" {{ $currentCategoryId == $entry['category']->id ? 'selected' : '' }}>
                                    {{ $entry['category']->name }}
                                </option>
                                @if($entry['children']->isNotEmpty())
                                    @foreach($entry['children'] as $child)
                                        <option value="{{ route('directory.category.legacy', $child['category']->id) }}" {{ $currentCategoryId == $child['category']->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;— {{ $child['category']->name }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
