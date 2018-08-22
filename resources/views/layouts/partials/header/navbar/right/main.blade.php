<div class="collapse navbar-collapse" id="navbar-collapse">
    <ul class="nav navbar-nav navbar-right">
        {{-- <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
        @include('uccello::layouts.partials.header.navbar.right.notifications')
        @include('uccello::layouts.partials.header.navbar.right.tasks') --}}

        {{-- Display current domain name and a link to open domains list --}}
        @if (uccello()->useMultiDomains())
        <li class="pull-right">
            <a href="javascript:void(0);" class="current-domain js-right-sidebar" data-close="true">
                <span>{{ $domain->name }}</span>
                <i class="material-icons">keyboard_arrow_down</i>
            </a>
        </li>
        @endif
    </ul>
</div>