@extends('theme::layouts.master')
@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-5 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-bell fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.notification_settings') }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ __('messages.notification_settings_intro') }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-envelope-open-text fa-10x"></i>
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
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.email_notifications') ?? 'Email Notifications' }}</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    @include('theme::billing.partials.alerts')

                    <form action="{{ route('profile.notifications.update') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            @foreach([
                                'email_new_follower' => [
                                    'label' => __('messages.notif_new_follower'),
                                    'hint' => __('messages.notif_new_follower_hint'),
                                    'icon' => 'fa-user-plus',
                                    'color' => 'primary'
                                ],
                                'email_new_comment' => [
                                    'label' => __('messages.notif_new_comment'),
                                    'hint' => __('messages.notif_new_comment_hint'),
                                    'icon' => 'fa-comment',
                                    'color' => 'primary'
                                ],
                                'email_new_message' => [
                                    'label' => __('messages.notif_new_message'),
                                    'hint' => __('messages.notif_new_message_hint'),
                                    'icon' => 'fa-envelope',
                                    'color' => 'primary'
                                ],
                                'email_mention' => [
                                    'label' => __('messages.notif_mention'),
                                    'hint' => __('messages.notif_mention_hint'),
                                    'icon' => 'fa-at',
                                    'color' => 'primary'
                                ],
                                'email_repost' => [
                                    'label' => __('messages.notif_repost'),
                                    'hint' => __('messages.notif_repost_hint'),
                                    'icon' => 'fa-retweet',
                                    'color' => 'primary'
                                ],
                                'email_reaction' => [
                                    'label' => __('messages.notif_reaction'),
                                    'hint' => __('messages.notif_reaction_hint'),
                                    'icon' => 'fa-heart',
                                    'color' => 'primary'
                                ],
                                'email_forum_reply' => [
                                    'label' => __('messages.notif_forum_reply'),
                                    'hint' => __('messages.notif_forum_reply_hint'),
                                    'icon' => 'fa-comments',
                                    'color' => 'primary'
                                ],
                                'email_marketplace_update' => [
                                    'label' => __('messages.notif_marketplace_update'),
                                    'hint' => __('messages.notif_marketplace_update_hint'),
                                    'icon' => 'fa-shopping-bag',
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
                                                <input class="form-check-input transition-all shadow-none" type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1" {{ $settings->{$field} ? 'checked' : '' }} style="width: 3.2em; height: 1.6em; cursor: pointer;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center pt-5 border-top mt-5">
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
