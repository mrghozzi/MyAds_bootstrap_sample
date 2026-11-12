@if($history->count() === 0)
    <div class="p-5 text-center bg-light bg-opacity-25 rounded-4 border border-light">
        <div class="rounded-circle bg-white shadow-sm p-4 d-inline-flex mb-3 border border-light">
            <i class="fa-solid fa-coins fa-2x text-muted opacity-50"></i>
        </div>
        <h5 class="fw-black text-dark">{{ ($featureAvailable ?? true) ? __('messages.no_history') : __('messages.upgrade_legacy_mode_notice') }}</h5>
        <p class="text-muted small mb-0">{{ __('messages.pts_history_desc') }}</p>
    </div>
@else
    <div class="table-responsive rounded-4 border border-light overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light bg-opacity-50">
                <tr>
                    <th class="px-3 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1" style="width: 80px;">#</th>
                    <th class="px-3 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.pts_transaction_type') }}</th>
                    <th class="px-3 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1">{{ __('messages.date') }}</th>
                    <th class="px-3 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1 text-end">{{ __('messages.pts_amount') }}</th>
                    <th class="px-3 py-3 border-0 smaller text-uppercase text-muted fw-black letter-spacing-1 text-end">{{ __('messages.pts_running_balance') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $item)
                    @php
                        $descKey = !empty($item->description_key) ? $item->description_key : ($item->type ?? 'points_awarded');
                        $description = __('messages.' . $descKey);
                        $description = ($description !== 'messages.' . $descKey) ? $description : $descKey;
                        $amount = (float) $item->amount;
                        $isPositive = $amount >= 0;

                        $dateDisplay = '--';
                        if (!empty($item->created_at)) {
                            $dateDisplay = is_numeric($item->created_at)
                                ? \Carbon\Carbon::createFromTimestamp((int) $item->created_at)->format('Y-m-d H:i')
                                : optional($item->created_at)->format('Y-m-d H:i');
                        } elseif (!empty($item->created_at_ts)) {
                            $dateDisplay = \Carbon\Carbon::createFromTimestamp((int) $item->created_at_ts)->format('Y-m-d H:i');
                        }
                    @endphp
                    <tr class="transition-all hover-bg-light">
                        <td class="px-3 smaller text-muted fw-black">#{{ $item->id }}</td>
                        <td class="px-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle {{ $isPositive ? 'bg-success' : 'bg-danger' }} bg-opacity-10 p-2 d-flex align-items-center justify-content-center border {{ $isPositive ? 'border-success' : 'border-danger' }} border-opacity-10 shadow-sm" style="width: 38px; height: 38px; flex-shrink: 0;">
                                    <i class="fa-solid {{ $isPositive ? 'fa-arrow-down-left-long text-success' : 'fa-arrow-up-right-long text-danger' }} smaller"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark fs-6 mb-1 text-truncate">{{ $description }}</div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        @if($isPositive)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 rounded-pill smallest fw-bold px-2">
                                                <i class="fa-solid fa-plus me-1"></i> {{ __('messages.pts_positive_badge') }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 rounded-pill smallest fw-bold px-2">
                                                <i class="fa-solid fa-minus me-1"></i> {{ __('messages.pts_negative_badge') }}
                                            </span>
                                        @endif

                                        @if(!empty($item->type) && !in_array($item->type, ['legacy', 'reward']))
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill smallest fw-bold px-2">
                                                {{ strtoupper(str_replace('_', ' ', $item->type)) }}
                                            </span>
                                        @endif

                                        @if(!empty($item->is_legacy))
                                            <span class="badge bg-light text-muted border border-light rounded-pill smallest fw-bold px-2">
                                                <i class="fa-solid fa-clock-rotate-left me-1"></i> {{ __('messages.legacy_points') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 smaller text-muted fw-bold text-nowrap">
                            <i class="fa-regular fa-calendar me-1 opacity-75"></i> {{ $dateDisplay }}
                        </td>
                        <td class="px-3 text-end fw-black text-nowrap {{ $isPositive ? 'text-success' : 'text-danger' }}">
                            {{ $amount > 0 ? '+' : '' }}{{ rtrim(rtrim(number_format($amount, 2), '0'), '.') }}
                            <small class="smallest opacity-75">{{ __('messages.pts_short') ?? 'PTS' }}</small>
                        </td>
                        <td class="px-3 text-end text-nowrap">
                            @if(isset($item->balance_after) && $item->balance_after !== null && $item->balance_after !== '')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 rounded-pill px-3 py-1 fs-7 fw-bold">
                                    <i class="fa-solid fa-wallet me-1 opacity-75"></i>
                                    {{ rtrim(rtrim(number_format((float) $item->balance_after, 2), '0'), '.') }}
                                    <small class="smallest opacity-75">{{ __('messages.pts_short') ?? 'PTS' }}</small>
                                </span>
                            @else
                                <span class="text-muted opacity-50">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(method_exists($history, 'hasPages') && $history->hasPages())
        <div class="p-3 border-top bg-white d-flex justify-content-center" id="pointsPaginationHolder">
            {{ $history->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endif
