<div class="menu">
    <ul class="list">
        @section('sidebar-menu')
        <ul class="list">
            <li class="header">MAIN NAVIGATION</li>            
        </ul>
        @if (isset($domain))
        {{-- Home module --}}
        <li @if ('home' === $module->name)class="active"@endif>
            <a href="/{{ $domain->slug }}/home">
                <i class="material-icons">home</i>
                <span>Home</span>
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
        @show
    </ul>
</div>