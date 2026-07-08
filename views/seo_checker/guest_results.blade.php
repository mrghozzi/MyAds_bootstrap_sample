@php
    $seo = (object) [
        'title' => __('messages.seo_checker'),
        'robots' => 'index, follow'
    ];
@endphp
@extends('theme::layouts.oauth')

@section('title', __('messages.seo_checker'))

@section('content')
<div class="card border-0 shadow-lg rounded-4 overflow-hidden w-100" style="max-width: 900px;">
    <div class="card-body p-0">
        @include('theme::seo_checker._results_content', ['results' => $results, 'settings' => $settings, 'userRole' => $userRole])
    </div>
</div>
@endsection
