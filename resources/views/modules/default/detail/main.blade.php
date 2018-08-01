@extends('layouts.app')

@section('page', 'detail')

@section('content')
    <div class="block-header">
        <h2>{{ $record->displayLabel }}</h2>
    </div>

    <div id="action-buttons" class="m-b-25">
        <a href="{{ ucroute('uccello.list', $domain, $module) }}" class="btn btn-default icon-left waves-effect m-r-10">
            {{ uctrans('button.return', $module) }}
            <i class="material-icons">chevron_left</i>
        </a>

        <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => $record->id]) }}" class="btn btn-success icon-left waves-effect">
            {{ uctrans('button.edit', $module) }}
            <i class="material-icons">edit</i>
        </a>

        <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => $record->id]) }}" class="btn btn-danger icon-right waves-effect pull-right">
            {{ uctrans('button.delete', $module) }}
            <i class="material-icons">delete</i>
        </a>
    </div>

    @section('default-blocks')
        {{-- All defined blocks --}}
        @foreach ($module->tabs as $tab)  {{-- TODO: Display all tabs --}}
            @foreach ($tab->blocks as $block)
            <div class="card block">
                <div class="header">
                    <h2>
                        <div @if($block->icon)class="block-label-with-icon"@endif>

                            {{-- Icon --}}
                            @if($block->icon)
                            <i class="material-icons">{{ $block->icon }}</i>
                            @endif

                            {{-- Label --}}
                            <span>{{ uctrans($block->label, $module) }}</span>
                        </div>

                        {{-- Description --}}
                        @if ($block->description)
                            <small>{{ uctrans($block->description, $module) }}</small>
                        @endif
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                    {{-- Display all block's fields --}}
                    @foreach ($block->fields as $field)
                        @continue(!$field->isDetailable())
                        <?php
                            // If a special template exists, use it. Else use the generic template
                            $uitypeViewName = sprintf('uitypes.detail.%s', $field->uitype->name);
                            $uitypeFallbackView = 'uccello::modules.default.uitypes.detail.text';
                            $uitypeViewToInclude = uccello()->view($field->uitype->package, $module, $uitypeViewName, $uitypeFallbackView);
                        ?>
                        @include($uitypeViewToInclude)
                    @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    @show

    {{-- Other blocks --}}
    @yield('other-blocks')
@endsection