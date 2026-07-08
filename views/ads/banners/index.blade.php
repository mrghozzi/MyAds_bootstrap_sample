@extends('theme::layouts.master')

@section('content')
<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: url({{ theme_asset('img/banner/03.jpg') }}) no-repeat center center; background-size: cover; position: relative; z-index: 1;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); border-radius: 1rem; z-index: -1;"></div>
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-4 rounded-circle bg-white p-2" src="{{ theme_asset('img/banner/banner_ads.png') }}" alt="banner-ads" style="width: 80px; height: 80px;">
                    <div>
                        <p class="text-white fs-3 fw-bold mb-1">{{ __('messages.list') }}&nbsp;{{ __('messages.bannads') }}</p>
                        <p class="text-white-50 mb-0 fs-5"><b>{{ __('messages.yhtierbpyaci') }}</b></p>
                    </div>
                </div>

                <a class="btn btn-light btn-lg rounded-pill fw-bold" href="{{ route('legacy.b_code') }}">
                    <i class="fa fa-code me-2"></i>{{ __('messages.codes') }}&nbsp;{{ __('messages.bannads') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <a class="btn btn-light rounded-pill border fw-bold px-4" href="{{ route('legacy.state', ['ty' => 'banner', 'st' => 'vu']) }}">
        <i class="fa fa-line-chart text-primary"></i>
    </a>
    
    <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2 fs-6 fw-bold">
        <i class="fa fa-info-circle me-2"></i>{{ __('messages.you_have') }}&nbsp;{{ $user->nvu }}&nbsp;{{ __('messages.ptvyba') }}
    </div>

    <a href="{{ route('ads.promote', ['p' => 'banners']) }}" class="btn btn-primary rounded-pill fw-bold px-4">
        <i class="fa fa-plus me-2"></i>{{ __('messages.add') }}
    </a>
</div>

<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4 p-md-5 overflow-auto">
        <table id="tablepagination" class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                  <th class="rounded-start">#ID</th>
                  <th>{{ __('messages.name') ?? 'Name' }}</th>
                  <th>{{ __('messages.Vu') ?? 'Vu' }}</th>
                  <th>{{ __('messages.Clik') ?? 'Clik' }}</th>
                  <th>{{ __('messages.size') ?? 'Size' }}</th>
                  <th>{{ __('Performance') }}</th>
                  <th class="rounded-end">{{ __('messages.Statu') ?? 'Statu' }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($banners as $banner)
            @php
                $bnname = mb_strlen($banner->name, 'utf8') > 25 ? mb_substr($banner->name, 0, 25) . "&nbsp;..." : $banner->name;
                $fgft = $banner->statu == 1 ? "ON" : "OFF";
            @endphp
            <tr>
              <td><span class="badge bg-light text-dark border">#{{ $banner->id }}</span></td>
              <td>
                <div class="fw-bold text-dark mb-2">{!! $bnname !!}</div>
               <div class="d-flex align-items-center gap-2">
                   <a href="{{ route('ads.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3"><i class="fa fa-edit"></i></a>
                   <form action="{{ route('ads.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_banner') }}');" style="margin: 0;">
                       @csrf
                       @method('DELETE')
                       <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" aria-label="{{ __('messages.delete_banner') }}">
                           <i class="fa fa-ban"></i>
                       </button>
                   </form>
               </div>
              </td>
              <td>
                  <a href="{{ route('legacy.state', ['ty' => 'banner', 'id' => $banner->id]) }}" class="btn btn-sm btn-warning rounded-pill fw-bold text-dark px-3">{{ $banner->vu }}</a>
                  @if($banner->img_b)
                  <div class="mt-2 small bg-light p-2 rounded-3 border d-inline-block">
                      <span class="text-success fw-bold">A: {{ $banner->vu_a }}</span><span class="mx-2 text-muted">|</span><span class="text-info fw-bold">B: {{ $banner->vu_b }}</span>
                  </div>
                  @endif
              </td>
              <td>
                  <a href="{{ route('legacy.state', ['ty' => 'vu', 'id' => $banner->id]) }}" class="btn btn-sm btn-primary rounded-pill fw-bold px-3">{{ $banner->clik }}</a>
                  @if($banner->img_b)
                  <div class="mt-2 small bg-light p-2 rounded-3 border d-inline-block">
                      <span class="text-success fw-bold">A: {{ $banner->clik_a }}</span><span class="mx-2 text-muted">|</span><span class="text-info fw-bold">B: {{ $banner->clik_b }}</span>
                  </div>
                  @endif
              </td>
              <td><span class="badge bg-secondary rounded-pill">{{ $banner->px }}</span></td>
              <td>
                  @include('theme::partials.ads.mini_heatmap', ['heatmap' => $banner->heatmap])
              </td>
              <td><span class="badge {{ $banner->statu == 1 ? 'bg-success' : 'bg-secondary' }}">{{ $fgft }}</span></td>
            </tr>
            @endforeach
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
