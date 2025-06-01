<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
             <!-- <img alt="image" src="assets/img/logo.png" class="header-logo" />  -->
            <a href="">  <span
                    class="logo-name">Pinyotta</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>

            <li class="dropdown {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i data-feather="monitor"></i><span>Dashboard</span>
                </a>
            </li>

            @foreach ($modules as $module)
                @if ($module->name !== 'Dashboard' && $module->menu_item == 1)

                @php
                    $isActive = false;
                    foreach ($module->pages as $page) {
                        if ($page->menu_item == "1" && request()->is(ltrim($page->page_route, '/'))) {
                            $isActive = true;
                            break;
                        }
                    }
                @endphp

                <li class="dropdown {{ $isActive ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i class="{{ $module->icons }}" aria-hidden="true"></i><span>{{ $module->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach ($module->pages as $page)
                            @if ($page->menu_item == "1")
                            <li>
                                <a class="nav-link {{ request()->is(ltrim($page->page_route, '/')) ? 'active' : '' }}"
                                   href="{{ url($page->page_route) }}">
                                    {{ $page->page_name }}
                                </a>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

                @endif
            @endforeach

            <li class="dropdown">
                <a href="{{ url('/admin/logout') }}" class="nav-link"><i class="fas fa-sign-out-alt" aria-hidden="true"></i><span>Logout</span></a>
            </li>


        </ul>
    </aside>
</div>
</div>
