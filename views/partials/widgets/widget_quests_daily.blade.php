@php
    $config = [];
    if (!empty($widget?->o_valuer)) {
        if (is_array($widget->o_valuer)) {
            $config = $widget->o_valuer;
        } else {
            $decoded = json_decode((string) $widget->o_valuer, true);
            $config = is_array($decoded) ? $decoded : [];
        }
    }

    $period = $config['period'] ?? 'daily';
    $showProgress = isset($config['show_progress']) ? (bool) $config['show_progress'] : true;
    $showButtons = isset($config['show_buttons']) ? (bool) $config['show_buttons'] : true;

    $activeQuests = [];
    if (auth()->check()) {
        $query = \App\Models\Quest::where('is_active', true)->orderBy('sort_order', 'asc');
        if ($period === 'daily') {
            $query->where('period', 'daily');
        }
        $activeQuests = $query->get();
            
        $today = now()->format('Y-m-d');
        $progressMap = \App\Models\QuestProgress::where('user_id', auth()->id())
            ->where('period_key', $today)
            ->get()
            ->keyBy('quest_id');
    }
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom-0">
        <h6 class="fw-bold mb-0 text-dark">{{ $widget->name ?? __('messages.daily_quests') }}</h6>
    </div>
    <div class="card-body pt-0">
        @auth
            <div class="d-flex flex-column gap-3 mb-3">
                @forelse($activeQuests as $quest)
                    @php
                        $progress = $progressMap->get($quest->id);
                        $current = $progress ? $progress->progress : 0;
                        $target = (int) $quest->target_count;
                        $percent = min(100, ($current / $target) * 100);
                        $isCompleted = $progress && $progress->completed_at;
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small text-dark d-flex align-items-center gap-1">
                                <i class="{{ $quest->icon ?? 'fa fa-bolt' }} text-primary"></i>
                                {{ __('messages.' . $quest->name_key) }}
                            </span>
                            <span class="text-muted smaller fw-bold">{{ $current }}/{{ $target }}</span>
                        </div>
                        @if($showProgress)
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $isCompleted ? 'bg-success' : 'bg-primary' }}" 
                                     role="progressbar" 
                                     style="width: {{ $percent }}%;" 
                                     aria-valuenow="{{ $current }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $target }}"></div>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-muted small my-2">{{ __('messages.no_active_quests') }}</p>
                @endforelse
            </div>
            @if($showButtons)
                <a class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold" href="{{ url('/quests') }}">
                    {{ __('messages.view_all_quests') ?? 'View All Quests' }}
                </a>
            @endif
        @else
            <div class="text-center py-3">
                <i class="fa fa-trophy fs-2 text-warning mb-2"></i>
                <p class="small text-muted mb-3">{{ __('messages.login_to_view_quests') ?? 'Login to complete quests and earn daily PTS points!' }}</p>
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">{{ __('messages.login') }}</a>
            </div>
        @endauth
    </div>
</div>
