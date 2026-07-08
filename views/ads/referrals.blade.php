@extends('theme::layouts.master')

@section('content')
@php
    $refUrl = url('/') . '?ref=' . $user->id;
    $siteTitle = $site_settings->titer ?? config('app.name');
    $banner728 = theme_asset('img/banner/728x90.gif');
    $banner300 = theme_asset('img/banner/300x250.gif');
    $banner160 = theme_asset('img/banner/160x600.gif');
    $banner468 = theme_asset('img/banner/468x60.gif');
    $code728 = "<!-- ADStn code begin --><a href=\"{$refUrl}\"><img src=\"{$banner728}\" width=\"728\" height=\"90\" ></a><!-- ADStn code begin -->";
    $code300 = "<!-- ADStn code begin --><a href=\"{$refUrl}\"><img src=\"{$banner300}\" width=\"300\" height=\"250\" ></a><!-- ADStn code begin -->";
    $code160 = "<!-- ADStn code begin --><a href=\"{$refUrl}\"><img src=\"{$banner160}\" width=\"160\" height=\"600\" ></a><!-- ADStn code begin -->";
    $code468 = "<!-- ADStn code begin --><a href=\"{$refUrl}\"><img src=\"{$banner468}\" width=\"468\" height=\"60\" ></a><!-- ADStn code begin -->";
@endphp

<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: url({{ theme_asset('img/banner/03.jpg') }}) no-repeat center center; background-size: cover; position: relative; z-index: 1;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); border-radius: 1rem; z-index: -1;"></div>
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-4 rounded-circle bg-white p-2" src="{{ theme_asset('img/banner/referral.png') }}" alt="referral" style="width: 80px; height: 80px;">
                    <div>
                        <p class="text-white fs-3 fw-bold mb-1">{{ __('messages.codes') }}&nbsp;{{ __('messages.referal') }}</p>
                        <p class="text-white-50 mb-0 fs-5"><b>{{ __('messages.ryffyrly') }}</b></p>
                    </div>
                </div>

                <a class="btn btn-light btn-lg rounded-pill fw-bold" href="{{ route('legacy.referral') }}">
                    <i class="fa fa-list me-2"></i>{{ __('messages.list') }}&nbsp;{{ __('messages.referal') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-lg-4 mb-4 mb-lg-0">
    <div class="card border-0 shadow-sm rounded-4 h-100">
      <div class="card-body p-4 p-md-5">
         <h5 class="fw-bold mb-3 text-primary">Your referral link</h5>
         <div class="bg-light p-3 rounded-3 mb-4 text-center border-dashed">
            <kbd class="user-select-all fs-6" style="word-break: break-all;">{{ $refUrl }}</kbd>
         </div>
         <h6 class="fw-bold mb-3"><i class="fa fa-share text-secondary me-2"></i>&nbsp;Share your referral link</h6>
         <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary rounded-circle" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($refUrl) }}" target="_blank" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
              <i class="fa-brands fa-facebook-f text-white"></i>
            </a>

            <a class="btn rounded-circle" href="https://twitter.com/intent/tweet?text={{ urlencode($siteTitle) }}&url={{ urlencode($refUrl) }}" style="background-color: #000; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;" target="_blank">
              <i class="fa-brands fa-x-twitter text-white"></i>
            </a>

            <a class="btn rounded-circle" href="https://telegram.me/share/url?url={{ urlencode($refUrl) }}&text={{ urlencode($siteTitle) }}" style="background-color: #0088cc; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;" target="_blank">
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
          <li class="nav-item" role="presentation">
            <button class="nav-link active bg-primary text-white rounded-pill px-4 fw-bold" data-bs-toggle="pill" type="button" role="tab">728x90</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-dark rounded-pill px-4 fw-bold" data-bs-toggle="pill" type="button" role="tab">300x250</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-dark rounded-pill px-4 fw-bold" data-bs-toggle="pill" type="button" role="tab">160x600</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-dark rounded-pill px-4 fw-bold" data-bs-toggle="pill" type="button" role="tab">468x60</button>
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" role="tabpanel">
            <h5 class="fw-bold mb-3">Your sponsorship tag 728x90</h5>
            <div class="bg-light p-3 rounded-3 border mb-4">
              <textarea class="form-control border-0 bg-transparent" rows="3" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none;">{{ trim($code728) }}&#10;{{ trim($extensions_code ?? '') }}</textarea>
            </div>
            <div class="text-center overflow-auto">
              <a href="{{ $refUrl }}"><img src="{{ $banner728 }}" width="728" height="90" class="img-fluid rounded shadow-sm"></a>
            </div>
          </div>

          <div class="tab-pane fade" role="tabpanel">
            <h5 class="fw-bold mb-3">Your sponsorship tag 300x250</h5>
            <div class="bg-light p-3 rounded-3 border mb-4">
              <textarea class="form-control border-0 bg-transparent" rows="3" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none;">{{ trim($code300) }}&#10;{{ trim($extensions_code ?? '') }}</textarea>
            </div>
            <div class="text-center overflow-auto">
              <a href="{{ $refUrl }}"><img src="{{ $banner300 }}" width="300" height="250" class="img-fluid rounded shadow-sm"></a>
            </div>
          </div>

          <div class="tab-pane fade" role="tabpanel">
            <h5 class="fw-bold mb-3">Your sponsorship tag 160x600</h5>
            <div class="bg-light p-3 rounded-3 border mb-4">
              <textarea class="form-control border-0 bg-transparent" rows="3" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none;">{{ trim($code160) }}&#10;{{ trim($extensions_code ?? '') }}</textarea>
            </div>
            <div class="text-center overflow-auto">
              <a href="{{ $refUrl }}"><img src="{{ $banner160 }}" width="160" height="600" class="img-fluid rounded shadow-sm"></a>
            </div>
          </div>

          <div class="tab-pane fade" role="tabpanel">
            <h5 class="fw-bold mb-3">Your sponsorship tag 468x60</h5>
            <div class="bg-light p-3 rounded-3 border mb-4">
              <textarea class="form-control border-0 bg-transparent" rows="3" readonly onclick="this.select(); document.execCommand('copy');" style="resize: none;">{{ trim($code468) }}&#10;{{ trim($extensions_code ?? '') }}</textarea>
            </div>
            <div class="text-center overflow-auto">
              <a href="{{ $refUrl }}"><img src="{{ $banner468 }}" width="468" height="60" class="img-fluid rounded shadow-sm"></a>
            </div>
          </div>
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
            // Remove active class from all options
            tabOptions.forEach(opt => opt.classList.remove('active', 'bg-primary', 'text-white'));
            // Add active class to clicked option
            this.classList.add('active', 'bg-primary', 'text-white');

            // Hide all items
            tabItems.forEach(item => {
                item.classList.remove('show', 'active');
            });
            // Show corresponding item
            if (tabItems[index]) {
                tabItems[index].classList.add('show', 'active');
            }
        });
    });
});
</script>
@endsection
