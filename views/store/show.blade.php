@extends('theme::layouts.master')

@section('content')
@php
    $productImage = $product->product_image ?? theme_asset('img/error_plug.png');
    $commentCount = \App\Models\Option::where('o_type', 's_coment')->where('o_parent', $product->id)->count();
    $categoryName = $type ? $type->name : '';
    $categoryLabel = $categoryName ? (__('messages.' . $categoryName) ?? $categoryName) : '';
    $owner = $product->user;
    $latestVersionLabel = $latestFile ? $latestFile->name : 'v1.0';
    $fileCount = $files->count();
    $reportKey = 'product' . $product->id;
@endphp
@include('theme::store.partials.kb-superdesign-formatter')

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 px-4 rounded-pill shadow-sm border">
            <li class="breadcrumb-item"><a href="{{ route('store.index') }}" class="text-decoration-none text-primary fw-bold"><i class="fa fa-shopping-cart"></i></a></li>
            @if($categoryLabel)
                <li class="breadcrumb-item"><a href="{{ route('store.index', ['category' => $categoryName]) }}" class="text-decoration-none text-muted small">{{ $categoryLabel }}</a></li>
            @endif
            <li class="breadcrumb-item active text-truncate small fw-bold" aria-current="page" style="max-width: 300px;">{{ $product->name }}</li>
        </ol>
    </nav>

    @include('theme::partials.ads', ['id' => 5])

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($isSuspended)
        <div class="alert alert-danger rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i> {{ __('messages.product_suspended_notice') }}
        </div>
    @endif

    <div class="row g-4 post{{ $status ? $status->id : $product->id }}">
        <!-- Product Main Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="row g-0">
                    <div class="col-md-5">
                        <div class="bg-light h-100 d-flex align-items-center justify-content-center p-4 border-end position-relative overflow-hidden">
                            <img src="{{ $productImage }}" class="img-fluid rounded-4 shadow transition-all hover-scale position-relative z-1" alt="{{ $product->name }}" style="max-height: 300px; object-fit: contain;">
                            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background: url('{{ $productImage }}') center/cover no-preview;"></div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card-body p-4 p-md-5 h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-3 py-2 fw-bold smaller">{{ $categoryLabel }}</span>
                                    @if($product->o_order > 0)
                                        @if($product->sale && $product->sale->is_active)
                                            <span class="badge bg-danger fw-black rounded-pill px-3 py-2 shadow-sm">
                                                <span class="text-decoration-line-through opacity-75 me-1">{{ number_format($product->o_order) }}</span>
                                                {{ number_format($product->sale->sale_price) }} PTS
                                            </span>
                                        @else
                                            <span class="badge bg-primary fw-black rounded-pill px-3 py-2 shadow-sm">{{ number_format($product->o_order) }} PTS</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success fw-black rounded-pill px-3 py-2 shadow-sm">{{ __('messages.free') }}</span>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;"><i class="fa fa-ellipsis-v"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        <li><button class="dropdown-item py-2 small fw-bold" onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('{{ __('messages.link_copied') }}')"><i class="fa fa-copy me-2 text-muted"></i> {{ __('messages.copy_link') }}</button></li>
                                        @if($canManageProduct)
                                            <li><a class="dropdown-item py-2 small fw-bold" href="{{ route('store.update', $product->name) }}"><i class="fa fa-edit me-2 text-muted"></i> {{ __('messages.edit_product') }}</a></li>
                                            <li><a class="dropdown-item py-2 small fw-bold" href="{{ route('store.downloads', $product->name) }}"><i class="fa fa-users me-2 text-muted"></i> {{ __('messages.downloads') ?? 'Downloads' }}</a></li>
                                            <li><a class="dropdown-item py-2 small fw-bold" href="{{ route('store.updates', $product->name) }}"><i class="fa fa-history me-2 text-muted"></i> {{ __('messages.manage_updates') ?? 'Manage Updates' }}</a></li>
                                            <li><button class="dropdown-item py-2 small fw-bold text-danger" onclick="deletePost({{ $product->id }}, 7867, '.row')"><i class="fa fa-trash me-2"></i> {{ __('messages.delete') }}</button></li>
                                        @elseif(auth()->check())
                                            <li><button class="dropdown-item py-2 small fw-bold" onclick="reportPost({{ $product->id }}, 7867)"><i class="fa fa-flag me-2 text-muted"></i> {{ __('messages.report_product') ?? 'Report Product' }}</button></li>
                                            @if($owner)
                                                <li><button class="dropdown-item py-2 small fw-bold" onclick="reportUser({{ $owner->id }})"><i class="fa fa-flag me-2 text-muted"></i> {{ __('messages.report_publisher') ?? 'Report Publisher' }}</button></li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            
                            <h1 class="fw-black mb-4 text-dark h2">{{ $product->name }}</h1>
                            
                            <div class="row g-2 mb-4">
                                <div class="col-4">
                                    <div class="bg-light bg-opacity-50 p-3 rounded-4 border text-center shadow-sm">
                                        <small class="text-muted d-block smaller fw-black text-uppercase letter-spacing-1 mb-1">{{ __('messages.version') }}</small>
                                        <span class="fw-black text-primary">{{ $latestVersionLabel }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light bg-opacity-50 p-3 rounded-4 border text-center shadow-sm">
                                        <small class="text-muted d-block smaller fw-black text-uppercase letter-spacing-1 mb-1">{{ __('messages.download') }}</small>
                                        <span class="fw-black text-dark">{{ number_format($downloadCount) }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light bg-opacity-50 p-3 rounded-4 border text-center shadow-sm">
                                        <small class="text-muted d-block smaller fw-black text-uppercase letter-spacing-1 mb-1">{{ __('messages.comments') }}</small>
                                        <span class="fw-black text-dark">{{ number_format($commentCount) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto d-grid gap-3 pt-3">
                                @if($downloadHash)
                                    @php
                                        $hasLicense = isset($license) && $license;
                                        $isOwner = auth()->check() && auth()->id() == $product->o_parent;
                                        $isFree = $product->o_order == 0;
                                        $canDownloadDirectly = $hasLicense || $isOwner || $isFree;
                                        $downloadUrl = route('store.download.hash', $downloadHash);
                                    @endphp
                                    <div class="d-flex gap-2">
                                        @if(auth()->check())
                                            @if($canDownloadDirectly)
                                                <a href="{{ $downloadUrl }}" class="btn btn-primary flex-grow-1 fw-black fs-5 py-3 rounded-pill shadow shadow-sm transition-all hover-translate-y">
                                                    <i class="fa fa-download me-2"></i> {{ __('messages.download') }}
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-primary flex-grow-1 fw-black fs-5 py-3 rounded-pill shadow shadow-sm transition-all hover-translate-y" onclick="document.getElementById('inline-purchase-panel').classList.toggle('d-none');">
                                                    <i class="fa fa-shopping-cart me-2"></i> {{ __('messages.purchase') }}
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary flex-grow-1 fw-black fs-5 py-3 rounded-pill shadow shadow-sm transition-all hover-translate-y">
                                                <i class="fa fa-shopping-cart me-2"></i> {{ __('messages.purchase') }}
                                            </a>
                                        @endif
                                        <a href="{{ route('kb.index', $product->name) }}" class="btn btn-outline-dark rounded-pill fw-black shadow-sm px-4 d-flex align-items-center justify-content-center transition-all hover-bg-dark hover-text-white">
                                            <i class="fa fa-book me-2"></i> {{ __('messages.wiki') }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if(auth()->check() && !($license || $product->o_order == 0 || auth()->id() == $product->o_parent))
                                <div id="inline-purchase-panel" class="d-none mt-4 bg-dark bg-opacity-5 border border-secondary border-opacity-25 rounded-4 p-4 text-start">
                                    <h4 class="h5 fw-black text-dark mb-3">{{ __('messages.confirm_purchase') ?? 'Confirm Purchase' }}</h4>
                                    <div class="price-breakdown-box bg-white border rounded-4 p-3 mb-3 shadow-sm">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted small fw-bold">{{ __('messages.price') ?? 'Price' }}</span>
                                            <strong class="text-dark" id="base-price-display">{{ $product->current_price }} PTS</strong>
                                        </div>
                                        <div id="discount-row" class="justify-content-between mb-2 text-success fw-bold" style="display: none !important;">
                                            <span>{{ __('messages.discount') ?? 'Discount' }}</span>
                                            <strong id="discount-amount-display">-0 PTS</strong>
                                        </div>
                                        <hr class="my-2 opacity-50">
                                        <div class="d-flex justify-content-between font-size-16 font-weight-700">
                                            <span class="fw-bold text-dark">{{ __('messages.total') ?? 'Total' }}</span>
                                            <strong class="text-primary h5 fw-black mb-0" id="final-price-display">{{ $product->current_price }} PTS</strong>
                                        </div>
                                    </div>
                                    <div class="promo-code-section mb-3">
                                        <label class="form-label smaller fw-black text-muted text-uppercase mb-2">{{ __('messages.discount_code') ?? 'Promo Code' }}</label>
                                        <div class="d-flex gap-2">
                                            <input type="text" id="coupon-code-input" class="form-control py-2" placeholder="{{ __('messages.enter_promo_code') ?? 'Enter code...' }}">
                                            <button type="button" id="apply-coupon-btn" class="btn btn-primary px-3 py-2 fw-bold text-nowrap rounded-3">{{ __('messages.apply') ?? 'Apply' }}</button>
                                        </div>
                                        <div id="coupon-feedback" class="mt-2 small fw-bold" style="display: none;"></div>
                                    </div>
                                    <div id="purchase-error" class="alert alert-danger p-3 mb-3 small rounded-3" style="display: none;"></div>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-sm btn-light border px-3 py-2 fw-bold rounded-3" onclick="document.getElementById('inline-purchase-panel').classList.add('d-none')">{{ __('messages.cancel') ?? 'Cancel' }}</button>
                                        <button type="button" id="confirm-purchase-btn" class="btn btn-sm btn-primary px-4 py-2 fw-black rounded-3 shadow-sm">{{ __('messages.confirm_purchase') ?? 'Confirm Purchase' }}</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Content -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-0 overflow-x-auto">
                    <ul class="nav nav-tabs nav-pills nav-fill gap-2 p-2 border-0" id="productTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold py-3 rounded-4" data-bs-toggle="tab" data-bs-target="#desc-tab">
                                <i class="fa fa-info-circle me-2"></i> {{ __('messages.details') }}
                            </button>
                        </li>
                        @if($topic)
                            <li class="nav-item">
                                <button class="nav-link fw-bold py-3 rounded-4" data-bs-toggle="tab" data-bs-target="#topic-tab">
                                    <i class="fa fa-comments me-2"></i> {{ __('messages.forum_topic') }}
                                </button>
                            </li>
                        @endif
                        <li class="nav-item">
                            <button class="nav-link fw-bold py-3 rounded-4" data-bs-toggle="tab" data-bs-target="#comments-tab" onclick="loadComments({{ $product->id }}, 'store')">
                                <i class="fa fa-comment me-2"></i> {{ __('messages.comments') }} <span class="badge bg-light text-muted border ms-1">{{ $commentCount }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold py-3 rounded-4" data-bs-toggle="tab" data-bs-target="#versions-tab">
                                <i class="fa fa-history me-2"></i> {{ __('messages.versions') }}
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="tab-content">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="desc-tab">
                            @if($canManageProduct)
                                <div class="mb-4 d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold" id="store-details-edit-btn"><i class="fa fa-edit me-2"></i> {{ __('messages.edit') }}</button>
                                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold d-none" id="store-details-save-btn"><i class="fa fa-save me-2"></i> {{ __('messages.save') }}</button>
                                    <button class="btn btn-sm btn-light border rounded-pill px-4 fw-bold d-none" id="store-details-cancel-btn">{{ __('messages.cancel') }}</button>
                                </div>
                            @endif
                            <div id="store-details-display" class="markdown-content lh-lg fs-5 text-dark">{!! $product->o_valuer !!}</div>
                            @if($canManageProduct)
                                <div id="store-details-editor" class="d-none">
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 open-stackedit-details">
                                            <i class="fa fa-pencil-square me-2"></i> {{ \Illuminate\Support\Facades\Lang::has('messages.edit_with_stackedit') ? __('messages.edit_with_stackedit') : 'Edit with StackEdit' }}
                                        </button>
                                    </div>
                                    <textarea id="store-details-textarea" class="form-control bg-light rounded-4 mb-3 border p-3" rows="15">{{ $product->o_valuer }}</textarea>
                                </div>
                            @endif
                        </div>

                        <!-- Topic Tab -->
                        @if($topic)
                            <div class="tab-pane fade" id="topic-tab">
                                @if($canManageProduct)
                                    <div class="mb-4 d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-bold" id="store-topic-edit-btn"><i class="fa fa-edit me-2"></i> {{ __('messages.edit') }}</button>
                                        <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold d-none" id="store-topic-save-btn"><i class="fa fa-save me-2"></i> {{ __('messages.save') }}</button>
                                        <button class="btn btn-sm btn-light border rounded-pill px-4 fw-bold d-none" id="store-topic-cancel-btn">{{ __('messages.cancel') }}</button>
                                    </div>
                                @endif
                                <div id="store-topic-display" class="markdown-content lh-lg fs-5 text-dark">{!! $topic->txt !!}</div>
                                @if($canManageProduct)
                                    <div id="store-topic-editor" class="d-none">
                                        <div class="mb-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 open-stackedit-topic">
                                                <i class="fa fa-pencil-square me-2"></i> {{ \Illuminate\Support\Facades\Lang::has('messages.edit_with_stackedit') ? __('messages.edit_with_stackedit') : 'Edit with StackEdit' }}
                                            </button>
                                        </div>
                                        <textarea id="store-topic-textarea" class="form-control bg-light rounded-4 mb-3 border p-3" rows="15">{{ $topic->txt }}</textarea>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Comments Tab -->
                        <div class="tab-pane fade" id="comments-tab">
                            <div class="post-comment-list post-comment-list-{{ $product->id }}">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Versions Tab -->
                        <div class="tab-pane fade" id="versions-tab">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border-0 mb-0">
                                    <thead class="bg-light bg-opacity-50 border-bottom">
                                        <tr>
                                            <th class="border-0 px-4 py-3 fw-black text-muted smaller text-uppercase letter-spacing-1">{{ __('messages.version') }}</th>
                                            <th class="border-0 py-3 fw-black text-muted smaller text-uppercase letter-spacing-1">{{ __('messages.date') }}</th>
                                            <th class="border-0 py-3 text-end px-4 fw-black text-muted smaller text-uppercase letter-spacing-1">{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($files as $file)
                                            @php $fileHash = hash('crc32', $file->o_mode . $file->id); @endphp
                                            <tr class="transition-all">
                                                <td class="px-4 py-4 fw-black fs-5">{{ $file->name }}</td>
                                                <td class="py-4 small text-muted fw-bold">{{ $file->created_at ? $file->created_at->diffForHumans() : '-' }}</td>
                                                <td class="text-end px-4 py-4">
                                                    <a href="{{ route('store.download.hash', $fileHash) }}" class="btn btn-light rounded-pill px-4 fw-black border transition-all hover-bg-primary hover-text-white shadow-sm">
                                                        <i class="fa fa-download me-2"></i> {{ __('messages.download') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Publisher Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden text-center">
                <div class="bg-primary bg-opacity-10 py-3 border-bottom">
                    <h6 class="fw-black mb-0 text-uppercase smaller text-primary letter-spacing-1">{{ __('messages.publisher') }}</h6>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if($owner)
                        <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="d-block mb-4 position-relative">
                            <img src="{{ $owner->avatarUrl() }}" alt="" class="rounded-circle border border-4 border-white shadow transition-all hover-scale" width="120" height="120" style="object-fit: cover;">
                            @if($owner->isOnline())
                                <span class="position-absolute bottom-0 end-0 bg-success border border-4 border-white rounded-circle" style="width: 24px; height: 24px; margin-right: 25%; margin-bottom: 5%;"></span>
                            @endif
                        </a>
                        <h5 class="fw-black mb-1 h4">
                            <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="text-dark text-decoration-none hover-primary">{{ $owner->username }}</a>
                            @if($owner->hasVerifiedBadge()) <i class="fa fa-check-circle text-primary small ms-1" title="Verified"></i> @endif
                        </h5>
                        <p class="text-muted smaller fw-bold mb-4 opacity-75">@ {{ $owner->username }}</p>
                        <p class="text-muted small mb-5 px-3 lh-base">{{ Str::limit($owner->bio ?: __('messages.no_bio'), 120) }}</p>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.short', $owner->publicRouteIdentifier()) }}" class="btn btn-primary rounded-pill py-2 fw-black shadow-sm transition-all hover-translate-y">{{ __('messages.view_profile') }}</a>
                            <button class="btn btn-light rounded-pill py-2 fw-bold small text-muted border transition-all hover-bg-light" onclick="reportUser({{ $owner->id }})"><i class="fa fa-flag me-2"></i> {{ __('messages.report') }}</button>
                        </div>
                    @else
                        <div class="bg-light p-4 rounded-circle mb-3 d-inline-block">
                            <i class="fa fa-user-slash fa-4x text-muted opacity-25"></i>
                        </div>
                        <h6 class="fw-bold text-muted">{{ __('messages.unknown_publisher') }}</h6>
                    @endif
                </div>
            </div>

            @if(isset($license) && $license)
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden border-2 border-primary" style="background: rgba(97, 93, 250, 0.01);">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold mb-0 text-uppercase smaller text-primary letter-spacing-1">
                            <i class="fa fa-key me-2"></i>{{ __('messages.license_key_label') ?? 'License Key' }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between bg-white border rounded-3 p-3 font-monospace small fw-bold text-dark mb-3">
                            <span id="license-key-val">{{ $license->license_key }}</span>
                            <button type="button" class="btn btn-link p-0 text-muted" onclick="navigator.clipboard.writeText('{{ $license->license_key }}'); alert('{{ __('messages.license_key_copied') ?? 'License key copied!' }}');" title="Copy">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                        <p class="smaller text-muted mb-0 lh-base">
                            {{ __('messages.license_key_hint') ?? 'Use this key to activate the product on your site.' }}
                        </p>
                    </div>
                </div>
            @endif

            <x-widget-column side="store_sidebar" />
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .letter-spacing-1 { letter-spacing: 1px; }
    .transition-all { transition: all 0.3s ease; }
    .hover-translate-y:hover { transform: translateY(-5px); }
    .hover-scale:hover { transform: scale(1.05); }
    .hover-primary:hover { color: #615dfa !important; }
    .hover-bg-dark:hover { background-color: #212529 !important; }
    .hover-bg-primary:hover { background-color: #615dfa !important; }
    .hover-text-white:hover { color: #fff !important; }
    .markdown-content img { max-width: 100%; border-radius: 12px; margin: 1.5rem 0; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.1); }
    .nav-pills .nav-link { color: #6c757d; transition: all 0.2s ease; }
    .nav-pills .nav-link.active { background-color: rgba(97, 93, 250, 0.1); color: #615dfa; }
    .table-hover tbody tr:hover { background-color: rgba(97, 93, 250, 0.03); }
    .breadcrumb-item + .breadcrumb-item::before { color: #dee2e6; }
</style>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
<script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Markdown rendering
        function renderMarkdown() {
            document.querySelectorAll('.markdown-content').forEach(el => {
                if (!el.getAttribute('data-rendered')) {
                    let text = (el.textContent || el.innerText || el.innerHTML || '');
                    text = text.replace(/^\s+/, '').trimEnd();
                    el.innerHTML = DOMPurify.sanitize(marked.parse(text));
                    el.setAttribute('data-rendered', 'true');
                    if (window.enhanceSuperdesignKbContent) {
                        window.enhanceSuperdesignKbContent(el);
                    }
                }
            });
        }
        renderMarkdown();

        if (window.initKbSnippetsToolbar) {
            window.initKbSnippetsToolbar('store-details-textarea');
            window.initKbSnippetsToolbar('store-topic-textarea');
        }

        // Points check
        document.querySelectorAll('.not-enough-points').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                alert("{{ __('messages.insufficient_points') }}");
            });
        });

        // Tabs logic backup
        const triggerTabList = [].slice.call(document.querySelectorAll('#productTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            const tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })

        // Inline editing
        function setupInlineEdit(idPrefix, route) {
            const editBtn = document.getElementById(idPrefix + '-edit-btn');
            const saveBtn = document.getElementById(idPrefix + '-save-btn');
            const cancelBtn = document.getElementById(idPrefix + '-cancel-btn');
            const display = document.getElementById(idPrefix + '-display');
            const editor = document.getElementById(idPrefix + '-editor');
            const textarea = document.getElementById(idPrefix + '-textarea');

            if (!editBtn) return;

            // StackEdit Integration
            const typeName = idPrefix.split('-')[1]; // "details" or "topic"
            const stackeditBtn = editor.querySelector('.open-stackedit-' + typeName);
            if (stackeditBtn) {
                stackeditBtn.addEventListener('click', function() {
                    if (typeof Stackedit === 'undefined') {
                        alert('StackEdit is loading, please try again.');
                        return;
                    }
                    const stackedit = new Stackedit();
                    stackedit.openFile({
                        name: "{{ $product->name }}",
                        content: { text: textarea.value }
                    });
                    const adjustIframe = () => {
                        const iframe = document.querySelector('iframe[src*="stackedit.io"]');
                        if (iframe) {
                            iframe.style.top = '0';
                            iframe.style.height = '100%';
                            iframe.style.zIndex = '9999';
                        } else {
                            setTimeout(adjustIframe, 50);
                        }
                    };
                    adjustIframe();
                    stackedit.on('fileChange', (file) => {
                        textarea.value = file.content.text;
                    });
                });
            }

            editBtn.onclick = () => {
                display.classList.add('d-none');
                editor.classList.remove('d-none');
                editBtn.classList.add('d-none');
                saveBtn.classList.remove('d-none');
                cancelBtn.classList.remove('d-none');
                textarea.focus();
            };

            cancelBtn.onclick = () => {
                display.classList.remove('d-none');
                editor.classList.add('d-none');
                editBtn.classList.remove('d-none');
                saveBtn.classList.add('d-none');
                cancelBtn.classList.add('d-none');
            };

            saveBtn.onclick = () => {
                const originalText = saveBtn.innerHTML;
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __('messages.saving') }}';
                
                fetch(route, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ txt: textarea.value })
                }).then(r => r.json()).then(data => {
                    if(data.success) {
                        display.innerHTML = textarea.value;
                        display.removeAttribute('data-rendered');
                        renderMarkdown();
                        cancelBtn.click();
                    } else {
                        alert(data.message || 'Error saving changes');
                    }
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                }).catch(err => {
                    console.error(err);
                    alert('An error occurred while saving.');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                });
            };
        }

        setupInlineEdit('store-details', "{{ route('store.update.details', $product->name) }}");
        setupInlineEdit('store-topic', "{{ route('store.update.topic', $product->name) }}");

        // Coupon code and purchase AJAX scripts
        const applyCouponBtn = document.getElementById('apply-coupon-btn');
        const couponCodeInput = document.getElementById('coupon-code-input');
        const couponFeedback = document.getElementById('coupon-feedback');
        const discountRow = document.getElementById('discount-row');
        const discountAmountDisplay = document.getElementById('discount-amount-display');
        const finalPriceDisplay = document.getElementById('final-price-display');
        const confirmPurchaseBtn = document.getElementById('confirm-purchase-btn');
        const purchaseError = document.getElementById('purchase-error');

        let currentCoupon = null;
        const csrfToken = typeof getCsrfToken === 'function' ? getCsrfToken() : (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}');

        if (applyCouponBtn) {
            applyCouponBtn.addEventListener('click', function() {
                const code = couponCodeInput.value.trim();
                if (!code) {
                    couponFeedback.textContent = "{{ __('messages.enter_coupon_code') ?? 'Please enter a coupon code.' }}";
                    couponFeedback.style.color = '#dc3545';
                    couponFeedback.style.display = 'block';
                    return;
                }

                applyCouponBtn.disabled = true;
                couponFeedback.textContent = "{{ __('messages.validating') ?? 'Validating...' }}";
                couponFeedback.style.color = '#6c757d';
                couponFeedback.style.display = 'block';

                fetch("{{ route('store.discounts.validate') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        code: code,
                        product_id: "{{ $product->id }}"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    applyCouponBtn.disabled = false;
                    if (data.success) {
                        currentCoupon = code;
                        couponFeedback.textContent = "{{ __('messages.coupon_applied') ?? 'Coupon applied successfully!' }} (" + data.discount_text + ")";
                        couponFeedback.style.color = '#198754';
                        
                        discountAmountDisplay.textContent = "-" + data.discount_amount + " PTS";
                        discountRow.style.setProperty('display', 'flex', 'important');
                        finalPriceDisplay.textContent = data.final_price + " PTS";
                    } else {
                        currentCoupon = null;
                        couponFeedback.textContent = data.message;
                        couponFeedback.style.color = '#dc3545';
                        
                        discountRow.style.setProperty('display', 'none', 'important');
                        finalPriceDisplay.textContent = "{{ $product->current_price }} PTS";
                    }
                })
                .catch(err => {
                    applyCouponBtn.disabled = false;
                    currentCoupon = null;
                    couponFeedback.textContent = "{{ __('messages.network_error') ?? 'An error occurred. Please try again.' }}";
                    couponFeedback.style.color = '#dc3545';
                    
                    discountRow.style.setProperty('display', 'none', 'important');
                    finalPriceDisplay.textContent = "{{ $product->current_price }} PTS";
                });
            });
        }

        if (confirmPurchaseBtn) {
            confirmPurchaseBtn.addEventListener('click', function() {
                confirmPurchaseBtn.disabled = true;
                confirmPurchaseBtn.textContent = "{{ __('messages.processing') ?? 'Processing...' }}";
                purchaseError.style.display = 'none';

                fetch("{{ route('store.purchase', $product->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        code: currentCoupon
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.download_url;
                    } else {
                        confirmPurchaseBtn.disabled = false;
                        confirmPurchaseBtn.textContent = "{{ __('messages.confirm_purchase') ?? 'Confirm Purchase' }}";
                        purchaseError.textContent = data.message;
                        purchaseError.style.display = 'block';
                    }
                })
                .catch(err => {
                    confirmPurchaseBtn.disabled = false;
                    confirmPurchaseBtn.textContent = "{{ __('messages.confirm_purchase') ?? 'Confirm Purchase' }}";
                    purchaseError.textContent = "{{ __('messages.network_error') ?? 'An error occurred. Please try again.' }}";
                    purchaseError.style.display = 'block';
                });
            });
        }
    });
</script>
@endsection
