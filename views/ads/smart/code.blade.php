@extends('theme::layouts.master')

@section('content')
<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, rgba(15,23,42,.96) 0%, rgba(29,78,216,.94) 56%, rgba(14,165,233,.88) 100%);">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-3" src="{{ theme_asset('img/banner/banner_ads.png') }}" alt="smart-ads-code" style="width: 60px; height: auto;">
                    <div>
                        <p class="text-white fs-4 fw-bold mb-1">{{ __('messages.smart_code_title') }}</p>
                        <p class="text-white-50 mb-0"><b>{{ __('messages.smart_code_desc') }}</b></p>
                    </div>
                </div>

                <a class="btn btn-light rounded-pill fw-bold" href="{{ route('ads.smart.index') }}">{{ __('messages.smart_list_ads') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @php
            $recommendedCode = \App\Support\SmartAdEmbedCode::build(route('ads.embed.smart'), $user->id, $extensions_code ?? '');
            $compatibleCode = \App\Support\SmartAdEmbedCode::buildInlineLoader(route('ads.smart.script'), $user->id, $extensions_code ?? '');
        @endphp
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">
                <h5 class="fw-bold mb-3">{{ __('messages.smart_code_recommended') }}</h5>
                <p class="text-muted mb-4">{{ __('messages.smart_code_recommended_desc') }}</p>
                <div class="bg-light p-3 rounded-3 border mb-3">
                    <textarea class="form-control border-0 bg-transparent" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none;" rows="4">{{ $recommendedCode }}</textarea>
                </div>
                <p class="text-muted small mb-0">{{ __('messages.smart_code_live_behavior_note') }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">
                <h5 class="fw-bold mb-3">{{ __('messages.advanced_code') }}</h5>
                <div class="bg-light p-3 rounded-3 border">
                    <textarea class="form-control border-0 bg-transparent" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none;" rows="4">{{ $compatibleCode }}</textarea>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">
                <h5 class="fw-bold mb-3">{{ __('messages.preview') }}</h5>
                @if($previewMarkup)
                    <div class="text-muted mb-3">
                        {{ $previewSmartAd->displayTitle() }}
                    </div>
                    <div class="p-4 border rounded-4 bg-light">
                        <div style="max-width: 760px; margin: 0 auto;">
                            {!! $previewMarkup !!}
                        </div>
                    </div>
                @else
                    <div class="p-5 border border-dashed rounded-4 bg-light text-center">
                        <p class="fw-bold fs-5 text-dark mb-2">{{ __('messages.smart_code_preview_empty_title') }}</p>
                        <p class="text-muted mb-0">{{ __('messages.smart_code_preview_empty_desc') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
