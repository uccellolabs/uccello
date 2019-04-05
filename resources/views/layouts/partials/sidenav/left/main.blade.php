@section('sidebar-left')
<aside>
    <ul id="sidenav-menu" class="sidenav sidenav-fixed">
        {{-- User info --}}
        @include('uccello::layouts.partials.sidenav.left.user')

        {{-- Menu before --}}
        @yield('sidebar-menu-before')

        {{-- Menu --}}
        @include('uccello::layouts.partials.sidenav.left.menu')

        {{-- Menu after --}}
        @yield('sidebar-menu-after')
    </ul>
</aside>
@show