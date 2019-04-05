@section('sidebar-right')
<aside>
    <ul id="sidenav-domains" class="sidenav">
        <div class="domain-search-bar">
            <input type="text" id="domain-name" placeholder="{{ uctrans('domain.search', $module) }}">
        </div>

        <div class="content">
            <div id="domains-tree">
                @include('uccello::layouts.partials.sidenav.right.domains.tree', ['domains' => uccello()->getRootDomains()])
            </div>
        </div>
    </ul>
</aside>
@show