@extends('theme::layouts.master')

@section('content')
@php
    $adsBrandName = \App\Support\AdsSettings::brandName();
    $quickBannerCodeTabs = \App\Support\BannerSizeCatalog::ordered();
    $advancedBannerCodeTabs = array_merge(
        $quickBannerCodeTabs,
        [
            [
                'value' => 'responsive2',
                'label' => 'Responsive 2',
            ],
            [
                'value' => 'responsive',
                'label' => __('messages.responsive'),
            ],
        ]
    );
    $bannerEmbedUrl = route('ads.embed.banner');
    $bannerServingUrl = route('ads.script');
@endphp
<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: url({{ theme_asset('img/banner/03.jpg') }}) no-repeat center center; background-size: cover; position: relative; z-index: 1;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); border-radius: 1rem; z-index: -1;"></div>
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-4 rounded-circle bg-white p-2" src="{{ theme_asset('img/banner/banner_ads.png') }}" alt="banner-ads" style="width: 80px; height: 80px;">
                    <div>
                        <p class="text-white fs-3 fw-bold mb-1">{{ __('messages.codes') }}&nbsp;{{ __('messages.bannads') }}</p>
                        <p class="text-white-50 mb-0 fs-5"><b>{{ __('messages.yhtierbpyaci') }}</b></p>
                    </div>
                </div>

                <a class="btn btn-light btn-lg rounded-pill fw-bold" href="{{ route('legacy.b_list') }}">
                    {{ __('messages.list') }}&nbsp;{{ __('messages.bannads') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-lg-4 mb-4 mb-lg-0">
    <div class="card border-0 shadow-sm rounded-4 h-100">
         <div class="card-body p-4 p-md-5">
             <h5 class="fw-bold mb-3 text-primary">{{ __('messages.your_referral_link') }}</h5>
             <div class="bg-light p-3 rounded-3 mb-4 text-center border-dashed">
                 <kbd class="user-select-all fs-6" style="word-break: break-all;">{{ route('register', ['ref' => $user->id]) }}</kbd>
             </div>
             <h6 class="fw-bold mb-3"><i class="fa fa-share text-secondary me-2"></i>&nbsp;{{ __('messages.share_your_referral_link') }}</h6>
             <div class="d-flex gap-2 flex-wrap">
                  <a class="btn btn-primary rounded-circle" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('register', ['ref' => $user->id])) }}" target="_blank" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-brands fa-facebook-f text-white"></i>
                  </a>

                  <a class="btn rounded-circle" href="https://twitter.com/intent/tweet?text={{ urlencode($adsBrandName) }}&url={{ urlencode(route('register', ['ref' => $user->id])) }}" style="background-color: #000; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;" target="_blank">
                    <i class="fa-brands fa-x-twitter text-white"></i>
                  </a>

                  <a class="btn rounded-circle" href="https://telegram.me/share/url?url={{ urlencode(route('register', ['ref' => $user->id])) }}&text={{ urlencode($adsBrandName) }}" style="background-color: #0088cc; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;" target="_blank">
                    <i class="fa-brands fa-telegram text-white"></i>
                  </a>
             </div>
         </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4 p-md-5">
        <div class="d-flex gap-2 flex-wrap mb-4 pb-3 border-bottom">
          <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" data-code-mode-button="quick">{{ __('messages.quick_code') }}</button>
          <button type="button" class="btn btn-outline-primary rounded-pill px-4 fw-bold" data-code-mode-button="advanced">{{ __('messages.advanced_code') }}</button>
        </div>

        <div data-code-mode-panel="quick" style="display: block;">
          <h5 class="fw-bold mb-2">{{ __('messages.quick_code') }}</h5>
          <p class="text-muted mb-4">{{ __('messages.quick_code_desc') }}</p>

          <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3 flex-nowrap overflow-auto" role="tablist" style="scrollbar-width: none;">
              @foreach($quickBannerCodeTabs as $index => $tab)
                  <li class="nav-item flex-shrink-0" role="presentation">
                      <button class="nav-link {{ $index === 0 ? 'active' : '' }} rounded-pill px-4 fw-bold" data-bs-toggle="pill" data-bs-target="#quick-tab-{{ $index }}" type="button" role="tab" aria-controls="quick-tab-{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                          {{ $tab['label'] }}
                      </button>
                  </li>
              @endforeach
          </ul>

          <div class="tab-content">
              @foreach($quickBannerCodeTabs as $index => $tab)
              @php($embedCode = \App\Support\BannerEmbedCode::buildLegacy(route('ads.script'), $user->id, $tab['value'], $extensions_code ?? ''))
              <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="quick-tab-{{ $index }}" role="tabpanel">
                  <h6 class="fw-bold mb-3">{{ __('messages.your_quick_banner_code', ['label' => $tab['label']]) }}</h6>
                  <div class="bg-light p-3 rounded-3 border mb-4">
                      <textarea class="form-control border-0 bg-transparent" readonly onclick="this.select(); document.execCommand('copy');" rows="4" style="resize: none;">{{ $embedCode }}</textarea>
                  </div>
                  <div class="text-center overflow-auto py-3">
                      {!! $embedCode !!}
                  </div>
              </div>
              @endforeach
          </div>
        </div>

        <div data-code-mode-panel="advanced" style="display: none;">
          <h5 class="fw-bold mb-2">{{ __('messages.advanced_code') }}</h5>
          <p class="text-muted mb-4">{{ __('messages.advanced_code_desc') }}</p>

          <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3 flex-nowrap overflow-auto" role="tablist" style="scrollbar-width: none;">
              @foreach($advancedBannerCodeTabs as $index => $tab)
                  <li class="nav-item flex-shrink-0" role="presentation">
                      <button class="nav-link {{ $index === 0 ? 'active' : '' }} rounded-pill px-4 fw-bold" data-bs-toggle="pill" data-bs-target="#advanced-tab-{{ $index }}" type="button" role="tab" aria-controls="advanced-tab-{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                          {{ $tab['label'] }}
                          @if(in_array($tab['value'], ['responsive', 'responsive2'], true))
                              &nbsp;<span class="badge bg-info text-white rounded-pill">beta</span>
                          @endif
                      </button>
                  </li>
              @endforeach
          </ul>

          <div class="tab-content">
              @foreach($advancedBannerCodeTabs as $index => $tab)
              @php($embedCode = \App\Support\BannerEmbedCode::build($bannerEmbedUrl, $user->id, $tab['value'], $extensions_code ?? ''))
              @php($compatibleCode = \App\Support\BannerEmbedCode::buildInlineLoader($bannerServingUrl, $user->id, $tab['value'], $extensions_code ?? ''))
              <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="advanced-tab-{{ $index }}" role="tabpanel">
                  @if($tab['value'] === 'responsive2')
                      <h6 class="fw-bold mb-2">{{ __('messages.recommended_smart_code') }} {{ $tab['label'] }} <span class="text-muted fw-normal">(1 {{ __('messages.point') }})</span></h6>
                      <p class="text-muted mb-4 small">
                          {{ __('messages.responsive_2_desc', ['app' => $adsBrandName]) }}
                      </p>
                      
                      <div class="row g-3 mb-4">
                          <div class="col-md-6">
                              <h6 class="fw-bold mb-2">{{ __('messages.advanced_code') }} (Recommended)</h6>
                              <div class="bg-light p-3 rounded-3 border h-100">
                                  <textarea class="form-control border-0 bg-transparent h-100" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none; min-height: 100px;">{{ $embedCode }}</textarea>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <h6 class="fw-bold mb-2">{{ __('messages.advanced_code') }} (Compatible)</h6>
                              <div class="bg-light p-3 rounded-3 border h-100">
                                  <textarea class="form-control border-0 bg-transparent h-100" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none; min-height: 100px;">{{ $compatibleCode }}</textarea>
                              </div>
                          </div>
                      </div>

                      <div class="myads-banner-code-preview myads-banner-code-preview--responsive2 mt-4">
                          <div class="myads-banner-code-preview__header">
                              <span class="myads-banner-code-preview__pill">Responsive 2</span>
                              <p class="myads-banner-code-preview__hint">{{ __('messages.responsive_2_preview_desc', ['app' => $adsBrandName]) }}</p>
                          </div>
                          <div class="myads-banner-code-preview__frame bg-light rounded-4 border">
                              <div class="myads-banner-code-preview__stage">
                                  {!! $embedCode !!}
                              </div>
                          </div>
                      </div>
                  @else
                      <h6 class="fw-bold mb-3">{{ __('messages.your_advanced_promotion_tags', ['label' => $tab['label']]) }} <span class="text-muted fw-normal">(1 {{ __('messages.point') }})</span></h6>
                      
                      <div class="row g-3 mb-4">
                          <div class="col-md-6">
                              <h6 class="fw-bold mb-2">{{ __('messages.advanced_code') }} (Recommended)</h6>
                              <div class="bg-light p-3 rounded-3 border h-100">
                                  <textarea class="form-control border-0 bg-transparent h-100" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none; min-height: 100px;">{{ $embedCode }}</textarea>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <h6 class="fw-bold mb-2">{{ __('messages.advanced_code') }} (Compatible)</h6>
                              <div class="bg-light p-3 rounded-3 border h-100">
                                  <textarea class="form-control border-0 bg-transparent h-100" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none; min-height: 100px;">{{ $compatibleCode }}</textarea>
                              </div>
                          </div>
                      </div>

                      <div class="text-center overflow-auto py-3">
                          {!! $embedCode !!}
                      </div>
                  @endif
              </div>
              @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modeButtons = document.querySelectorAll('[data-code-mode-button]');
    const modePanels = document.querySelectorAll('[data-code-mode-panel]');

    function setMode(mode) {
        modeButtons.forEach((button) => {
            const isActive = button.getAttribute('data-code-mode-button') === mode;
            button.classList.toggle('btn-primary', isActive);
            button.classList.toggle('btn-outline-primary', !isActive);
        });

        modePanels.forEach((panel) => {
            panel.style.display = panel.getAttribute('data-code-mode-panel') === mode ? 'block' : 'none';
        });
    }

    modeButtons.forEach((button) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            setMode(this.getAttribute('data-code-mode-button'));
        });
    });

    setMode('quick');
});
</script>
@endsection
