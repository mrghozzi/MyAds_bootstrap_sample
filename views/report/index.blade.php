@extends('theme::layouts.master')

@section('content')
<div class="content mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-bottom pt-4 pb-3 fw-bold fs-5 text-danger">
                    <i class="fa fa-flag me-2"></i>{{ __('messages.report') }}
                </div>

                <div class="card-body p-4 p-md-5">
                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm fw-bold"><i class="fa fa-check-circle me-2"></i> {{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm fw-bold"><i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}</div>
                    @endif

                    <!-- ITEM PREVIEW -->
                    <div class="report-item p-4 mb-4 bg-light rounded-4 text-center border border-dashed">
                        @if($type == 'link')
                            <a href="{{ $item->url }}" class="btn btn-outline-primary rounded-pill fw-bold" target="_blank">{{ $item->name }} <i class="fa fa-external-link ms-1"></i></a>
                        @elseif($type == 'banner')
                            <div class="rounded-3 shadow-sm mx-auto" style="background-image: url('{{ $item->img }}'); height: {{ $item->px == 1 ? '90' : ($item->px == 2 ? '250' : '60') }}px; width: {{ $item->px == 1 ? '728' : ($item->px == 2 ? '300' : '468') }}px; max-width: 100%; background-size: contain; background-repeat: no-repeat; background-position: center; border: 1px solid #dee2e6;"></div>
                        @elseif($type == 'smart')
                            <div class="mx-auto p-3 rounded-4 bg-white shadow-sm border" style="max-width: 420px;">
                                @if($item->displayImage())
                                    <img src="{{ $item->displayImage() }}" alt="{{ $item->displayTitle() }}" class="w-100 rounded-3 mb-3 object-fit-cover" style="max-height: 180px;">
                                @endif
                                <h5 class="fw-bold text-dark mb-2">{{ $item->displayTitle() }}</h5>
                                <p class="text-muted small mb-0">{{ \Illuminate\Support\Str::limit($item->displayDescription(), 160) }}</p>
                            </div>
                        @elseif($type == 'order')
                            <div class="mx-auto p-4 rounded-4 bg-white shadow-sm border text-start" style="max-width: 500px;">
                                <h5 class="fw-bold text-dark mb-2">{{ $item->title }}</h5>
                                <p class="text-muted small mb-0">{{ \Illuminate\Support\Str::limit($item->description, 200) }}</p>
                            </div>
                        @elseif($type == 'user')
                            <div class="mx-auto p-4 rounded-4 bg-white shadow-sm border" style="max-width: 300px;">
                                <div class="user-avatar medium mx-auto mb-3">
                                    <div class="user-avatar-content">
                                        <div class="hexagon-image-68-74" data-src="{{ $item->avatarUrl() }}"></div>
                                    </div>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">{{ $item->username }}</h5>
                                <p class="text-muted small fw-bold mb-0">{{ $item->name }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- FORM -->
                    <form action="{{ route('report.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="s_type" value="{{ $typeId }}">
                        <input type="hidden" name="tp_id" value="{{ $item->id }}">
                        
                        <div class="mb-4">
                            <label for="txt" class="form-label small fw-bold">{{ __('messages.reason') }}</label>
                            <textarea id="txt" name="txt" class="form-control form-control-lg bg-light border-0 p-3" rows="4" required placeholder="{{ __('messages.reason_desc') }}"></textarea>
                        </div>
                        
                        <div class="text-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 fw-bold shadow-sm">
                                <i class="fa fa-flag me-2"></i> {{ __('messages.submit_report') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important; }
</style>
@endsection
