@extends('theme::layouts.master')

@section('content')
<div class="row d-none d-lg-flex mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4" style="background: url({{ theme_asset('img/banner/03.jpg') }}) no-repeat center center; background-size: cover; position: relative; z-index: 1;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); border-radius: 1rem; z-index: -1;"></div>
            <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img class="me-4 rounded-circle bg-white p-2" src="{{ theme_asset('img/banner/link_ads.png') }}" alt="link-ads" style="width: 80px; height: 80px;">
                    <div>
                        <p class="text-white fs-3 fw-bold mb-1">{{ __('messages.list') }}&nbsp;{{ __('messages.textads') }}</p>
                        <p class="text-white-50 mb-0 fs-5"><b>{{ __('messages.yhtierbpyaci') }}</b></p>
                    </div>
                </div>

                <a class="btn btn-light btn-lg rounded-pill fw-bold" href="{{ route('legacy.l_code') }}">
                    <i class="fa fa-code me-2"></i>{{ __('messages.codes') }}&nbsp;{{ __('messages.textads') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <a class="btn btn-light rounded-pill border fw-bold px-4" href="{{ route('legacy.state', ['ty' => 'link', 'st' => 'vu']) }}">
        <i class="fa fa-line-chart text-primary"></i>
    </a>
    
    <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-4 py-2 fs-6 fw-bold">
        <i class="fa fa-info-circle me-2"></i>{{ __('messages.you_have') }}&nbsp;{{ $user->nlink }}&nbsp;{{ __('messages.ptcyta') }}
    </div>

    <a href="{{ route('ads.promote', ['p' => 'link']) }}" class="btn btn-primary rounded-pill fw-bold px-4">
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
               <th>{{ __('Performance') }}</th>
               <th class="rounded-end">{{ __('messages.Statu') ?? 'Statu' }}</th>
              </tr>
             </thead>
             <tbody>
              @foreach($links as $link)
              @php
                  $bnname = mb_strlen($link->name, 'utf8') > 25 ? mb_substr($link->name, 0, 25) . "&nbsp;..." : $link->name;
                  $fgft = $link->statu == 1 ? "ON" : "OFF";
                  // Calculate Vu count (visits where pid=link_id and t_name='link')
                  $vuCount = \App\Models\State::where('pid', $link->id)->where('t_name', 'link')->count();
              @endphp
              <tr>
                <td><span class="badge bg-light text-dark border">#{{ $link->id }}</span></td>
                <td>
                  <div class="fw-bold text-dark mb-2">{!! $bnname !!}</div>
                  <div class="d-flex align-items-center gap-2">
                      <a href="{{ route('ads.links.edit', $link->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3"><i class="fa fa-edit"></i></a>
                      <form action="{{ route('ads.links.destroy', $link->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_link') }}');" style="margin: 0;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" aria-label="{{ __('messages.delete_link') }}">
                              <i class="fa fa-ban"></i>
                          </button>
                      </form>
                  </div>
                </td>
                 <td>
                     <a href="{{ route('legacy.state', ['ty' => 'link', 'id' => $link->id]) }}" class="btn btn-sm btn-warning rounded-pill fw-bold text-dark px-3">{{ $vuCount }}</a>
                     @if($link->name_b || $link->txt_b)
                     <div class="mt-2 small bg-light p-2 rounded-3 border d-inline-block">
                         <span class="text-success fw-bold">A: {{ $link->vu_a }}</span><span class="mx-2 text-muted">|</span><span class="text-info fw-bold">B: {{ $link->vu_b }}</span>
                     </div>
                     @endif
                 </td>
                 <td>
                     <a href="{{ route('legacy.state', ['ty' => 'clik', 'id' => $link->id]) }}" class="btn btn-sm btn-primary rounded-pill fw-bold px-3">{{ $link->clik }}</a>
                     @if($link->name_b || $link->txt_b)
                     <div class="mt-2 small bg-light p-2 rounded-3 border d-inline-block">
                         <span class="text-success fw-bold">A: {{ $link->clik_a }}</span><span class="mx-2 text-muted">|</span><span class="text-info fw-bold">B: {{ $link->clik_b }}</span>
                     </div>
                     @endif
                 </td>
                 <td>
                     @include('theme::partials.ads.mini_heatmap', ['heatmap' => $link->heatmap])
                 </td>
                 <td><span class="badge {{ $link->statu == 1 ? 'bg-success' : 'bg-secondary' }}">{{ $fgft }}</span></td>
              </tr>
              @endforeach
             </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
