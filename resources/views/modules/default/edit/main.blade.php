@extends('layouts.app')

@section('page', 'edit')

@section('content')

    @section('breadcrumb')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb pull-left">
                {{-- Module icon --}}
                <a href="{{ ucroute('uccello.list', $domain, $module) }}" class="pull-left module-icon">
                    <i class="material-icons">{{ $module->icon ?? 'extension' }}</i>
                </a>

                <ol class="breadcrumb pull-left">
                    @if ($admin_env)<li><a href="{{ ucroute('uccello.settings.dashboard', $domain) }}">{{ uctrans('breadcrumb.admin', $module) }}</a></li>@endif
                    <li><a href="{{ ucroute('uccello.list', $domain, $module) }}">{{ uctrans($module->name, $module) }}</a></li>
                    @if ($record->getKey())<li><a href="{{ ucroute('uccello.detail', $domain, $module, ['id' => $record->getKey()]) }}">{{ $record->recordLabel ?? $record->getKey() }}</a></li>@endif
                    <li class="active">{{ $record->getKey() ? uctrans('edit', $module) : uctrans('create', $module) }}</li>
                </ol>
            </div>
        </div>
    </div>
    @show

    {!! form_start($form) !!}
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
                    <div class="row display-flex">
                    {{-- Display all block's fields --}}
                    @foreach ($block->fields as $field)
                        {{-- Check if the field can be displayed --}}
                        @continue(($mode === 'edit' && !$field->isEditable()) || ($mode === 'create' && !$field->isCreateable()))
                        <?php
                            // If a special template exists, use it. Else use the generic template
                            $uitypeViewName = sprintf('uitypes.edit.%s', $field->uitype->name);
                            $uitypeFallbackView = 'uccello::modules.default.uitypes.edit.text';
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

    {!! form_end($form) !!}
@endsection