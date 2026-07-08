<div class="d-flex flex-column nav-sidebar">
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ url('/portal') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('portal*', 'tag*') ? 'active' : 'hover-bg-light' }}">
                <i class="fa fa-rss fs-5 text-muted-icon"></i>
                <span class="fw-semibold">{{ __('messages.community') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/clips') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('clips*') ? 'active' : 'hover-bg-light' }}">
                <i class="fa fa-video fs-5 text-muted-icon"></i>
                <span class="fw-semibold">{{ __('messages.clips') ?? 'Clips' }}</span>
            </a>
        </li>
        @auth
            <li class="nav-item">
                <a href="{{ url('/home') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('home*') ? 'active' : 'hover-bg-light' }}">
                    <i class="fa fa-gauge fs-5 text-muted-icon"></i>
                    <span class="fw-semibold">{{ __('messages.board') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/quests') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('quests*') ? 'active' : 'hover-bg-light' }}">
                    <i class="fa fa-trophy fs-5 text-muted-icon"></i>
                    <span class="fw-semibold">{{ __('messages.quests') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('badges.all') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('badges*') ? 'active' : 'hover-bg-light' }}">
                    <i class="fa fa-award fs-5 text-muted-icon"></i>
                    <span class="fw-semibold">{{ __('messages.badges') }}</span>
                </a>
            </li>
        @endauth
        <li class="nav-item">
            <a href="{{ url('/forum') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('forum*', 'f*', 't*', 'post*', 'editor*') ? 'active' : 'hover-bg-light' }}">
                <i class="fa fa-comments fs-5 text-muted-icon"></i>
                <span class="fw-semibold">{{ __('messages.forum') }}</span>
            </a>
        </li>
        @if(\App\Support\GroupSettings::isEnabled())
            <li class="nav-item">
                <a href="{{ route('groups.index') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('groups*') ? 'active' : 'hover-bg-light' }}">
                    <i class="fa fa-users fs-5 text-muted-icon"></i>
                    <span class="fw-semibold">{{ __('messages.groups_title') }}</span>
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a href="{{ url('/directory') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('directory*', 'dr*', 'cat*', 'add-site*') ? 'active' : 'hover-bg-light' }}">
                <i class="fa fa-sitemap fs-5 text-muted-icon"></i>
                <span class="fw-semibold">{{ __('messages.directory') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/orders') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('orders*') ? 'active' : 'hover-bg-light' }}">
                <i class="fa fa-briefcase fs-5 text-muted-icon"></i>
                <span class="fw-semibold">{{ __('messages.order_requests') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/store') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('store*', 'kb*', 'download*') ? 'active' : 'hover-bg-light' }}">
                <i class="fa fa-shopping-cart fs-5 text-muted-icon"></i>
                <span class="fw-semibold">{{ __('messages.store') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/news') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('news*') ? 'active' : 'hover-bg-light' }}">
                <i class="fa fa-newspaper fs-5 text-muted-icon"></i>
                <span class="fw-semibold">{{ __('messages.news') }}</span>
            </a>
        </li>
        @auth
            <li class="nav-item">
                <a href="{{ route('ads.index') }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 {{ Request::is('ads*', 'promote*') ? 'active' : 'hover-bg-light' }}">
                    <i class="fa fa-bullhorn fs-5 text-muted-icon"></i>
                    <span class="fw-semibold">{{ __('messages.advertising') }}</span>
                </a>
            </li>
        @endauth

        @if(isset($site_menus) && count($site_menus) > 0)
            <li class="my-3 border-top border-light-dark"></li>
            @foreach($site_menus as $menu)
                <li class="nav-item">
                    <a href="{{ $menu->dir }}" class="nav-link py-2.5 px-3 rounded-3 d-flex align-items-center gap-3 hover-bg-light">
                        <i class="fa fa-link fs-5 text-muted-icon"></i>
                        <span class="fw-semibold">{{ $menu->name }}</span>
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</div>

