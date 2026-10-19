@extends('theme::layouts.master')

@push('head')
{{-- Google Schema.org Structured Data --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'SoftwareApplication',
    'name' => $detail['title'] ?? $listing->name,
    'url' => $listing->url,
    'description' => $detail['excerpt'] ?? $listing->txt,
    'applicationCategory' => $detail['category_name'] ?? ($listing->category?->name ?? 'General'),
    'operatingSystem' => 'All',
    'offers' => [
        '@type' => 'Offer',
        'price' => in_array($detail['pricing_type'] ?? 'free', ['free', 'open_source']) ? '0' : '',
        'priceCurrency' => 'USD',
    ],
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => (string) min(5.0, max(4.0, 4.0 + (($detail['upvotes_count'] ?? 0) > 0 ? 0.9 : 0.0))),
        'reviewCount' => (string) max(1, ($detail['upvotes_count'] ?? 0) + ($detail['reactions_count'] ?? 0) + ($detail['comments_count'] ?? 0)),
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
@php
    $listingImage = $listing->prominent_image ?: theme_asset('img/error_plug.png');
    $owner = $listing->user;
    $commentCount = $detail['comments_count'] ?? \App\Models\Option::where('o_type', 'd_coment')->where('o_parent', $listing->id)->count();
@endphp

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 px-4 rounded-pill shadow-sm border">
            <li class="breadcrumb-item"><a href="{{ route('directory.index') }}" class="text-decoration-none text-info fw-bold"><i class="fa fa-sitemap"></i></a></li>
            @if($listing->category)
                <li class="breadcrumb-item"><a href="{{ route('directory.category', $listing->category->id) }}" class="text-decoration-none text-muted small">{{ $listing->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active text-truncate small fw-bold" aria-current="page" style="max-width: 300px;">{{ $detail['title'] ?? $listing->name }}</li>
        </ol>
    </nav>

    @include('theme::partials.ads', ['id' => 5])

    <div class="row g-4 mt-2 post{{ $listing->id }}">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                        <div class="d-flex gap-4 align-items-center">
                            <div class="bg-white p-2 rounded-4 shadow-sm border d-flex align-items-center justify-content-center transition-all hover-shadow-lg" style="width: 90px; height: 90px;">
                                <img src="{{ $listingImage }}" class="img-fluid rounded-3 object-fit-contain" alt="{{ $listing->name }}" style="max-height: 75px;">
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <h1 class="fw-black mb-0 text-dark h2">{{ $detail['title'] ?? $listing->name }}</h1>
                                    @if($detail['is_verified'] ?? false)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill border border-success border-opacity-25" title="{{ __('messages.verified_site') ?? 'Verified Site' }}">
                                            <i class="fa fa-check-circle me-1"></i> {{ __('messages.verified_site') ?? 'Verified' }}
                                        </span>
                                    @endif
                                    @if($detail['is_featured'] ?? false)
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill border border-warning border-opacity-25">
                                            <i class="fa fa-star me-1"></i> {{ __('messages.featured_site') ?? 'Featured' }}
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge rounded-pill {{ ($detail['pricing_type'] ?? 'free') === 'free' ? 'bg-success' : (($detail['pricing_type'] ?? '') === 'paid' ? 'bg-danger' : (($detail['pricing_type'] ?? '') === 'open_source' ? 'bg-secondary' : 'bg-primary')) }}">
                                        {{ $detail['pricing_label'] ?? 'Free' }}
                                    </span>
                                    <a href="{{ $detail['visit_url'] ?? $listing->url }}" target="_blank" class="text-muted small text-decoration-none hover-primary">
                                        <i class="fa fa-link me-1"></i> {{ $detail['display_domain'] ?? parse_url($listing->url, PHP_URL_HOST) }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <!-- Upvote Button -->
                            <button type="button" 
                                    class="btn {{ ($detail['has_upvoted'] ?? false) ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 py-2 directory-upvote-btn {{ ($detail['has_upvoted'] ?? false) ? 'is-upvoted' : '' }}" 
                                    data-directory-upvote="{{ $listing->id }}"
                                    @guest onclick="window.location='{{ route('login') }}'" @endguest
                                    aria-label="{{ __('messages.upvote') ?? 'Upvote' }}">
                                <span class="me-1">▲</span>
                                <span>{{ __('messages.upvote') ?? 'Upvote' }}</span>
                                <span class="directory-upvote-count badge bg-white text-primary rounded-pill ms-2 fw-bold" data-count="{{ $detail['upvotes_count'] ?? 0 }}">{{ $detail['upvotes_count'] ?? 0 }}</span>
                            </button>

                            <!-- Options Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown" style="width: 38px; height: 38px; padding: 0;"><i class="fa fa-ellipsis-v"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                    <li><button class="dropdown-item py-2 small fw-bold" onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa fa-copy me-2 text-muted"></i> {{ __('messages.copy_link') }}</button></li>
                                    @if(auth()->check() && (auth()->id() == $listing->uid || auth()->user()->isAdmin()))
                                        <li><a class="dropdown-item py-2 small fw-bold" href="{{ route('directory.edit', $listing->id) }}"><i class="fa fa-edit me-2 text-muted"></i> {{ __('messages.edit') }}</a></li>
                                        <li><button class="dropdown-item py-2 small fw-bold text-danger" onclick="deletePost({{ $listing->id }}, 'directory', '.post{{ $listing->id }}')"><i class="fa fa-trash me-2"></i> {{ __('messages.delete') }}</button></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item py-2 small fw-bold text-muted" onclick="reportPost({{ $listing->id }}, 'directory')"><i class="fa fa-flag me-2"></i> {{ __('messages.report') }}</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Key Features -->
                    @if(!empty($detail['features']))
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted small text-uppercase mb-2"><i class="fa fa-check-circle text-success me-1"></i> {{ __('messages.key_features') ?? 'Key Features' }}</h6>
                            <div class="row g-2">
                                @foreach($detail['features'] as $feature)
                                    <div class="col-sm-6">
                                        <div class="p-2 bg-light rounded-3 border small d-flex align-items-center gap-2">
                                            <i class="fa fa-check text-success"></i>
                                            <span class="text-dark fw-semibold">{{ $feature }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="bg-light bg-opacity-50 p-4 rounded-4 mb-4 border">
                        <div class="lh-lg text-dark" style="white-space: pre-line;">{!! $detail['description_html'] ?? nl2br(e($listing->txt)) !!}</div>
                    </div>

                    <!-- Tags -->
                    @if(!empty($detail['tags']))
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach($detail['tags'] as $tag)
                                <span class="badge bg-light text-muted rounded-pill px-3 py-2 border">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ $detail['visit_url'] ?? $listing->url }}" target="_blank" class="btn btn-info btn-lg text-white rounded-pill px-5 fw-black shadow-sm transition-all hover-translate-y">
                            <i class="fa fa-external-link-alt me-2"></i> {{ __('messages.visit_website') }}
                        </a>
                        <button class="btn btn-outline-info btn-lg rounded-pill px-5 fw-black shadow-sm transition-all hover-translate-y" onclick="toggleReaction({{ $listing->id }}, 'directory', 'like')" id="reaction-btn-{{ $listing->id }}">
                            <i class="fa fa-thumbs-up me-2"></i> {{ __('messages.like') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-black mb-0 text-dark">{{ __('messages.comments') }}</h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3">{{ $commentCount }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="post-comment-list post-comment-list-{{ $listing->id }}" id="directory-comments-{{ $listing->id }}">
                        <div class="text-center py-5">
                            <div class="spinner-border text-info" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Website Stats Widget -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-uppercase smaller text-muted letter-spacing-1">{{ __('messages.website_info') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center justify-content-between py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded-3 me-3 text-muted">
                                    <i class="fa fa-arrow-up text-primary"></i>
                                </div>
                                <span class="small text-muted fw-bold">{{ __('messages.upvotes') ?? 'Upvotes' }}</span>
                            </div>
                            <span class="small fw-black text-primary upvote-stat-val">{{ $detail['upvotes_count'] ?? 0 }}</span>
                        </div>
                        <div class="list-group-item d-flex align-items-center justify-content-between py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded-3 me-3 text-muted">
                                    <i class="fa fa-eye"></i>
                                </div>
                                <span class="small text-muted fw-bold">{{ __('messages.visits') }}</span>
                            </div>
                            <span class="badge bg-info text-white rounded-pill px-3">{{ number_format($detail['views'] ?? ($listing->visits ?? 0)) }}</span>
                        </div>
                        <div class="list-group-item d-flex align-items-center justify-content-between py-3 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded-3 me-3 text-muted">
                                    <i class="fa fa-calendar-alt"></i>
                                </div>
                                <span class="small text-muted fw-bold">{{ __('messages.added_on') }}</span>
                            </div>
                            <span class="small fw-black text-dark">{{ \Carbon\Carbon::createFromTimestamp($listing->date)->format('M d, Y') }}</span>
                        </div>
                        @if($listing->category)
                            <div class="list-group-item d-flex align-items-center justify-content-between py-3 border-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-2 rounded-3 me-3 text-muted">
                                        <i class="fa fa-tag"></i>
                                    </div>
                                    <span class="small text-muted fw-bold">{{ __('messages.category') }}</span>
                                </div>
                                <a href="{{ route('directory.category', $listing->category->id) }}" class="small fw-black text-info text-decoration-none">{{ $listing->category->name }}</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-light p-3 d-flex flex-column gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#embedBadgeModal">
                        <i class="fa fa-code me-1"></i> {{ __('messages.embed_badge') ?? 'Embed Badge' }}
                    </button>
                    @if(!($detail['is_verified'] ?? false))
                        <button type="button" class="btn btn-outline-success btn-sm rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#claimModal">
                            <i class="fa fa-shield me-1"></i> {{ __('messages.claim_listing') ?? 'Claim Listing' }}
                        </button>
                    @endif
                </div>
            </div>

            <!-- Owner Card -->
            @if($owner)
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden text-center">
                    <div class="bg-info bg-opacity-10 py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-uppercase smaller text-info letter-spacing-1">{{ __('messages.added_by') }}</h6>
                    </div>
                    <div class="card-body p-4">
                        <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="d-block mb-3 position-relative">
                            <img src="{{ $owner->avatarUrl() }}" alt="" class="rounded-circle border border-4 border-white shadow-sm transition-all hover-scale" width="90" height="90" style="object-fit: cover;">
                        </a>
                        <h5 class="fw-black mb-1">
                            <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="text-dark text-decoration-none">{{ $owner->username }}</a>
                        </h5>
                        <p class="smaller text-muted mb-3">{{ '@' . $owner->username }}</p>
                        <div class="d-grid">
                            <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="btn btn-outline-info rounded-pill fw-bold shadow-sm">{{ __('messages.view_profile') }}</a>
                        </div>
                    </div>
                </div>
            @endif

            <x-widget-column side="directory_sidebar" />
        </div>
    </div>
</div>

<!-- Embed Badge Modal -->
<div class="modal fade" id="embedBadgeModal" tabindex="-1" aria-labelledby="embedBadgeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="embedBadgeModalLabel"><i class="fa fa-code text-primary me-2"></i> {{ __('messages.embed_badge_title') ?? 'Embed Badge on Your Website' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">{{ __('messages.embed_badge_desc') ?? 'Display this dynamic badge on your website to show off your rank and upvotes on MYADS Directory:' }}</p>
                <div class="text-center p-3 bg-light rounded-3 mb-3 border">
                    <img src="{{ route('directory.badge', $listing->id) }}" alt="Featured on MYADS" class="img-fluid">
                </div>
                
                <label class="form-label small fw-bold">HTML Code:</label>
                <div class="input-group mb-3">
                    <textarea class="form-control form-control-sm font-monospace" id="html-badge-code" rows="2" readonly>&lt;a href="{{ route('directory.show', $listing->id) }}" target="_blank" rel="noopener"&gt;&lt;img src="{{ route('directory.badge', $listing->id) }}" alt="Featured on MYADS Directory" /&gt;&lt;/a&gt;</textarea>
                    <button class="btn btn-primary btn-sm px-3 copy-badge-code" data-target="#html-badge-code"><i class="fa fa-copy"></i></button>
                </div>

                <label class="form-label small fw-bold">Markdown Code:</label>
                <div class="input-group">
                    <textarea class="form-control form-control-sm font-monospace" id="md-badge-code" rows="2" readonly>[![Featured on MYADS Directory]({{ route('directory.badge', $listing->id) }})]({{ route('directory.show', $listing->id) }})</textarea>
                    <button class="btn btn-secondary btn-sm px-3 copy-badge-code" data-target="#md-badge-code"><i class="fa fa-copy"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Claim Listing Modal -->
<div class="modal fade" id="claimModal" tabindex="-1" aria-labelledby="claimModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="claimModalLabel"><i class="fa fa-shield text-success me-2"></i> {{ __('messages.claim_listing_title') ?? 'Claim Site Ownership' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @if(auth()->check())
                    <p class="text-muted small mb-3">{{ __('messages.claim_listing_desc') ?? 'To verify that you own this site, copy and paste this verification meta tag into the <head> section of your website homepage:' }}</p>
                    
                    @php
                        $verificationToken = 'myads-verify-' . hash('crc32', $listing->id . '-' . auth()->id());
                    @endphp

                    <div class="input-group mb-3">
                        <textarea class="form-control form-control-sm font-monospace" id="meta-tag-code" rows="2" readonly>&lt;meta name="myads-verification" content="{{ $verificationToken }}"&gt;</textarea>
                        <button class="btn btn-success btn-sm px-3 copy-badge-code" data-target="#meta-tag-code"><i class="fa fa-copy"></i></button>
                    </div>

                    <div id="claim-status-message" class="alert alert-info py-2 small" style="display: none;"></div>

                    <div class="d-grid mt-3">
                        <button type="button" class="btn btn-success fw-bold rounded-pill" id="btn-verify-claim" data-listing-id="{{ $listing->id }}">
                            <i class="fa fa-check-circle me-1"></i> {{ __('messages.verify_now') ?? 'Verify Ownership Now' }}
                        </button>
                    </div>
                @else
                    <p class="text-muted small mb-3">{{ __('messages.login_to_claim') ?? 'Please sign in first to verify ownership of this website listing.' }}</p>
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill w-100 fw-bold">{{ __('messages.login') }}</a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-3px); }
    .hover-shadow-lg:hover { box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important; }
    .hover-scale:hover { transform: scale(1.05); }
    .post-comment-list img { max-width: 100%; border-radius: 12px; }
    .breadcrumb-item + .breadcrumb-item::before { color: #dee2e6; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const CSRF_TOKEN = '{{ csrf_token() }}';

    if (typeof loadComments === 'function') {
        loadComments({{ $listing->id }}, 'directory');
    }

    // Upvote handler
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-directory-upvote]');
        if (!btn || btn.dataset.loading === '1') return;

        e.preventDefault();
        const listingId = btn.getAttribute('data-directory-upvote');
        if (!listingId) return;

        btn.dataset.loading = '1';

        fetch('/directory/' + listingId + '/upvote', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (res.status === 401) {
                window.location.href = '/login';
                return null;
            }
            return res.json();
        })
        .then(data => {
            btn.dataset.loading = '0';
            if (!data || !data.success) return;

            if (data.upvoted) {
                btn.classList.remove('btn-outline-primary');
                btn.classList.add('btn-primary', 'is-upvoted');
            } else {
                btn.classList.remove('btn-primary', 'is-upvoted');
                btn.classList.add('btn-outline-primary');
            }

            const countEl = btn.querySelector('.directory-upvote-count');
            if (countEl) countEl.textContent = data.upvotes_count;

            document.querySelectorAll('.upvote-stat-val').forEach(el => el.textContent = data.upvotes_count);
        })
        .catch(err => {
            btn.dataset.loading = '0';
            console.error(err);
        });
    });

    // Copy badge codes
    document.querySelectorAll('.copy-badge-code').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            if (target) {
                target.select();
                navigator.clipboard.writeText(target.value).then(() => {
                    const orig = this.innerHTML;
                    this.innerHTML = '<i class="fa fa-check"></i>';
                    setTimeout(() => this.innerHTML = orig, 2000);
                });
            }
        });
    });

    // Claim Verification Ajax
    const claimBtn = document.getElementById('btn-verify-claim');
    const claimStatus = document.getElementById('claim-status-message');
    if (claimBtn) {
        claimBtn.addEventListener('click', function() {
            claimBtn.disabled = true;
            claimBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Checking website...';
            claimStatus.style.display = 'none';

            fetch('/directory/' + claimBtn.dataset.listingId + '/claim', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                claimStatus.style.display = 'block';
                claimStatus.className = data.success ? 'alert alert-success py-2 small' : 'alert alert-danger py-2 small';
                claimStatus.textContent = data.message || (data.success ? 'Site verified successfully!' : 'Verification tag not found.');
                if (data.success) {
                    setTimeout(() => window.location.reload(), 1500);
                }
            })
            .catch(err => {
                claimStatus.style.display = 'block';
                claimStatus.className = 'alert alert-danger py-2 small';
                claimStatus.textContent = 'Verification error occurred. Please try again.';
            })
            .finally(() => {
                claimBtn.disabled = false;
                claimBtn.innerHTML = '<i class="fa fa-check-circle me-1"></i> {{ __("messages.verify_now") ?? "Verify Ownership Now" }}';
            });
        });
    }
});
</script>
@endpush
@endsection
