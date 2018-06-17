@extends('layouts.app')

@section('content')
    {!! form_start($form) !!}
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
                        <?php
                            // If a special template exists, use it. Else use the generic template
                            $uitypeViewName = sprintf('uitypes.edit.%s', $field->uitype);
                            $uitypeFallbackView = 'uccello::modules.default.uitypes.edit.text';
                            $uitypeViewToInclude = ucview($module, $uitypeViewName, $uitypeFallbackView);
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