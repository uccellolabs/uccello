<div class="menu">
    @yield('sidebar-menu-before')

    @section('sidebar-main-menu')
        <ul class="list">
            @yield('sidebar-main-menu-before')

            @if ($admin_env)
                <?php $menuItems = $domain->settingsMenu->data ?? null; ?>
            @else
                <?php $menuItems = $domain->classicMenu->data ?? null; ?>
            @endif

            @if (!empty($menuItems))
                @include('uccello::layouts.partials.sidebars.left.menu.custom')
            @else
                @include('uccello::layouts.partials.sidebars.left.menu.default')
            @endif

            {{-- All modules except if there are not displayed in menu --}}
            @if (isset($modules) && isset($domain) && isset($module))

                {{-- If we are on a module not displayed in the menu, add it without link --}}
                @if (!$module->isDisplayedInMenu())
                    <li class="active">
                        <a href="javascript:void(0)">
                            @if ($module->icon)<i class="material-icons">{{ $module->icon ?? 'list' }}</i>@endif
                            <span>{{ uctrans($module->name, $module) }}</span>
                        </a>
                    </li>
                @endif
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