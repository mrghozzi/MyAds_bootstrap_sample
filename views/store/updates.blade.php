@extends('theme::layouts.master')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="card border-0 shadow-sm mb-4 bg-primary bg-gradient text-white rounded-4 overflow-hidden position-relative border border-light transition-all">
        <div class="card-body p-4 p-md-5 d-flex align-items-center position-relative z-1">
            <div class="bg-white bg-opacity-20 p-3 rounded-4 me-4 border border-white border-opacity-25 shadow-sm">
                <i class="fa fa-code-fork fa-3x"></i>
            </div>
            <div>
                <h1 class="h2 fw-black mb-1 text-white">{{ __('messages.updates') ?? 'Updates' }}</h1>
                <p class="mb-0 text-white text-opacity-75 small fw-bold">{{ $product->name }}</p>
            </div>
        </div>
        <div class="position-absolute top-0 end-0 p-5 opacity-10 d-none d-lg-block">
            <i class="fa fa-history fa-10x"></i>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-black mb-0 text-dark">{{ __('messages.updates') ?? 'Updates' }}</h4>
        <a href="{{ route('store.show', $product->name) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y">
            <i class="fa fa-arrow-left me-1"></i>
            {{ __('messages.back') ?? 'Back' }}
        </a>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="d-grid gap-4">
                @forelse($files as $file)
                    <div class="card border-0 shadow-sm rounded-4 border border-light overflow-hidden transition-all hover-translate-y" id="update-row-{{ $file->id }}">
                        <div class="card-body p-4">
                            <div class="row align-items-center g-4">
                                <div class="col-md-auto">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center shadow-sm border border-primary border-opacity-10" style="width: 64px; height: 64px;">
                                        <i class="fa fa-code-branch fs-3"></i>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="d-flex align-items-center gap-3 flex-wrap mb-2">
                                        <h5 class="fw-black mb-0 text-dark fs-5">{{ $file->name }}</h5>
                                        <span class="text-muted small fw-bold">
                                            <i class="fa fa-clock me-1 opacity-50"></i>{{ $file->created_at ? $file->created_at->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    <p class="mb-0 text-muted small fw-bold line-height-1.6">
                                        {{ Str::limit($file->o_valuer, 150) }}
                                    </p>
                                </div>
                                <div class="col-md-auto text-md-end">
                                    <button type="button" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-black shadow-sm transition-all hover-translate-y btn-delete-update" data-id="{{ $file->id }}" data-url="{{ route('store.updates.destroy', ['name' => $product->name, 'file' => $file->id]) }}">
                                        <i class="fa fa-trash me-2"></i>
                                        {{ __('messages.delete') ?? 'Delete' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center bg-white shadow-sm rounded-4 border border-light">
                        <div class="rounded-circle bg-light p-4 d-inline-flex mb-4">
                            <i class="fa fa-history fa-3x text-muted opacity-25"></i>
                        </div>
                        <h4 class="fw-black text-dark">{{ __('messages.no_results') ?? 'No results found.' }}</h4>
                        <p class="text-muted small mb-0 fw-bold">{{ __('messages.no_updates_yet') ?? 'No version updates have been published for this product yet.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if($files->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $files->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-update').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (!confirm('{{ __('messages.confirm_delete') ?? 'Are you sure you want to delete this update?' }}')) {
                return;
            }
            
            var url = this.getAttribute('data-url');
            var id = this.getAttribute('data-id');
            var row = document.getElementById('update-row-' + id);
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                } else if (data.error) {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting.');
            });
        });
    });
});
</script>
@endsection
