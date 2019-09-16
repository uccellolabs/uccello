<section>
    @include('uccello::layouts.partials.sidenav.left.main')

    @if (config('uccello.domains.display_tree') !== false)
        @include('uccello::layouts.partials.sidenav.right.main')
    @endif
</section>