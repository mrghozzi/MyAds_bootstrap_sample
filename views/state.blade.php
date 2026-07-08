@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 p-3 rounded me-3">
                    <i class="fa fa-chart-line fa-2x"></i>
                </div>
                <div>
                    <h2 class="h4 fw-bold mb-1">{!! $title !!}</h2>
                    <p class="mb-0 text-white-50">{{ $subtitle }}</p>
                </div>
            </div>
            <a class="btn btn-light fw-bold" href="{{ $backUrl }}">
                <i class="fa fa-chevron-left me-1"></i> {{ __('messages.go_back') }}
            </a>
        </div>
    </div>

    <!-- Stats Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tablepagination" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#ID</th>
                            <th>{{ __('messages.url_link') ?? 'Url' }}</th>
                            <th>{{ __('messages.time') ?? 'Time' }}</th>
                            <th>{{ __('messages.browser') ?? 'Browser' }}</th>
                            <th>{{ __('messages.platform') ?? 'Platform' }}</th>
                            <th class="pe-4 text-end">{{ __('messages.ip') ?? 'Ip' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($states as $state)
                            <tr>
                                <td class="ps-4 text-muted">{{ $state->id }}</td>
                                <td>
                                    @if($state->r_link == 'N')
                                        <span class="badge bg-danger"><i class="fa-solid fa-link-slash"></i></span>
                                    @else
                                        <a class="btn btn-sm btn-outline-success" href="{{ $state->r_link }}" target="_blank">
                                            <i class="fa-solid fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <div class="small fw-bold">{{ date('d, M Y', $state->r_date) }}</div>
                                    <div class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ date('H:i:s', $state->r_date) }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $state->browser['name'] }}</div>
                                    <div class="text-muted smaller" style="font-size: 0.75rem;">{{ $state->browser['version'] }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $state->browser['platform'] }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="http://ip.is-best.net/?ip={{ $state->v_ip }}" target="_blank" title="{{ $state->v_ip }}">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa fa-info-circle fa-2x mb-3 d-block opacity-25"></i>
                                    {{ __('messages.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
