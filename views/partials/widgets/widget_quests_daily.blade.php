@php
    $activeQuests = [];
    if (auth()->check()) {
        $activeQuests = \App\Models\Quest::where('is_active', true)
            ->where('period', 'daily')
            ->orderBy('sort_order', 'asc')
            ->get();
            
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
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar {{ $isCompleted ? 'bg-success' : 'bg-primary' }}" 
                                 role="progressbar" 
                                 style="width: {{ $percent }}%;" 
                                 aria-valuenow="{{ $current }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="{{ $target }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-muted small my-3">{{ __('messages.no_active_quests') ?? 'No active quests today.' }}</p>
                @endforelse
            </div>
            @if(count($activeQuests) > 0)
                <div class="d-flex gap-2">
                    <a class="btn btn-primary btn-sm rounded-pill flex-grow-1 fw-bold" href="/quests">
                        {{ __('messages.all_quests') ?? 'All Quests' }}
                    </a>
                    <a class="btn btn-outline-secondary btn-sm rounded-pill flex-grow-1 fw-bold" href="{{ route('profile.show', ['username' => auth()->user()->username, 'tab' => 'history']) }}">
                        {{ __('messages.history') ?? 'History' }}
                    </a>
                </div>
            @endif
        @else
            <p class="text-center text-muted small my-3">{{ __('messages.login_to_start_quests') ?? 'Login to start earning points from daily quests!' }}</p>
            <a class="btn btn-primary btn-sm rounded-pill w-100 fw-bold" href="{{ route('login') }}">{{ __('messages.login') }}</a>
        @endauth
    </div>
</div>
