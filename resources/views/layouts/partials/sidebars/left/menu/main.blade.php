<div class="menu">
    @yield('sidebar-menu-before')

    @section('sidebar-main-menu')
        <ul class="list">
            @yield('sidebar-main-menu-before')

            @if ($admin_env)
                <?php $menuItems = $domain->adminMenu->data ?? null; ?>
            @else
                <?php $menuItems = $domain->classicMenu->data ?? null; ?>
            @endif

            @if (!empty($menuItems))
                @include('uccello::layouts.partials.sidebars.left.menu.custom')
            @else
                @include('uccello::layouts.partials.sidebars.left.menu.default')
            @endif

            @yield('sidebar-main-menu-after')

            {{-- Display admin menu for if the user can admin at least one admin module --}}
            <?php $settingsModule = ucmodule('settings'); ?>
            @if (Auth::user()->canAccessToSettingsPanel($domain) && $settingsModule->isActiveOnDomain($domain) && !$admin_env)
                <li class="header">{{ uctrans('menu.admin', $module) }}</li>

                @yield('sidebar-admin-menu-before')

                <li>
                    <a href="{{ ucroute('uccello.settings.dashboard', $domain) }}">
                        <i class="material-icons">{{ $settingsModule->icon }}</i>
                        <span>{{ uctrans('uccello::settings.settings') }}</span>
                    </a>
                </li>

                @yield('sidebar-admin-menu-after')

            @endif
        </ul>
    @show

    @yield('sidebar-menu-after')
</div>