@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-download fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('downloads') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ $product->name }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-cloud-download-alt fa-10x"></i>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-black mb-0 text-dark">{{ __('downloads') }}</h4>
        <a href="{{ route('store.show', $product->name) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
            <i class="fa fa-arrow-left me-1"></i>
            {{ __('messages.back') ?? 'Back' }}
        </a>
    </div>

    <div class="row g-4">
        @forelse($licenses as $license)
            @php
                $userImg = $license->avatar;
                if (!$userImg) {
                    $avatarUrl = asset('upload/avatar.png');
                } elseif (\Illuminate\Support\Str::startsWith($userImg, ['http://', 'https://'])) {
                    $avatarUrl = $userImg;
                } else {
                    $avatarUrl = asset($userImg);
                }
            @endphp
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden border border-light transition-all hover-translate-y h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <a href="{{ route('profile.show', $license->username) }}" class="d-inline-block mb-3">
                                <img src="{{ $avatarUrl }}" alt="{{ $license->username }}" class="rounded-circle border border-primary border-2 p-1" style="width: 80px; height: 80px; object-fit: cover;">
                            </a>
                            <h5 class="fw-black mb-1">
                                <a href="{{ route('profile.show', $license->username) }}" class="text-decoration-none text-dark hover-primary">{{ $license->username }}</a>
                            </h5>
                            <p class="text-muted smaller fw-bold mb-3">{{ __('messages.member') ?? 'Member' }}</p>
                        </div>
                        
                        <div>
                            <div class="bg-light p-2 rounded-3 mb-3">
                                <div class="smaller text-muted fw-bold mb-1">{{ __('messages.downloaded') ?? 'Downloaded' }}</div>
                                <div class="small fw-black text-dark" title="{{ $license->created_at }}">{{ \Carbon\Carbon::parse($license->created_at)->diffForHumans() }}</div>
                            </div>
                            
                            <a href="{{ route('profile.show', $license->username) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-black">
                                {{ __('messages.view_profile') ?? 'View Profile' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="p-5 text-center bg-white shadow-sm rounded-4 border border-light">
                    <div class="rounded-circle bg-light p-4 d-inline-flex mb-4">
                        <i class="fa fa-users fa-3x text-muted opacity-25"></i>
                    </div>
                    <h4 class="fw-black text-dark">{{ __('messages.no_results') ?? 'No results found.' }}</h4>
                    <p class="text-muted small mb-0 fw-bold">{{ __('messages.no_downloads_yet') ?? 'No users have downloaded this product yet.' }}</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($licenses->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $licenses->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
