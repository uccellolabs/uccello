
<div class="col-sm-6 col-xs-12">

    @yield('other-links')

    {{-- Add widget --}}
    {{-- @if (Auth()->user()->canAdmin($domain, $module))
    <div class="btn-group add-widget m-l-10">
        <button type="button" class="btn bg-orange icon-right waves-effect pull-right dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            {{ uctrans('button.add_widget', $module) }}
            <i class="material-icons">widgets</i>
        </button>
        <ul id="items-number" class="dropdown-menu">
            @foreach ($availableWidgets as $availableWidget)
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-id="{{ $availableWidget->id }}">{{ trans($availableWidget->labelForTranslation) }}</a></li>
            @endforeach
        </ul>
    </div>
    @endif --}}

    @if (count($module->detailLinks) > 0)
    @foreach ($module->detailLinks as $link)
        @include('uccello::layouts.partials.link.main', ['link' => $link])
    @endforeach
    @endif

    @if (count($module->detailActionLinks) > 0)
        <a class='dropdown-trigger btn bg-primary' href='#' data-target='dropdown_actions'>
            <i class="material-icons right">keyboard_arrow_down</i>
            {!! uctrans('button.action', $module) !!}
        </a>
        <ul id='dropdown_actions' class="dropdown-content">
            @foreach ($module->detailActionLinks as $link)
                <li>
                    @include('uccello::layouts.partials.link.main', ['link' => $link])
                </li>
            @endforeach
        </ul>
    @endif
</div>