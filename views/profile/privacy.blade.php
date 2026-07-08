@extends('theme::layouts.master')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-5 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-shield-alt fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.privacy_settings') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.privacy_settings_desc') ?? 'Manage who can see your profile and interact with you.' }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-user-shield fa-10x"></i>
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
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.privacy_controls') ?? 'Privacy Controls' }}</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @include('theme::billing.partials.alerts')

                    @if(!empty($upgradeNotice))
                        @include('theme::partials.upgrade_notice', ['upgradeNotice' => $upgradeNotice])
                    @endif

                    <form action="{{ route('profile.privacy.update') }}" method="POST">
                        @csrf
                        <fieldset {{ !($featureAvailable ?? true) ? 'disabled' : '' }}>
                            
                            <div class="mb-5">
                                <h6 class="fw-black text-muted text-uppercase smaller letter-spacing-1 mb-4 d-flex align-items-center">
                                    <span class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle me-3"><i class="fa fa-eye small"></i></span>
                                    {{ __('messages.visibility_settings') ?? 'Visibility Settings' }}
                                </h6>
                                
                                <div class="row g-4">
                                    @foreach([
                                        'profile_visibility' => __('messages.profile_visibility'),
                                        'about_visibility' => __('messages.about_visibility'),
                                        'photos_visibility' => __('messages.photos_visibility'),
                                        'followers_visibility' => __('messages.followers_visibility'),
                                        'following_visibility' => __('messages.following_visibility'),
                                        'points_history_visibility' => __('messages.points_history_visibility'),
                                    ] as $field => $label)
                                        <div class="col-md-6">
                                            <label class="form-label fw-black smaller text-uppercase letter-spacing-1 text-muted" for="{{ $field }}">{{ $label }}</label>
                                            <div class="input-group bg-light rounded-pill border border-light p-1">
                                                <span class="input-group-text bg-transparent border-0"><i class="fa fa-globe text-primary opacity-50"></i></span>
                                                <select id="{{ $field }}" name="{{ $field }}" class="form-select bg-transparent border-0 px-2 fw-bold shadow-none">
                                                    @foreach($visibilityOptions as $option)
                                                        <option value="{{ $option }}" {{ $privacySettings->{$field} === $option ? 'selected' : '' }}>
                                                            {{ __('messages.visibility_' . $option) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-5">
                                <h6 class="fw-black text-muted text-uppercase smaller letter-spacing-1 mb-4 d-flex align-items-center">
                                    <span class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle me-3"><i class="fa fa-handshake small"></i></span>
                                    {{ __('messages.interaction_settings') ?? 'Interaction Settings' }}
                                </h6>

                                <div class="row g-4">
                                    @foreach([
                                        'allow_direct_messages' => [
                                            'label' => __('messages.allow_direct_messages'),
                                            'hint' => __('messages.allow_direct_messages_hint'),
                                            'icon' => 'fa-envelope',
                                            'color' => 'primary'
                                        ],
                                        'allow_mentions' => [
                                            'label' => __('messages.allow_mentions'),
                                            'hint' => __('messages.allow_mentions_hint'),
                                            'icon' => 'fa-at',
                                            'color' => 'primary'
                                        ],
                                        'allow_reposts' => [
                                            'label' => __('messages.allow_reposts'),
                                            'hint' => __('messages.allow_reposts_hint'),
                                            'icon' => 'fa-retweet',
                                            'color' => 'primary'
                                        ],
                                        'show_online_status' => [
                                            'label' => __('messages.show_online_status'),
                                            'hint' => __('messages.show_online_status_hint'),
                                            'icon' => 'fa-signal',
                                            'color' => 'primary'
                                        ],
                                    ] as $field => $config)
                                        <div class="col-md-6">
                                            <div class="card h-100 border-0 shadow-sm rounded-4 transition-all hover-translate-y bg-light bg-opacity-25 border border-light overflow-hidden">
                                                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center gap-4">
                                                        <div class="bg-{{ $config['color'] }} bg-opacity-10 text-{{ $config['color'] }} rounded-4 d-flex align-items-center justify-content-center shadow-sm border border-{{ $config['color'] }} border-opacity-10" style="width: 60px; height: 60px;">
                                                            <i class="fa {{ $config['icon'] }} fs-4"></i>
                                                        </div>
                                                        <div>
                                                            <label class="fw-black text-dark fs-6 d-block mb-1 cursor-pointer" for="{{ $field }}">{{ $config['label'] }}</label>
                                                            <p class="text-muted smallest fw-bold mb-0 lh-sm">{{ $config['hint'] }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-check form-switch ms-3">
                                                        <input type="hidden" name="{{ $field }}" value="0">
                                                        <input class="form-check-input transition-all shadow-none" type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1" {{ $privacySettings->{$field} ? 'checked' : '' }} style="width: 3.2em; height: 1.6em; cursor: pointer;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex justify-content-center pt-5 border-top mt-5">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg transition-all hover-translate-y" {{ !($featureAvailable ?? true) ? 'disabled' : '' }}>
                                    <i class="fa fa-save me-2"></i> {{ __('messages.save_changes') }}
                                </button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
