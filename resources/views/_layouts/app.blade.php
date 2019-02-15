@extends('uccello::layouts.main')

@section('pre-content')
    @include('uccello::layouts.partials.loader')
    <div class="overlay"></div>
    @include('uccello::layouts.partials.header.main')
    @include('uccello::layouts.partials.sidebars.main')
@endsection