@php
    $selectedScopes = $selectedScopes ?? [];
    if (!is_array($selectedScopes)) {
        $selectedScopes = [];
    }

    $developerScopeFallbacks = [
        'user.identity.read' => [
            'label' => 'Identity',
            'description' => 'Read the member account identifier and basic public identity fields.',
        ],
        'user.profile.read' => [
            'label' => 'Profile',
            'description' => 'Read public profile details and core member metadata.',
        ],
        'user.social_links.read' => [
            'label' => 'Social Links',
            'description' => 'Read the public social links configured on a member profile.',
        ],
        'user.follows.read' => [
            'label' => 'Follow Graph',
            'description' => 'Read follower and following relationships for visible members.',
        ],
        'owner.profile.read' => [
            'label' => 'Owner Profile',
            'description' => 'Read the authenticated owner profile through the developer API.',
        ],
        'owner.content.read' => [
            'label' => 'Owner Content',
            'description' => 'Read the authenticated owner content feed and published updates.',
        ],
        'owner.follow.write' => [
            'label' => 'Owner Follow Write',
            'description' => 'Follow or unfollow members on behalf of the authorized owner.',
        ],
        'owner.messages.read' => [
            'label' => 'Owner Messages Read',
            'description' => 'Read private message conversations that belong to the authorized owner.',
        ],
        'owner.messages.write' => [
            'label' => 'Owner Messages Write',
            'description' => 'Send private messages on behalf of the authorized owner.',
        ],
    ];

    $scopeInputPrefix = $scopeInputPrefix ?? 'developer_scope';
@endphp

<div class="row row-cols-1 row-cols-md-2 g-3">
    @foreach($scopes as $scopeId => $scope)
        @php
            $translatedLabel = __($scope['name']);
            $translatedDescription = __($scope['description']);
            $scopeFallback = $developerScopeFallbacks[$scopeId] ?? null;
            $scopeLabel = $translatedLabel === $scope['name']
                ? ($scopeFallback['label'] ?? ucwords(str_replace('.', ' ', $scopeId)))
                : $translatedLabel;
            $scopeDescription = $translatedDescription === $scope['description']
                ? ($scopeFallback['description'] ?? $scopeId)
                : $translatedDescription;
        @endphp

        <div class="col">
            <label class="card border border-2 shadow-sm rounded-4 h-100 cursor-pointer" style="cursor: pointer;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="form-check mt-1">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="requested_scopes[]"
                                value="{{ $scopeId }}"
                                id="{{ $scopeInputPrefix }}_{{ str_replace('.', '_', $scopeId) }}"
                                @checked(in_array($scopeId, $selectedScopes, true))
                            >
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold">{{ $scopeLabel }}</span>
                                @if(!empty($scope['is_sensitive']))
                                    <span class="badge bg-danger rounded-pill px-2 py-1 small">{{ __('messages.sensitive') }}</span>
                                @endif
                            </div>
                            <div class="text-muted small mb-2">{{ $scopeDescription }}</div>
                            <code class="small bg-light px-2 py-1 rounded">{{ $scopeId }}</code>
                        </div>
                    </div>
                </div>
            </label>
        </div>
    @endforeach
</div>
