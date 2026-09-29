@extends('theme::layouts.master')

@section('title', __('messages.profile_verification'))

@section('content')
<div class="container py-4 py-lg-5">
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative">
        <div class="card-body p-4 p-md-5 d-flex align-items-center gap-4 position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 border border-white border-opacity-25"><i class="fa fa-circle-check fa-3x"></i></div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.profile_verification') }}</h1>
                <p class="mb-0 text-white text-opacity-75">{{ __('messages.verification_member_intro') }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block"><i class="fa fa-shield-halved fa-10x"></i></div>
    </div>

    <div class="row g-4">
        <aside class="col-lg-3">
            @include('theme::profile.settings_nav')
            <x-widget-column side="portal_left" />
        </aside>
        <main class="col-lg-9">
            @if(session('success'))<div class="alert alert-success rounded-3">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert alert-danger rounded-3">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="alert alert-danger rounded-3">{{ __('messages.please_check_errors') }}</div>@endif

            <section class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-body py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h2 class="h5 fw-black mb-0"><i class="fa fa-circle-check text-primary me-2"></i>{{ __('messages.profile_verification') }}</h2>
                    @if($user->ucheck)<span class="badge rounded-pill text-bg-success">{{ __('messages.verification_status_approved') }}</span>@endif
                </div>
                <div class="card-body p-4">
                    @if($user->ucheck)
                        <div class="alert alert-success mb-0"><i class="fa fa-circle-check me-2"></i>{{ __('messages.verification_already_verified') }}</div>
                    @else
                        @if(!$settings['enabled'])<div class="alert alert-info rounded-3">{{ __('messages.verification_closed') }}</div>@endif
                        @if($request)
                            <div class="rounded-4 border p-4 mb-4">
                                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                                    <h3 class="h6 fw-black mb-0">{{ __('messages.verification_your_request') }}</h3>
                                    <span class="badge rounded-pill {{ $request->status === 'approved' ? 'text-bg-success' : ($request->status === 'rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">{{ __('messages.verification_status_' . $request->status) }}</span>
                                </div>
                                <p class="mb-2" style="white-space:pre-line">{{ $request->reason }}</p>
                                @if($request->evidence_links)<ul class="mb-2">@foreach($request->evidence_links as $link)<li><a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $link }}</a></li>@endforeach</ul>@endif
                                @if($request->reviewer_note)<div class="alert alert-secondary rounded-3 mb-0"><strong>{{ __('messages.verification_reviewer_note') }}:</strong> {{ $request->reviewer_note }}</div>@endif
                            </div>
                        @endif

                        @if($settings['terms'] !== '')
                            <div class="rounded-4 bg-body-tertiary p-4 mb-4">
                                <h3 class="h6 fw-black mb-3">{{ __('messages.verification_terms') }}</h3>
                                <div style="white-space:pre-line">{{ $settings['terms'] }}</div>
                            </div>
                        @endif

                        <div class="rounded-4 border p-4 mb-4">
                            <h3 class="h6 fw-black mb-3">{{ __('messages.verification_eligibility') }}</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fa {{ $settings['enabled'] ? 'fa-check text-success' : 'fa-times text-danger' }} me-2"></i>{{ __('messages.verification_requirement_enabled') }}</li>
                                @if($settings['require_verified_email'])<li class="mb-2"><i class="fa {{ $eligibility['email_verified'] ? 'fa-check text-success' : 'fa-times text-danger' }} me-2"></i>{{ __('messages.verification_requirement_email') }}</li>@endif
                                <li class="mb-2"><i class="fa {{ $eligibility['account_age'] ? 'fa-check text-success' : 'fa-times text-danger' }} me-2"></i>{{ __('messages.verification_requirement_age', ['required' => $settings['min_account_age_days'], 'current' => $eligibility['account_age_days']]) }}</li>
                                <li><i class="fa {{ $eligibility['followers'] ? 'fa-check text-success' : 'fa-times text-danger' }} me-2"></i>{{ __('messages.verification_requirement_followers', ['required' => $settings['min_followers_count'], 'current' => $eligibility['followers_count']]) }}</li>
                            </ul>
                        </div>

                        @if($request?->status === 'pending')
                            <div class="alert alert-info rounded-3 mb-0">{{ __('messages.verification_pending_exists') }}</div>
                        @elseif($eligibility['eligible'])
                            <form action="{{ route('profile.verification.submit') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="reason" class="form-label fw-bold">{{ __('messages.verification_reason') }}</label>
                                    <textarea id="reason" name="reason" rows="5" maxlength="3000" class="form-control rounded-3" required>{{ old('reason', $request?->status === 'rejected' ? $request->reason : '') }}</textarea>
                                    @error('reason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('messages.verification_evidence_links') }}</label>
                                    <div class="form-text mb-2">{{ __('messages.verification_evidence_help') }}</div>
                                    @for($i = 0; $i < 5; $i++)
                                        <input type="url" name="evidence_links[]" value="{{ old('evidence_links.' . $i, $request?->status === 'rejected' ? ($request->evidence_links[$i] ?? '') : '') }}" class="form-control rounded-3 mb-2" placeholder="https://" aria-label="{{ __('messages.verification_evidence_link_number', ['number' => $i + 1]) }}">
                                    @endfor
                                    @error('evidence_links.*')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-check mb-4">
                                    <input type="checkbox" class="form-check-input" id="accept_terms" name="accept_terms" value="1" required {{ old('accept_terms') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="accept_terms">{{ __('messages.verification_accept_terms') }}</label>
                                    @error('accept_terms')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">{{ $request?->status === 'rejected' ? __('messages.verification_resubmit') : __('messages.verification_submit') }}</button>
                            </form>
                        @else
                            <div class="alert alert-warning rounded-3 mb-0">{{ __('messages.verification_not_eligible') }}</div>
                        @endif
                    @endif
                </div>
            </section>
        </main>
    </div>
</div>
@endsection
