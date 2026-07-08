@extends('theme::layouts.master')

@section('content')
@php
    $adsBrandName = \App\Support\AdsSettings::brandName();
    $scriptUrl = route('ads.link.script');
    $embedScriptUrl = route('ads.embed.link');
    $fixedCode = \App\Support\LinkEmbedCode::build($embedScriptUrl, $user->id, '468x60', $extensions_code ?? '');
    $responsiveCode = \App\Support\LinkEmbedCode::build($embedScriptUrl, $user->id, 'responsive', $extensions_code ?? '');
    $responsive2Code = \App\Support\LinkEmbedCode::build($embedScriptUrl, $user->id, 'responsive2', $extensions_code ?? '');
    $fixedFallbackCode = \App\Support\LinkEmbedCode::buildDirect($scriptUrl, $user->id, '468x60', $extensions_code ?? '');
    $responsiveFallbackCode = \App\Support\LinkEmbedCode::buildDirect($scriptUrl, $user->id, '510x320', $extensions_code ?? '');
    $responsive2QuickCode = \App\Support\LinkEmbedCode::buildDirect($scriptUrl, $user->id, 'responsive2', $extensions_code ?? '');
    $responsive2SmartCode = \App\Support\LinkEmbedCode::buildResponsive2Smart($scriptUrl, $user->id, $extensions_code ?? '');
    $fixedPreview = \App\Support\LinkEmbedCode::buildDirect($scriptUrl, $user->id, '468x60');
    $responsivePreview = \App\Support\LinkEmbedCode::buildDirect($scriptUrl, $user->id, '510x320');
    $responsive2Preview = \App\Support\LinkEmbedCode::buildResponsive2Smart($scriptUrl, $user->id);
    $linkCodeTabs = [
        [
            'key' => '468x60',
            'label' => '468x60',
            'title' => __('messages.your_promotion_tags_size', ['size' => '468x60']) . ' (1 ' . __('messages.point') . ')',
            'code' => $fixedCode,
            'fallback_code' => $fixedFallbackCode,
            'preview' => $fixedPreview,
        ],
        [
            'key' => 'responsive',
            'label' => __('messages.responsive'),
            'title' => __('messages.your_promotion_tags_size', ['size' => __('messages.responsive')]) . ' (1 ' . __('messages.point') . ')',
            'code' => $responsiveCode,
            'fallback_code' => $responsiveFallbackCode,
            'preview' => $responsivePreview,
        ],
        [
            'key' => 'responsive2',
            'label' => 'Responsive 2',
            'title' => __('messages.your_promotion_tags_size', ['size' => 'Responsive 2']) . ' (1 ' . __('messages.point') . ')',
            'code' => $responsive2Code,
            'quick_code' => $responsive2QuickCode,
            'smart_code' => $responsive2SmartCode,
            'preview' => $responsive2Preview,
        ],
    ];
@endphp

<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: url({{ theme_asset('img/banner/03.jpg') }}) no-repeat center center; background-size: cover; position: relative; z-index: 1;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); border-radius: 1rem; z-index: -1;"></div>
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-4 rounded-circle bg-white p-2" src="{{ theme_asset('img/banner/link_ads.png') }}" alt="link-ads" style="width: 80px; height: 80px;">
                    <div>
                        <p class="text-white fs-3 fw-bold mb-1">{{ __('messages.codes') }}&nbsp;{{ __('messages.textads') }}</p>
                        <p class="text-white-50 mb-0 fs-5"><b>{{ __('messages.yhtierbpyaci') }}</b></p>
                    </div>
                </div>

                <a class="btn btn-light btn-lg rounded-pill fw-bold" href="{{ route('legacy.l_list') }}">
                    {{ __('messages.list') }}&nbsp;{{ __('messages.textads') }}
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
                <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" role="tablist">
                    @foreach($linkCodeTabs as $index => $tab)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $index === 0 ? 'active bg-primary text-white' : 'text-dark' }} rounded-pill px-4 fw-bold" data-bs-toggle="pill" type="button" role="tab">
                                {{ $tab['label'] }}
                                @if($tab['key'] === 'responsive2')
                                    &nbsp;<span class="badge bg-info text-white rounded-pill">beta</span>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($linkCodeTabs as $index => $tab)
                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" role="tabpanel">
                            <h5 class="fw-bold mb-3">{{ $tab['title'] }}</h5>
                            <hr class="mb-4" />

                            @if($tab['key'] !== 'responsive2')
                                <div class="bg-light p-3 rounded-3 border mb-4">
                                    <textarea class="form-control border-0 bg-transparent" readonly onclick="this.select(); document.execCommand('copy');" rows="3" style="resize: none;">{{ $tab['code'] }}</textarea>
                                </div>

                                <h6 class="fw-bold mb-2">{{ __('messages.quick_code') }}</h6>
                                <div class="bg-light p-3 rounded-3 border mb-4">
                                    <textarea class="form-control border-0 bg-transparent" readonly onclick="this.select(); document.execCommand('copy');" rows="2" style="resize: none;">{{ $tab['fallback_code'] }}</textarea>
                                </div>

                                <div class="text-center overflow-auto py-3">
                                    {!! $tab['preview'] !!}
                                </div>
                            @else
                                <p class="text-muted mb-4">
                                    {{ __('messages.responsive_2_link_desc') }}
                                </p>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-2">{{ __('messages.recommended_smart_code') }}</h6>
                                        <div class="bg-light p-3 rounded-3 border h-100">
                                            <textarea class="form-control border-0 bg-transparent h-100" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none; min-height: 100px;">{{ $tab['code'] }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-2">{{ __('messages.quick_code') }}</h6>
                                        <div class="bg-light p-3 rounded-3 border h-100">
                                            <textarea class="form-control border-0 bg-transparent h-100" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none; min-height: 100px;">{{ $tab['quick_code'] }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <h6 class="fw-bold mb-2">{{ __('messages.recommended_smart_code') }} (Alternative)</h6>
                                        <div class="bg-light p-3 rounded-3 border">
                                            <textarea class="form-control border-0 bg-transparent" readonly onclick="this.select(); document.execCommand('copy');" rows="3" style="resize: none;">{{ $tab['smart_code'] }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 border rounded-4 p-4 bg-light overflow-auto" style="max-width: 760px; margin: 0 auto;">
                                    {!! $tab['preview'] !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabOptions = document.querySelectorAll('.nav-link[data-bs-toggle="pill"]');
    const tabItems = document.querySelectorAll('.tab-pane');

    tabOptions.forEach((option, index) => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            tabOptions.forEach(opt => opt.classList.remove('active', 'bg-primary', 'text-white'));
            this.classList.add('active', 'bg-primary', 'text-white');

            tabItems.forEach(item => {
                item.classList.remove('show', 'active');
            });
            if (tabItems[index]) {
                tabItems[index].classList.add('show', 'active');
            }
        });
    });
});
</script>
@endsection
