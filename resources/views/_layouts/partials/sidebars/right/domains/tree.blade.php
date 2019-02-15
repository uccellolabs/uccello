@if ($domains)
    <ul>
    @foreach ($domains as $_domain)
        <li @if ($_domain->id === $domain->id)class="current"@endif>
            <a href="{{ ucroute('uccello.home', $_domain) }}">{{ $_domain->name }}</a>
            @include('uccello::layouts.partials.sidebars.right.domains.tree', ['domains' => $_domain->children()->get()])
        </li>
    @endforeach
    </ul>
@endif