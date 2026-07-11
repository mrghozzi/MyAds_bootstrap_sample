@php
    $selectedStoreCategory = $selectedStoreCategory ?? null;
    $selectedStoreSubcategory = $selectedStoreSubcategory ?? null;
    $scriptProductOptions = $scriptProductOptions ?? collect();
    $scriptCategoryOptions = $scriptCategoryOptions ?? collect();
    $genericCategoryOptions = $genericCategoryOptions ?? collect();
@endphp

<div class="mb-3">
    <label for="cat_s" class="form-label fw-bold"><i class="fa fa-folder me-2"></i>{{ __('messages.cat') }}</label>
    <select id="cat_s" name="cat_s" class="form-select form-select-lg rounded-3" required onchange="window.triggerCategoryUpdate(this)">
        <option value="">-- {{ __('messages.select') }} --</option>
        @foreach($storeCategories as $category)
            <option value="{{ $category->name }}" @selected($selectedStoreCategory === $category->name)>
                {{ __('messages.' . $category->name) }}
            </option>
        @endforeach
    </select>
</div>

@if($selectedStoreCategory === \App\Support\StoreCategoryCatalog::PLUGINS || $selectedStoreCategory === \App\Support\StoreCategoryCatalog::THEMES)
    <div class="mb-3">
        <label for="sc_cat" class="form-label fw-bold"><i class="fa fa-sitemap me-2"></i>{{ \Illuminate\Support\Facades\Lang::has('messages.script') ? __('messages.script') : 'Choose Script' }}</label>
        <select id="sc_cat" name="sc_cat" class="form-select form-select-lg rounded-3" required>
            <option value="">-- {{ __('messages.select') }} --</option>
            @foreach($scriptProductOptions as $scriptProduct)
                <option value="{{ $scriptProduct['value'] }}" @selected((string) $selectedStoreSubcategory === (string) $scriptProduct['value'])>
                    {{ \Illuminate\Support\Facades\Lang::has('messages.' . $scriptProduct['label']) ? __('messages.' . $scriptProduct['label']) : $scriptProduct['label'] }}
                </option>
            @endforeach
            <option value="others" @selected($selectedStoreSubcategory === 'others')>{{ __('messages.others') }}</option>
        </select>
    </div>
@elseif($selectedStoreCategory === \App\Support\StoreCategoryCatalog::SCRIPT)
    <div class="mb-3">
        <label for="sc_cat" class="form-label fw-bold"><i class="fa fa-sitemap me-2"></i>{{ \Illuminate\Support\Facades\Lang::has('messages.script_type') ? __('messages.script_type') : __('messages.subcategories') }}</label>
        <select id="sc_cat" name="sc_cat" class="form-select form-select-lg rounded-3" required>
            <option value="">-- {{ __('messages.select') }} --</option>
            @foreach($scriptCategoryOptions as $scriptCategory)
                <option value="{{ $scriptCategory['value'] }}" @selected((string) $selectedStoreSubcategory === (string) $scriptCategory['value'])>
                    {{ \Illuminate\Support\Facades\Lang::has('messages.' . $scriptCategory['label']) ? __('messages.' . $scriptCategory['label']) : $scriptCategory['label'] }}
                </option>
            @endforeach
        </select>
    </div>
@elseif(in_array($selectedStoreCategory, [\App\Support\StoreCategoryCatalog::GRAPHICS, \App\Support\StoreCategoryCatalog::AUDIO, \App\Support\StoreCategoryCatalog::VIDEO, \App\Support\StoreCategoryCatalog::EBOOKS, \App\Support\StoreCategoryCatalog::SOFTWARE, \App\Support\StoreCategoryCatalog::COURSES]))
    <div class="mb-3">
        <label for="sc_cat" class="form-label fw-bold"><i class="fa fa-sitemap me-2"></i>{{ __('messages.subcategories') ?? 'Subcategories' }}</label>
        <select id="sc_cat" name="sc_cat" class="form-select form-select-lg rounded-3" required>
            <option value="">-- {{ __('messages.select') }} --</option>
            @foreach($genericCategoryOptions as $genericCategory)
                <option value="{{ $genericCategory['value'] }}" @selected((string) $selectedStoreSubcategory === (string) $genericCategory['value'])>
                    {{ \Illuminate\Support\Facades\Lang::has('messages.' . $genericCategory['label']) ? __('messages.' . $genericCategory['label']) : $genericCategory['label'] }}
                </option>
            @endforeach
        </select>
    </div>
@endif

