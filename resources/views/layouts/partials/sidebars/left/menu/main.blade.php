<div class="menu">
    @yield('sidebar-menu-before')

    @section('sidebar-main-menu')
        <ul class="list">
            @yield('sidebar-main-menu-before')

            <?php $homeModule = ucmodule('home'); ?>
            @if ($admin_env && isset($domain) && $homeModule->isActiveOnDomain($domain) && Auth::user()->canRetrieve($domain, $homeModule))
            {{-- Home module --}}
            <li @if ('home' === $module->name)class="active"@endif>
                <a href="{{ ucroute('uccello.home', $domain) }}">
                    <i class="material-icons col-primary">arrow_back_ios</i>
                    <span class="col-primary">{{ uctrans('menu.return', $module) }}</span>
                </a>
            </li>
            @endif

            {{-- Render menu --}}
            {!! $menu->render() !!}

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