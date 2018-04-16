@extends('uccello::layouts.main')

@section('pre-content')
    @include('uccello::layouts.partials.loader')
    <div class="overlay"></div>
    @include('uccello::layouts.partials.header')
    @include('uccello::layouts.partials.sidebar', ["domain" => $domain ?? null, "module" => $module ?? null])
@endsection