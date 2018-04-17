@extends('layouts.app')

@section('content')
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
                        <span>{{ __(sprintf('uccello::%s.%s', $module->name , $block->label)) }}</span>
                    </div>

                    {{-- Description --}}
                    @if ($block->description)
                        <small>{{ __(sprintf('uccello::%s.%s', $module->name , $block->description)) }}</small>                        
                    @endif
                </h2>
            </div>
            <div class="body">
                
            </div>
        </div>
        @endforeach       
    @endforeach

    {{-- Other blocks --}}
    @yield('other-blocks')
@endsection