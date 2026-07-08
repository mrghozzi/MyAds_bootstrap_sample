@php
    $pageLocale = str_replace('_', '-', app()->getLocale());
    $pageDirection = locale_direction();
    $yieldedTitle = trim($__env->yieldContent('title'));
    $resolvedTitle = $yieldedTitle !== '' ? $yieldedTitle : trim((string) ($seo->title ?? ''));
    $resolvedTitle = $resolvedTitle !== '' ? $resolvedTitle : ($site_settings->titer ?? 'MyAds');
@endphp
<!DOCTYPE html>
<html lang="{{ $pageLocale }}" dir="{{ $pageDirection }}" data-bs-theme="light" data-theme="{{ request()->cookie('modedark', 'css') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resolvedTitle }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA & Web App Meta Tags -->
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#212529" media="(prefers-color-scheme: dark)">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $site_settings->titer ?? 'MyAds' }}">
    <link rel="manifest" href="{{ url('/manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('upload/fav.png') }}">

    <!-- Bootstrap 5.3.3 CSS -->
    @if($pageDirection === 'rtl')
        <link href="{{ theme_asset('css/bootstrap5.rtl.min.css') }}" rel="stylesheet">
    @else
        <link href="{{ theme_asset('css/bootstrap5.min.css') }}" rel="stylesheet">
    @endif
    
    <!-- Theme Compatibility CSS -->
    <link href="{{ theme_asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('css/styles.min.css') }}" rel="stylesheet">
    @if($pageDirection === 'rtl')
        <link href="{{ theme_asset('css/rtl.css') }}" rel="stylesheet">
    @endif
    
    <!-- FontAwesome 6 -->
    <link href="{{ theme_asset('css/fontawesome6.min.css') }}" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        (function() {
            const cookieTheme = document.cookie.split('; ').find(row => row.startsWith('modedark='))?.split('=')[1];
            const localTheme = localStorage.getItem('themeMode');
            const currentThemeVal = cookieTheme || localTheme || 'css';
            const currentTheme = currentThemeVal === 'css_d' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', currentTheme);
            document.documentElement.setAttribute('data-theme', currentThemeVal);
        })();
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }
        .main-content {
            min-height: 75vh;
        }
        .sticky-sidebar {
            position: sticky;
            top: 56px;
            height: calc(100vh - 56px);
            overflow-y: auto;
            z-index: 100;
        }
        .nav-sidebar .nav-link {
            color: var(--bs-body-color);
            transition: all 0.2s ease-in-out;
        }
        .nav-sidebar .nav-link:hover {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-primary);
        }
        .nav-sidebar .nav-link.active {
            background-color: var(--bs-primary-bg-subtle) !important;
            color: var(--bs-primary) !important;
        }
        .nav-sidebar .nav-link.active .text-muted-icon {
            color: var(--bs-primary) !important;
        }
        .text-muted-icon {
            color: var(--bs-secondary-color);
        }
        .main-layout-container {
            max-width: 1440px;
            margin: 0 auto;
        }
        .header-dropdown-menu {
            width: 320px;
            max-height: 480px;
            overflow: hidden;
        }
        .header-dropdown-list {
            max-height: 320px;
            overflow-y: auto;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .hover-bg-light:hover {
            background-color: var(--bs-secondary-bg) !important;
        }
    </style>

    @stack('head')
</head>
<body class="{{ $pageDirection }}" data-theme="{{ request()->cookie('modedark', 'css') }}">

    @auth
        @php
            $headerUser = auth()->user();
            $headerMessages = collect();
            $headerMessageUnreadCount = 0;
            $headerNotifications = collect();
            $headerNotificationUnreadCount = 0;
            $formatNotificationCount = static fn (int $count): string => $count > 99 ? '99+' : (string) $count;

            if ($headerUser) {
                $headerAllMessages = \App\Models\Message::where('us_rec', $headerUser->id)
                    ->orWhere('us_env', $headerUser->id)
                    ->orderBy('time', 'desc')
                    ->get();

                $headerPartnerIds = [];
                foreach ($headerAllMessages as $headerMessage) {
                    $headerPartnerId = $headerMessage->us_env == $headerUser->id ? $headerMessage->us_rec : $headerMessage->us_env;
                    if (!in_array($headerPartnerId, $headerPartnerIds, true)) {
                        $headerPartnerIds[] = $headerPartnerId;
                    }
                }

                $headerPartners = \App\Models\User::whereIn('id', $headerPartnerIds)->get()->keyBy('id');
                $headerUnreadPartnerIds = \App\Models\Message::where('us_rec', $headerUser->id)
                    ->where('state', '!=', 0)
                    ->groupBy('us_env')
                    ->pluck('us_env')
                    ->all();
                $headerMessageUnreadCount = count($headerUnreadPartnerIds);
                $headerUnreadMap = array_flip($headerUnreadPartnerIds);

                $headerConversations = [];
                $headerAdded = [];
                foreach ($headerAllMessages as $headerMessage) {
                    $headerPartnerId = $headerMessage->us_env == $headerUser->id ? $headerMessage->us_rec : $headerMessage->us_env;
                    if (isset($headerAdded[$headerPartnerId])) {
                        continue;
                    }
                    $headerPartner = $headerPartners->get($headerPartnerId);
                    if (!$headerPartner) {
                        continue;
                    }
                    $headerAdded[$headerPartnerId] = true;
                    $headerConversations[] = [
                        'user' => $headerPartner,
                        'message' => $headerMessage,
                        'unread' => isset($headerUnreadMap[$headerPartnerId]),
                    ];
                }

                $headerMessages = collect($headerConversations)->take(5);
                $headerNotifications = \App\Models\Notification::where('uid', $headerUser->id)
                    ->orderBy('time', 'desc')
                    ->limit(5)
                    ->get();
                $headerNotificationUnreadCount = \App\Models\Notification::where('uid', $headerUser->id)
                    ->whereIn('state', [0, 3])
                    ->count();
            }
        @endphp
    @endauth

    <nav class="navbar navbar-expand border-bottom shadow-sm sticky-top" style="height: 56px; background-color: var(--bs-body-bg);">
        <div class="container-fluid px-3">
            <div class="d-flex align-items-center me-2">
                <button class="btn btn-outline-secondary d-lg-none me-2 p-1 px-2 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    <i class="fa fa-bars fs-5"></i>
                </button>
                <a class="navbar-brand text-body fw-black d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <i class="fa fa-ad text-primary fs-4"></i>
                    <span class="d-none d-sm-inline">{{ $site_settings->titer ?? 'MyAds' }}</span>
                </a>
            </div>

            <form class="d-none d-md-flex me-auto" action="{{ url('/portal') }}" method="GET" style="max-width: 250px;">
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control bg-body-secondary border-0 rounded-start-pill px-3 text-body" placeholder="{{ __('messages.search_placeholder') }}">
                    <button class="btn btn-body-secondary border-0 rounded-end-pill" type="submit"><i class="fa fa-search text-muted"></i></button>
                </div>
            </form>

            <ul class="navbar-nav align-items-center gap-1 gap-md-2 ms-auto">
                <!-- Lang Switcher -->
                @if(isset($available_languages) && count($available_languages) > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-body-secondary small fw-bold px-2 cursor-pointer" id="langDropdown" role="button" data-bs-toggle="dropdown">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="langDropdown">
                            @foreach($available_languages as $lang)
                                <li><a class="dropdown-item py-2 small" href="?lang={{ $lang->code }}">{{ $lang->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif

                <!-- Dark Mode Theme Switcher -->
                <li class="nav-item">
                    <button class="btn btn-link text-body-secondary px-2 border-0" onclick="toggleTheme()" title="Toggle Theme">
                        <i id="theme-toggle-icon" class="fa fa-{{ request()->cookie('modedark', 'css') == 'css_d' ? 'moon' : 'sun' }} text-warning"></i>
                    </button>
                </li>

                @auth
                    <!-- PTS Points Indicator -->
                    <li class="nav-item me-1">
                        <a href="{{ url('/history') }}" class="nav-link text-success small fw-bold py-1 px-3 bg-success bg-opacity-10 rounded-pill d-flex align-items-center gap-1 shadow-sm border border-success border-opacity-10" title="Points History">
                            <i class="fa fa-coins"></i>
                            <span>{{ auth()->user()->pts }} <span class="d-none d-md-inline">PTS</span></span>
                        </a>
                    </li>

                    <!-- Messages Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link text-body-secondary px-2 position-relative cursor-pointer" id="messagesDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope fs-5"></i>
                            @if($headerMessageUnreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                    {{ $headerMessageUnreadCount }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 header-dropdown-menu p-0" aria-labelledby="messagesDropdown">
                            <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-body-tertiary">
                                <h6 class="fw-bold text-body mb-0"><i class="fa fa-envelope me-2 text-muted"></i>{{ __('messages.msgs') }}</h6>
                                @if($headerMessageUnreadCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $headerMessageUnreadCount }}</span>
                                @endif
                            </div>
                            <div class="list-group list-group-flush header-dropdown-list">
                                @forelse($headerMessages as $headerConversation)
                                    @php
                                        $headerPartner = $headerConversation['user'];
                                        $headerLastMessage = $headerConversation['message'];
                                        $headerConversationRouteKey = \App\Models\Message::encodeConversationRouteKey($headerUser->id, $headerPartner);
                                    @endphp
                                    <a class="list-group-item list-group-item-action py-3 border-0 border-bottom d-flex align-items-center gap-3 {{ $headerConversation['unread'] ? 'bg-body-secondary border-start border-3 border-primary' : '' }}" href="{{ route('messages.show', $headerConversationRouteKey) }}">
                                        <img src="{{ $headerPartner->avatarUrl() }}" class="rounded-circle" width="38" height="38" style="object-fit: cover;">
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                                <h6 class="small fw-bold text-body mb-0 text-truncate">{{ $headerPartner->username }}</h6>
                                                <small class="text-muted smaller" style="font-size: 0.7rem;">{{ \Carbon\Carbon::createFromTimestamp($headerLastMessage->time)->diffForHumans() }}</small>
                                            </div>
                                            <p class="small text-muted mb-0 text-truncate">{{ strip_tags($headerLastMessage->text) }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-muted small">{{ __('messages.no_msg') }}</div>
                                @endforelse
                            </div>
                            <a class="dropdown-item text-center py-2.5 small fw-bold bg-body-tertiary border-top text-primary rounded-bottom-4" href="{{ url('/messages') }}">{{ __('messages.msgs') }}</a>
                        </div>
                    </li>

                    <!-- Notifications Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link text-body-secondary px-2 position-relative cursor-pointer" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fa fa-bell fs-5"></i>
                            @if($headerNotificationUnreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" data-notification-badge style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                    {{ $headerNotificationUnreadCount > 99 ? '99+' : $headerNotificationUnreadCount }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 header-dropdown-menu p-0" aria-labelledby="notificationsDropdown">
                            <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-body-tertiary">
                                <h6 class="fw-bold text-body mb-0"><i class="fa fa-bell me-2 text-muted"></i>{{ __('messages.notifications') }}</h6>
                                <button type="button" class="btn btn-link text-primary p-0 text-decoration-none small mark-all-read-btn" data-mark-all-notifications @if($headerNotificationUnreadCount === 0) hidden @endif style="font-size: 0.8rem;" onclick="markAllNotificationsAsRead(this)">
                                    <i class="fa fa-check-double me-1"></i>{{ __('messages.mark_all_read') ?? 'Mark all read' }}
                                </button>
                            </div>
                            <div class="list-group list-group-flush header-dropdown-list" id="header-notifications-list">
                                @forelse($headerNotifications as $headerNotif)
                                    <a class="list-group-item list-group-item-action py-3 border-0 border-bottom d-flex align-items-center gap-3 {{ $headerNotif->state == 0 || $headerNotif->state == 3 ? 'bg-body-secondary border-start border-3 border-primary' : '' }}" @if($headerNotif->state == 0 || $headerNotif->state == 3) data-notification-unread-item @endif href="{{ route('notifications.show', $headerNotif->id) }}">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
                                            <i class="fa fa-bell"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <p class="small text-body mb-1 fw-bold text-truncate">{{ $headerNotif->name }}</p>
                                            <small class="text-muted smaller" style="font-size: 0.7rem;">{{ \Carbon\Carbon::createFromTimestamp($headerNotif->time)->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-muted small" id="no-notifications-placeholder">{{ __('messages.no_notifications') }}</div>
                                @endforelse
                            </div>
                            <a class="dropdown-item text-center py-2.5 small fw-bold bg-body-tertiary border-top text-primary rounded-bottom-4" href="{{ url('/notification') }}">{{ __('messages.notifications') }}</a>
                        </div>
                    </li>

                    <!-- User Account Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-body px-2 cursor-pointer" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="rounded-circle border" width="28" height="28" style="object-fit: cover;">
                            <span class="d-none d-md-inline small fw-bold text-body">{{ auth()->user()->username }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2" aria-labelledby="userDropdown" style="min-width: 200px;">
                            <li><h6 class="dropdown-header text-muted small text-uppercase fw-black">{{ __('messages.account_summary') }}</h6></li>
                            <li><a class="dropdown-item py-2 rounded-3 small" href="{{ route('profile.show', auth()->user()->username) }}"><i class="fa fa-user me-2 text-muted"></i> {{ __('messages.member_profile') }}</a></li>
                            <li><a class="dropdown-item py-2 rounded-3 small" href="{{ route('profile.edit') }}"><i class="fa fa-user-pen me-2 text-muted"></i> {{ __('messages.edit_profile') }}</a></li>
                            <li><a class="dropdown-item py-2 rounded-3 small" href="{{ route('profile.history') }}"><i class="fa fa-clock-rotate-left me-2 text-muted"></i> {{ __('messages.points_history') }}</a></li>
                            @if(auth()->user()->hasAdminAccess())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 rounded-3 small fw-bold text-primary" href="{{ route('admin.index') }}"><i class="fa fa-shield-halved me-2"></i> Admin Panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 rounded-3 small text-danger"><i class="fa fa-sign-out-alt me-2"></i> {{ __('messages.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white-50 px-2" href="{{ route('login') }}">{{ __('messages.login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" href="{{ url('/register') }}">{{ __('messages.register') }}</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Sidebar & Content Layout Grid -->
    <div class="container-fluid main-layout-container">
        <div class="row g-0">
            <!-- Desktop Sidebar -->
            <aside class="col-lg-3 col-xl-2 bg-body-tertiary border-end d-none d-lg-block sticky-sidebar py-4 px-3">
                @include('theme::partials.header.sidemenu')
            </aside>

            <!-- Main Content Area -->
            <main class="col-lg-9 col-xl-10 px-md-4 py-4 main-content flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="width: 280px;">
        <div class="offcanvas-header border-bottom py-3">
            <h5 class="offcanvas-title fw-black d-flex align-items-center gap-2" id="mobileSidebarLabel">
                <i class="fa fa-ad text-primary fs-4"></i>
                <span>{{ $site_settings->titer ?? 'MyAds' }}</span>
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-3">
            @include('theme::partials.header.sidemenu')
        </div>
    </div>

    <footer class="bg-body-tertiary border-top py-4 mt-auto">
        <div class="container text-center text-muted">
            <p class="mb-0 small">&copy; {{ date('Y') }} {{ $site_settings->titer ?? 'MyAds' }}. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 Bundle JS -->
    <script src="{{ theme_asset('js/bootstrap5.bundle.min.js') }}"></script>
    
    <!-- Platform Essential Scripts -->
    <script src="{{ theme_asset('js/app.js') }}"></script>
    
    @stack('scripts')
    
    <script>
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        // Dark theme toggle script
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
            const nextCookieVal = nextTheme === 'dark' ? 'css_d' : 'css';
            
            document.documentElement.setAttribute('data-bs-theme', nextTheme);
            document.documentElement.setAttribute('data-theme', nextCookieVal);
            if (document.body) {
                document.body.setAttribute('data-theme', nextCookieVal);
            }
            localStorage.setItem('themeMode', nextCookieVal);
            document.cookie = 'modedark=' + nextCookieVal + ';path=/;max-age=31536000';
            
            const btnIcon = document.getElementById('theme-toggle-icon');
            if (btnIcon) {
                btnIcon.className = nextTheme === 'dark' ? 'fa fa-moon text-warning' : 'fa fa-sun text-warning';
            }
        }

        function loadComments(id, type, limit = 5) {
            const selector = '.post-comment-list-' + id;
            const container = document.querySelector(selector);
            if (!container) return;

            if (container.innerHTML === '' || container.querySelector('.spinner-border')) {
                container.innerHTML = '<div class="p-4 text-center"><div class="spinner-border text-primary spinner-border-sm" role="status"></div></div>';
            }

            fetch('{{ route("comment.load") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ id: id, type: type, limit: limit })
            })
            .then(r => r.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(e => console.error('Error loading comments:', e));
        }

        function postComment(id, type) {
            const input = document.getElementById('txt_comment' + id);
            if (!input || !input.value.trim()) return;

            const text = input.value;
            fetch('{{ route("comment.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ id: id, type: type, comment: text })
            })
            .then(r => r.text())
            .then(html => {
                const container = document.querySelector('.post-comment-list-' + id);
                if (container) {
                    container.innerHTML = html;
                    input.value = '';
                }
            })
            .catch(e => alert('Error posting comment: ' + e));
        }

        function deleteComment(trashid, type) {
            if (!confirm('{{ __("messages.confirm_delete") }}')) return;

            fetch('{{ route("comment.delete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ trashid: trashid, type: type })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    const el = document.querySelector('.coment' + trashid);
                    if (el) el.remove();
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }

        function toggleReaction(id, type, reaction) {
            fetch('{{ route("reaction.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ id: id, type: type, reaction: reaction })
            })
            .then(r => r.json())
            .then(data => {
                if (data.html) {
                    const btn = document.getElementById('reaction-btn-' + id) || document.getElementById('reaction-btn-comment-' + id);
                    if (btn) btn.innerHTML = data.html;
                } else if (data.error) {
                    alert(data.error);
                }
            });
        }

        function deletePost(id, type, selector) {
            if (!confirm('{{ __("messages.confirm_delete") }}')) return;

            let url = '';
            let method = 'POST';
            let body = { id: id };

            if (type == 'forum' || type == 2 || type == 4) {
                url = '{{ route("forum.delete") }}';
            } else if (type == 'store' || type == 7867) {
                url = '{{ route("store.delete") }}';
            } else if (type == 'directory' || type == 1) {
                url = '{{ route("directory.delete") }}';
            } else if (type == 'order' || type == 6) {
                url = '{{ route("orders.destroy", ":id") }}'.replace(':id', id);
                method = 'DELETE';
                body = {};
            }

            if (!url) return;

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: method === 'DELETE' ? null : JSON.stringify(body)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const el = document.querySelector(selector);
                    if (el) el.remove();
                    else window.location.reload();
                } else {
                    alert(data.error || 'Error deleting post');
                }
            });
        }

        function sharePost(social, url, title) {
            const shareUrls = {
                facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
                twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`,
                linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
                telegram: `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`
            };
            if (shareUrls[social]) {
                window.open(shareUrls[social], '_blank', 'width=600,height=400');
            }
        }

        function postEdit(id, type, extra) {
             if (type == 100 || type == 4 || type == 10 || type == 11 || type == 12 || type == 13 || type == 14) {
                  let container = document.getElementById('post_form' + id);
                  if (!container) return;
                  
                  if (container.querySelector('textarea')) return;

                  let currentText = container.innerText;
                  
                  let html = `
                    <form onsubmit="event.preventDefault(); savePost(${id}, ${type}, this);">
                        <textarea class="form-control mb-2" name="txt" style="width:100%; height:100px;">${currentText.trim()}</textarea>
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">{{ __('messages.save') }}</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="cancelEdit(${id});">{{ __('messages.cancel') }}</button>
                        </div>
                    </form>
                  `;
                  
                  container.dataset.originalContent = container.innerHTML;
                  container.innerHTML = html;
             } else if (type == 2) {
                  window.location.href = '{{ url("editor") }}/' + id;
             } else if (type == 1) {
                  window.location.href = '{{ url("directory") }}/' + id + '/edit';
             } else if (type == 7867) {
                  if (extra) {
                      window.location.href = '{{ url("store") }}/' + extra + '/update';
                  } else {
                      alert('Please use the edit button on the product page.');
                  }
             }
        }

        function savePost(id, type, form) {
            let txt = form.txt.value;
            fetch('{{ route("forum.update", ":id") }}'.replace(':id', id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ 
                    name: 'post', 
                    txt: txt,
                    cat: 0 
                })
            })
            .then(response => {
                if(response.ok) {
                    window.location.reload();
                } else {
                    response.json().then(data => {
                        alert('Error: ' + (data.message || 'Error saving post'));
                    }).catch(() => {
                        alert('Error saving post');
                    });
                }
            });
        }

        function cancelEdit(id) {
            let container = document.getElementById('post_form' + id);
            if (container && container.dataset.originalContent) {
                container.innerHTML = container.dataset.originalContent;
            }
        }

        function reportPost(id, type) {
            const reason = prompt('{{ __("messages.report_reason") ?? "Reason for reporting:" }}');
            if (!reason) return;

            fetch('{{ route("report.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ id: id, type: type, reason: reason })
            })
            .then(r => r.json())
            .then(data => {
                alert(data.message || 'Report submitted');
            });
        }

        function reportUser(uid) {
            reportPost(uid, 99);
        }

        @auth
        // Notifications AJAX Read Trigger
        (function () {
            const markAllRoute = '{{ route('notifications.mark_all_read') }}';
            const csrfToken = '{{ csrf_token() }}';

            function formatNotificationCount(count) {
                return count > 99 ? '99+' : String(count);
            }

            window.updateNotificationIndicators = function (count) {
                const safeCount = Number.isFinite(count) ? Math.max(0, count) : 0;
                const formattedCount = formatNotificationCount(safeCount);

                document.querySelectorAll('[data-notification-badge]').forEach(function (badge) {
                    badge.textContent = safeCount > 0 ? formattedCount : '';
                    badge.style.display = safeCount > 0 ? 'inline-block' : 'none';
                });

                document.querySelectorAll('[data-notification-unread-item]').forEach(function (item) {
                    item.classList.remove('bg-light', 'bg-opacity-50', 'border-start', 'border-3', 'border-primary');
                    item.removeAttribute('data-notification-unread-item');
                });

                document.querySelectorAll('.mark-all-read-btn').forEach(function (btn) {
                    btn.style.display = 'none';
                });
            };

            window.markAllNotificationsAsRead = function (btn) {
                if (window.__notificationsMarkingAllRead) {
                    return;
                }

                window.__notificationsMarkingAllRead = true;
                if (btn) {
                    btn.style.pointerEvents = 'none';
                    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                }

                fetch(markAllRoute, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data.success) {
                        window.updateNotificationIndicators(0);
                    }
                })
                .catch(function (error) {
                    console.error('Error marking notifications as read:', error);
                })
                .finally(function () {
                    window.__notificationsMarkingAllRead = false;
                    if (btn) {
                        btn.style.pointerEvents = '';
                        btn.style.display = 'none';
                    }
                });
            };
        })();
        @endauth
    </script>
    
    @php
        \App\Helpers\Hooks::do_action('theme_master_head_end');
    @endphp
    <!-- Live Search JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-main');
            const clearBtn = document.getElementById('search-clear-btn');
            const dropdown = document.getElementById('live-search-dropdown');
            const resultsContainer = document.getElementById('live-search-results');
            const seeAllBtn = document.getElementById('live-search-see-all');
            
            if (!searchInput || !dropdown) return;
            
            let debounceTimer;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();
                
                if (query.length > 0) {
                    clearBtn.style.display = 'flex';
                } else {
                    clearBtn.style.display = 'none';
                    dropdown.style.display = 'none';
                    return;
                }
                
                if (query.length < 2) {
                    dropdown.style.display = 'none';
                    return;
                }
                
                seeAllBtn.href = "{{ url('/portal') }}?search=" + encodeURIComponent(query);
                
                debounceTimer = setTimeout(() => {
                    resultsContainer.innerHTML = '<div class="text-center p-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>';
                    dropdown.style.display = 'block';
                    
                    fetch("{{ route('search.live') }}?q=" + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(data => {
                            resultsContainer.innerHTML = '';
                            if (data.results && data.results.length > 0) {
                                data.results.forEach(item => {
                                    const iconHtml = item.type === 'user' ? '<i class="fas fa-user"></i>' : 
                                                     (item.type === 'product' ? '<i class="fas fa-box"></i>' : 
                                                     (item.type === 'forum' ? '<i class="fas fa-comments"></i>' : '<i class="fas fa-newspaper"></i>'));
                                    
                                    const imgHtml = item.img ? 
                                        `<img src="${item.img}" class="rounded-circle border" width="40" height="40" style="object-fit: cover;">` :
                                        `<div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-secondary" style="width:40px; height:40px;">${iconHtml}</div>`;
                                        
                                    const subtitleHtml = item.subtitle ? `<div class="small text-muted text-truncate">${item.subtitle}</div>` : '';
                                    
                                    resultsContainer.innerHTML += `
                                        <a class="dropdown-item d-flex align-items-center gap-3 py-2 border-bottom" href="${item.url}">
                                            ${imgHtml}
                                            <div class="overflow-hidden">
                                                <div class="fw-bold text-truncate text-dark">${item.title}</div>
                                                ${subtitleHtml}
                                            </div>
                                        </a>
                                    `;
                                });
                            } else {
                                resultsContainer.innerHTML = `<div class="p-3 text-center text-muted small">{{ __('messages.no_results_found') ?? 'No results found.' }}</div>`;
                            }
                        })
                        .catch(err => {
                            console.error('Live search error', err);
                            resultsContainer.innerHTML = `<div class="p-3 text-center text-danger small">Error loading results</div>`;
                        });
                }, 300);
            });
            
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                clearBtn.style.display = 'none';
                dropdown.style.display = 'none';
                searchInput.focus();
            });
            
            // Close dropdown on outside click
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
            
            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2) {
                    dropdown.style.display = 'block';
                }
            });
        });
    </script>
    <!-- User Popover JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popover = document.createElement('div');
            popover.id = 'global-user-popover';
            popover.className = 'shadow-lg rounded-4 bg-white border border-light overflow-hidden';
            popover.style.cssText = 'position: absolute; display: none; z-index: 9999; min-width: 320px; max-width: 350px; opacity: 0; transition: opacity 0.2s ease;';
            document.body.appendChild(popover);
            
            let hoverTimer;
            let hideTimer;
            let currentTarget = null;
            let cache = {};
            
            function showPopover(trigger) {
                const username = trigger.getAttribute('data-username');
                if (!username) return;
                
                if (cache[username]) {
                    renderPopover(trigger, cache[username]);
                } else {
                    popover.innerHTML = '<div class="p-4 text-center"><div class="spinner-border text-primary spinner-border-sm" role="status"></div></div>';
                    positionPopover(trigger);
                    popover.style.display = 'block';
                    setTimeout(() => popover.style.opacity = '1', 10);
                    
                    fetch("{{ url('/ajax/user-popover') }}/" + encodeURIComponent(username))
                        .then(response => {
                            if (!response.ok) throw new Error('Not found');
                            return response.text();
                        })
                        .then(html => {
                            cache[username] = html;
                            if (currentTarget === trigger) {
                                renderPopover(trigger, html);
                            }
                        })
                        .catch(err => {
                            console.error('Popover fetch error', err);
                            popover.style.opacity = '0';
                            setTimeout(() => popover.style.display = 'none', 200);
                        });
                }
            }
            
            function renderPopover(trigger, html) {
                popover.innerHTML = html;
                positionPopover(trigger);
                popover.style.display = 'block';
                setTimeout(() => popover.style.opacity = '1', 10);
            }
            
            function positionPopover(trigger) {
                const rect = trigger.getBoundingClientRect();
                let top = rect.bottom + window.scrollY + 10;
                let left = rect.left + window.scrollX;
                
                if (left + 320 > window.innerWidth) {
                    left = window.innerWidth - 340;
                }
                
                popover.style.top = top + 'px';
                popover.style.left = left + 'px';
            }
            
            document.addEventListener('mouseover', function(e) {
                const trigger = e.target.closest('.user-popover-trigger');
                if (trigger) {
                    clearTimeout(hideTimer);
                    if (currentTarget !== trigger) {
                        clearTimeout(hoverTimer);
                        hoverTimer = setTimeout(() => {
                            currentTarget = trigger;
                            showPopover(trigger);
                        }, 400);
                    }
                } else if (popover.contains(e.target)) {
                    clearTimeout(hideTimer);
                } else {
                    clearTimeout(hoverTimer);
                    if (popover.style.opacity === '1') {
                        hideTimer = setTimeout(() => {
                            popover.style.opacity = '0';
                            setTimeout(() => popover.style.display = 'none', 200);
                            currentTarget = null;
                        }, 300);
                    }
                }
            });
            
            document.addEventListener('mouseout', function(e) {
                const trigger = e.target.closest('.user-popover-trigger');
                if (trigger) {
                    clearTimeout(hoverTimer);
                    hideTimer = setTimeout(() => {
                        popover.style.opacity = '0';
                        setTimeout(() => popover.style.display = 'none', 200);
                        currentTarget = null;
                    }, 300);
                }
            });
        });
    </script>
</body>
</html>
