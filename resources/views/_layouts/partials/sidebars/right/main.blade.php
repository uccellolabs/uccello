<aside id="rightsidebar" class="right-sidebar">
    <div class="domain-search-bar">
        <input type="text" id="domain-name" placeholder="{{ uctrans('domain.search', $module) }}">
    </div>

    <div class="content">
        <div id="domains-tree">
            @include('uccello::layouts.partials.sidebars.right.domains.tree', ['domains' => uccello()->getRootDomains()])
        </div>
    </div>
</aside>