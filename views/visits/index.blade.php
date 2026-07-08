@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                    <i class="fa fa-exchange fa-3x"></i>
                </div>
                <div>
                    <h1 class="h2 fw-bold mb-1">{{ __('messages.list') }} {{ __('messages.exvisit') }}</h1>
                    <p class="mb-0 text-white-50 small">{{ __('messages.ctevbtexp') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3 mt-md-0">
                <button class="btn btn-light btn-lg fw-bold shadow-sm" onClick="window.open('{{ route('visits.surf') }}', 'SurfWindow', 'width=1024,height=768');">
                    <i class="fa fa-play-circle me-2"></i> {{ __('messages.exvisit') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold">
                    <i class="fa fa-info-circle me-1"></i> {{ __('messages.you_have') }} {{ $user->vu }} {{ __('messages.ptvysa') }}
                </span>
                <span class="ms-3 text-muted small fw-bold">
                    {{ __('messages.yshbv') }}: {{ $visits }}
                </span>
            </div>
            <a href="{{ route('ads.promote', ['p' => 'exchange']) }}" class="btn btn-secondary rounded-pill px-4 fw-bold">
                <i class="fa fa-plus me-1"></i> {{ __('messages.add') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('messages.id') ?? '#ID' }}</th>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('messages.name') ?? 'Name' }}</th>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('messages.vu') ?? 'Vu' }}</th>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('messages.tims') ?? 'Tims' }}</th>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">{{ __('messages.statu') ?? 'Statu' }}</th>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted text-end">{{ __('messages.actions') ?? 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sites as $site)
                                @php
                                    $repvu = array("1","2","3","4");
                                    $repvu_to = array("10s","20s","30s","60s");
                                    $tims_vu = str_replace($repvu, $repvu_to, $site->tims);
                                    $bnname = mb_strlen($site->name, 'utf8') > 35 ? mb_substr($site->name, 0, 35) . "..." : $site->name;
                                @endphp
                                <tr>
                                    <td class="px-4 small text-muted">#{{ $site->id }}</td>
                                    <td class="px-4">
                                        <div class="fw-bold text-dark">{!! $bnname !!}</div>
                                        <div class="smaller text-muted text-truncate" style="max-width: 250px;">{{ $site->url }}</div>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">{{ $site->vu }}</span>
                                    </td>
                                    <td class="px-4">
                                        <span class="small text-muted fw-bold"><i class="fa fa-clock me-1 opacity-50"></i> {{ $tims_vu }}</span>
                                    </td>
                                    <td class="px-4">
                                        @if($site->statu == 1)
                                            <span class="badge bg-success rounded-pill px-3">{{ __('messages.active') ?? 'Active' }}</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill px-3">{{ __('messages.paused') ?? 'Paused' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('visits.edit', $site->id) }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold border">
                                                <i class="fa fa-edit me-1 text-primary"></i> {{ __('messages.edit') }}
                                            </a>
                                            <form action="{{ route('visits.destroy', $site->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_visit') }}');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-sm rounded-pill px-3 fw-bold border">
                                                    <i class="fa fa-trash me-1 text-danger"></i> {{ __('messages.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($sites->isEmpty())
                                <tr>
                                    <td colspan="6" class="p-5 text-center text-muted">
                                        <i class="fa fa-exchange fa-3x mb-3 opacity-10"></i>
                                        <h6 class="fw-bold mb-1">{{ __('messages.no_visits_found') ?? 'No visit exchange sites found' }}</h6>
                                        <p class="mb-0 small">{{ __('messages.add_your_first_site') ?? 'Add your first site to start exchanging visits.' }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
