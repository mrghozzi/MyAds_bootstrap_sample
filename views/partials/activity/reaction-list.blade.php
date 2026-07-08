<div class="d-flex align-items-center me-2">
    @if(isset($activity->grouped_reactions) && count($activity->grouped_reactions) > 0)
        <div class="d-flex align-items-center">
            @foreach($activity->grouped_reactions as $type => $users)
                <div class="dropdown d-inline-block position-relative" style="margin-inline-end: -6px; z-index: {{ 10 - $loop->index }};">
                    <img class="rounded-circle border border-white shadow-sm bg-white cursor-pointer" 
                         src="{{ theme_asset('img/reaction/'.$type.'.png') }}" 
                         alt="{{ $type }}" 
                         width="20" 
                         height="20"
                         data-bs-toggle="dropdown" 
                         aria-expanded="false"
                         style="transition: transform 0.2s; cursor: pointer;"
                         onmouseover="this.style.transform='scale(1.2)'"
                         onmouseout="this.style.transform='scale(1)'">
                    <ul class="dropdown-menu shadow border-0 p-3" style="min-width: 150px; font-size: 13px;">
                        <li class="dropdown-header px-0 pb-2 border-bottom mb-2 fw-black text-dark d-flex align-items-center gap-2">
                            <img src="{{ theme_asset('img/reaction/'.$type.'.png') }}" width="16" height="16">
                            {{ ucfirst($type) }}
                        </li>
                        @foreach($users as $user)
                            <li class="py-1 text-muted fw-bold small">{{ $user->username }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
</div>
