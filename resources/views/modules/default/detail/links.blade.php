
<div class="col-sm-8 col-xs-12 text-right">

    @yield('other-links')

    @if (count($module->detailLinks) > 0)
    @foreach ($module->detailLinks as $link)
        <div class="btn-group m-l-10">
        @include('uccello::layouts.partials.link.main', ['link' => $link])
        </div>
    @endforeach
    @endif

    @if (count($module->detailActionLinks) > 0)
    <div class="btn-group m-l-10">
        <button type="button" class="btn bg-primary icon-right waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            {!! uctrans('button.action', $module) !!}
            <i class="material-icons">keyboard_arrow_down</i>
        </button>
        <ul class="dropdown-menu">
            @foreach ($module->detailActionLinks as $link)
                <li>
                    @include('uccello::layouts.partials.link.main', ['link' => $link])
                </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>