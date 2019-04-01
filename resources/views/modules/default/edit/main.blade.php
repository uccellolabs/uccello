@extends('layouts.app')

@section('page', 'edit')

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                {{-- Admin --}}
                @if ($admin_env)
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.settings.dashboard', $domain) }}">
                        <i class="material-icons left">settings</i>
                        <span>{{ uctrans('breadcrumb.admin', $module) }}</span>
                    </a>
                </span>
                @endif

                {{-- Module icon --}}
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.list', $domain, $module) }}">
                        <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                        <span>{{ uctrans($module->name, $module) }}</span>
                    </a>
                </span>
                @if ($record->getKey())<a class="breadcrumb" href="{{ ucroute('uccello.detail', $domain, $module, ['id' => $record->getKey()]) }}">{{ $record->recordLabel ?? $record->getKey() }}</a>@endif
                <span class="breadcrumb active">{{ $record->getKey() ? uctrans('edit', $module) : uctrans('create', $module) }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if (count($module->tabs) > 1)
    <div class="row">
        <div class="col s12">
            <ul class="tabs">
                @foreach($module->tabs as $tab_i => $tab)
                <li class="tab col s3"><a @if($tab_i === 0)class="active"@endif href="#tab{{ $tab_i }}">{{ uctrans($tab->label, $module) }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @section('form')
        {!! form_start($form) !!}
        <div class="row">
            @foreach($module->tabs as $tab_i => $tab)
            <div id="#tab{{ $tab_i }}" class="col s12">
                @foreach ($tab->blocks as $block_i => $block)
                <div class="card">
                    <div class="card-content">
                        {{-- Title --}}
                        <span class="card-title">
                            {{-- Icon --}}
                            @if($block->icon)
                            <i class="material-icons primary-text">{{ $block->icon }}</i>
                            @endif

                            {{-- Label --}}
                            {{ uctrans($block->label, $module) }}

                            {{-- Description --}}
                            @if ($block->description)
                                <small>{{ uctrans($block->description, $module) }}</small>
                            @endif
                        </span>

                        {{-- Fields --}}
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
            </div>
            @endforeach
        </div>
        {!! form_end($form) !!}
    @show
</div>
@endsection