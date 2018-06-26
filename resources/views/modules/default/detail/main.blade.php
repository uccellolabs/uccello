@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('uccello.edit', ['domain' => $domain->slug, 'module' => $module->name, 'id' => $record->id]) }}" class="btn btn-success">
                {{ uctrans('button.edit', $module) }}
            </a>

            <a href="{{ route('uccello.delete', ['domain' => $domain->slug, 'module' => $module->name, 'id' => $record->id]) }}" class="btn btn-danger pull-right">
                {{ uctrans('button.delete', $module) }}
            </a>
        </div>
    </div>

    @section('default-blocks')
        {{-- All defined blocks --}}
        @foreach ($structure->tabs as $tab)  {{-- TODO: Display all tabs --}}
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
                            $uitypeViewName = sprintf('uitypes.detail.%s', $field->uitype);
                            $uitypeFallbackView = 'uccello::modules.default.uitypes.detail.text';
                            $uitypeViewToInclude = uccello()->view($module, $uitypeViewName, $uitypeFallbackView);
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