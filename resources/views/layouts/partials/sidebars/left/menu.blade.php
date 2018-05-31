<div class="menu">
    <ul class="list">
        @yield('sidebar-menu-before')

        @section('sidebar-main-menu')
            <ul class="list">
                <li class="header">{{ strtoupper(uctrans('menu.title', $module)) }}</li>            
            </ul>
            
            @yield('sidebar-main-menu-before')

            @if (isset($domain))
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
                    @if ($_module->name !== 'home')
                    <li @if ($_module->id === $module->id)class="active"@endif>
                        <a href="{{ route('list', ['domain' => $domain->slug, 'module' => $_module->name]) }}">
                            @if ($_module->icon)<i class="material-icons">{{ $_module->icon ?? 'list' }}</i>@endif
                            <span>{{ uctrans($_module->name, $_module) }}</span>
                        </a>
                    </li>
                    @endif
                @endforeach
            @endif

            @yield('sidebar-main-menu-after')
        @show

        @yield('sidebar-menu-after')
    </ul>
</div>