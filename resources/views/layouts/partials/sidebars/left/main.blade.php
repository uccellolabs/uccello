@section('sidebar-left')
<aside>
    <ul class="sidenav sidenav-fixed">
        {{-- User info --}}
        @include('uccello::layouts.partials.sidebars.left.user')

        {{-- Menu before --}}
        @yield('sidebar-menu-before')

        {{-- Menu --}}
        @include('uccello::layouts.partials.sidebars.left.menu')

        {{-- Menu after --}}
        @yield('sidebar-menu-after')
    </ul>


</aside>
@show