@extends('theme::layouts.master')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-5 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-share-nodes fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.social_links') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.social_links_desc') ?? 'Connect your social media profiles to show them on your profile.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-hashtag fa-10x"></i>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            @include('theme::profile.settings_nav')
            <x-widget-column side="portal_left" />
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden mb-4">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.social_profiles') ?? 'Social Profiles' }}</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @include('theme::billing.partials.alerts')

                    <form action="{{ route('profile.social.update') }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-light border-0 shadow-sm rounded-4 p-4 mb-5 d-flex align-items-center gap-4">
                            <div class="position-relative">
                                <img src="{{ $user->avatarUrl() }}" class="rounded-circle shadow-sm border border-3 border-white" width="70" height="70" style="object-fit: cover;">
                                <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle" style="width: 15px; height: 15px;"></div>
                            </div>
                            <div>
                                <h5 class="fw-black mb-1 text-dark">{{ $user->username }}</h5>
                                <p class="smaller text-muted fw-bold mb-0 text-uppercase letter-spacing-1">{{ __('messages.manage_your_social_presence') ?? 'Manage your social presence' }}</p>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            @php
                                $platforms = [
                                    'facebook' => ['label' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'color' => '#1877f2', 'placeholder' => 'facebook.com/username'],
                                    'twitter' => ['label' => 'Twitter (X)', 'icon' => 'fab fa-x-twitter', 'color' => '#000000', 'placeholder' => 'twitter.com/username'],
                                    'vkontakte' => ['label' => 'Vkontakte', 'icon' => 'fab fa-vk', 'color' => '#0077ff', 'placeholder' => 'vk.com/username'],
                                    'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'color' => '#0077b5', 'placeholder' => 'linkedin.com/in/username'],
                                    'instagram' => ['label' => 'Instagram', 'icon' => 'fab fa-instagram', 'color' => '#e4405f', 'placeholder' => 'instagram.com/username'],
                                    'youtube' => ['label' => 'YouTube', 'icon' => 'fab fa-youtube', 'color' => '#ff0000', 'placeholder' => 'youtube.com/@channel'],
                                    'threads' => ['label' => 'Threads', 'icon' => 'fab fa-threads', 'color' => '#000000', 'placeholder' => 'threads.net/@username'],
                                    'reddit' => ['label' => 'Reddit', 'icon' => 'fab fa-reddit-alien', 'color' => '#ff4500', 'placeholder' => 'reddit.com/user/username'],
                                    'github' => ['label' => 'GitHub', 'icon' => 'fab fa-github', 'color' => '#333333', 'placeholder' => 'github.com/username'],
                                    'tiktok' => ['label' => 'TikTok', 'icon' => 'fab fa-tiktok', 'color' => '#000000', 'placeholder' => 'tiktok.com/@username'],
                                    'discord' => ['label' => 'Discord', 'icon' => 'fab fa-discord', 'color' => '#5865F2', 'placeholder' => 'discord.gg/invite'],
                                ];
                            @endphp

                            @foreach($platforms as $id => $platform)
                                <div class="col-md-6">
                                    <label for="{{ $id }}" class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted">{{ $platform['label'] }}</label>
                                    <div class="input-group bg-light rounded-pill border border-light p-1 transition-all focus-within-shadow">
                                        <span class="input-group-text bg-white rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                            <i class="{{ $platform['icon'] }} fs-5" style="color: {{ $platform['color'] }};"></i>
                                        </span>
                                        <input type="text" id="{{ $id }}" name="{{ $id }}" class="form-control bg-transparent border-0 px-3 fw-bold shadow-none" 
                                               value="{{ old($id, $links[$id] ?? '') }}" placeholder="{{ $platform['placeholder'] }}">
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-md-6">
                                <label for="adstn" class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted">{{ __('messages.adstn') ?? 'ADStn' }}</label>
                                <div class="input-group bg-light rounded-pill border border-light p-1 transition-all focus-within-shadow">
                                    <span class="input-group-text bg-white rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                        <i class="fa-brands fa-buysellads fs-5" style="color: #5438a3;"></i>
                                    </span>
                                    <input type="text" id="adstn" name="adstn" class="form-control bg-transparent border-0 px-3 fw-bold shadow-none" 
                                           value="{{ old('adstn', $links['adstn'] ?? '') }}" placeholder="username">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center pt-5 border-top">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg transition-all hover-translate-y">
                                <i class="fa fa-save me-2"></i> {{ __('messages.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
