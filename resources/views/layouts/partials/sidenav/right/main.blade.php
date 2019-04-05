@section('sidebar-right')
<aside>
    <ul id="sidenav-domains" class="sidenav">
        <div class="domain-search-bar">
            <input type="text" id="domain-name" placeholder="{{ uctrans('domain.search', $module) }}">
        </div>

        <div class="content">
            <div id="domains-tree">
                {!! $domainsTreeHtml !!}
            </div>
        </div>
    </ul>
</aside>
@show