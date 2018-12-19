<div class="menu">
    @yield('sidebar-menu-before')

    @section('sidebar-main-menu')
        <ul class="list">
            @yield('sidebar-main-menu-before')

            <?php $homeModule = ucmodule('home'); ?>
            @if (isset($domain) && $homeModule->isActiveOnDomain($domain) && Auth::user()->canRetrieve($domain, $homeModule))
            {{-- Home module --}}
            <li @if ('home' === $module->name)class="active"@endif>
                <a href="{{ ucroute('uccello.home', $domain) }}">
                    @if(!$admin_env)
                    <i class="material-icons">{{ $homeModule->icon }}</i>
                    <span>{{ uctrans('uccello::home.home') }}</span>
                    @else
                    <i class="material-icons col-primary">arrow_back_ios</i>
                    <span class="col-primary">{{ uctrans('uccello::default.menu.return') }}</span>
                    @endif
                </a>
            </li>
            @endif

            <?php $settingsModule = ucmodule('settings'); ?>
            @if (isset($domain) && $settingsModule->isActiveOnDomain($domain) && Auth::user()->canAccessToSettingsPanel($domain) && $admin_env)
            {{-- Settings module --}}
            <li @if ('settings' === $module->name)class="active"@endif>
                <a href="{{ ucroute('uccello.settings', $domain) }}">
                    <i class="material-icons">{{ $settingsModule->icon }}</i>
                    <span>{{ uctrans('uccello::settings.settings') }}</span>
                </a>
            </li>
            @endif

            {{-- All modules except if there are not displayed in menu --}}
            @if (isset($modules) && isset($domain) && isset($module))
                @foreach ($modules as $_module)
                    @continue ($_module->name === 'home' || $_module->name === 'settings' || !$_module->isDisplayedInMenu() || !$_module->isActiveOnDomain($domain) || !Auth::user()->canRetrieve($domain, $_module) || (!$admin_env && $_module->isAdminModule()) || ($admin_env && !$_module->isAdminModule()))
                    <li @if ($_module->id === $module->id)class="active"@endif>
                        <?php
                            // Use route if defined, else use default one
                            $routeName = isset($_module->data->route) ? $_module->data->route : 'uccello.list';
                        ?>
                        <a href="{{ ucroute($routeName, $domain, $_module) }}">
                            <i class="material-icons">{{ $_module->icon ?? 'extension' }}</i>
                            <span>{{ uctrans($_module->name, $_module) }}</span>
                        </a>
                    </li>
                @endforeach

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
                    <a href="{{ ucroute('uccello.settings', $domain) }}">
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