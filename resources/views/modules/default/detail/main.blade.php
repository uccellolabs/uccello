@extends('layouts.app')

@section('page', 'detail')

@section('content')

    @section('breadcrumb')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb pull-left">
                {{-- Redirect to previous page. If there is not previous page, redirect to list view --}}
                <a href="{{ URL::previous() !== URL::current() ? URL::previous() : ucroute('uccello.list', $domain, $module) }}" class="pull-left">
                    <i class="material-icons" data-toggle="tooltip" data-placement="top" title="{{ uctrans('button.return', $module) }}">chevron_left</i>
                </a>

                <ol class="breadcrumb pull-left">
                    <li><a href="{{ ucroute('uccello.list', $domain, $module) }}">{{ uctrans($module->name, $module) }}</a></li>
                    <li class="active">{{ $record->recordLabel }}</li>
                </ol>
            </div>
        </div>
    </div>
    @show

    <div class="detail-blocks">
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
    </div>

    @section('page-action-buttons')
    <div id="page-action-buttons">
        <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => $record->id]) }}" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.edit', $module) }}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">edit</i>
        </a>

        <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => $record->id]) }}" class="btn btn-danger btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.delete', $module) }}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">delete</i>
        </a>
    </div>
    @show
@endsection