
<div class="col-sm-6 col-xs-12 text-right">

    @yield('other-links')

    {{-- Add widget --}}
    @if (Auth()->user()->canAdmin($domain, $module))
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
    @endif

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