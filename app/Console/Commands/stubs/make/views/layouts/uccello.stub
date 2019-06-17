@extends('uccello::layouts.app')

@if (auth()->check())
    @section('user-name'){{ auth()->user()->name }}@endsection
    @section('user-email') {{ auth()->user()->email }}@endsection
@endif

@section('css')
    {{-- Put your global CSS files here --}}

    {{-- Uncomment below to load css/app.js with Mix --}}
    {{-- {!! Html::style(mix('css/app.css')) !!} --}}
@endsection

@section('script')
    {{-- Put your global JS files here --}}

    {{-- Uncomment below to load js/app.js with Mix --}}
    {{-- {!! Html::script(mix('js/app.js')) !!} --}}
@endsection