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
                    <i class="material-icons">home</i>
                    <span>{{ uctrans('uccello::home.home') }}</span>
                </a>
            </li>
            @endif

            {{-- All modules except Home --}}
            @if (isset($modules) && isset($domain) && isset($module))
                @foreach ($modules as $_module)
                    @continue ($_module->name === 'home' || !$_module->isActiveOnDomain($domain) || !Auth::user()->canRetrieve($domain, $_module))
                    <li @if ($_module->id === $module->id)class="active"@endif>
                        <a href="{{ ucroute('uccello.list', $domain, $_module) }}">
                            @if ($_module->icon)<i class="material-icons">{{ $_module->icon ?? 'list' }}</i>@endif
                            <span>{{ uctrans($_module->name, $_module) }}</span>
                        </a>
                    </li>
                @endforeach
            @endif

            @yield('sidebar-main-menu-after')
        </ul>
    @show

    @yield('sidebar-menu-after')
</div>