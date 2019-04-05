@if ($domains)
    <ul class="browser-default">
    @foreach ($domains as $_domain)
        <li @if ($_domain->id === $domain->id)class="current"@endif>
            <a href="{{ ucroute('uccello.home', $_domain) }}">{{ $_domain->name }}</a>
            @include('uccello::layouts.partials.sidenav.right.domains.tree', ['domains' => $_domain->children()->get()])
        </li>
    @endforeach
    </ul>
@endif