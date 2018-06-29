<div class="menu">
    @yield('sidebar-menu-before')

    @section('sidebar-main-menu')
        <ul class="list">
            <li class="header">{{ strtoupper(uctrans('menu.title', $module)) }}</li>

            @yield('sidebar-main-menu-before')

            <?php $homeModule = ucmodule('home'); ?>
            @if (isset($domain) && $homeModule->isActiveOnDomain($domain) && Auth::user()->canRetrieve($domain, $homeModule))
            {{-- Home module --}}
            <li @if ('home' === $module->name)class="active"@endif>
                <a href="/{{ $domain->slug }}/home">
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
                        <a href="{{ route('uccello.list', ['domain' => $domain->slug, 'module' => $_module->name]) }}">
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