<div class="card border-0 shadow-sm rounded-4 mb-4">
    @if($widget->name)
        <div class="card-header bg-white py-3 border-bottom-0">
            <h6 class="fw-bold mb-0 text-dark">{{ $widget->name }}</h6>
        </div>
    @endif
    <div class="card-body pt-0 text-secondary small lh-lg">
        {!! $widget->o_valuer !!}
    </div>
</div>
