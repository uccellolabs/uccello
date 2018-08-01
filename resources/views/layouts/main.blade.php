<!DOCTYPE html>
<html lang="@yield('lang', config('app.locale', 'en'))">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>@yield('title', config('app.name', 'Uccello'))</title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Page name --}}
    <meta name="page" content="@yield('page')">
    {{-- Domain --}}
    @if($domain ?? false)<meta name="domain" content="{{ $domain->slug }}">@endif
    {{-- Module --}}
    @if($module ?? false)<meta name="module" content="{{ $module->name }}">@endif

    @yield('extra-meta')

    {{-- Favicon --}}
    <link rel="icon" href="img/favicon.png" type="image/x-icon">
    <base href="/">

    {{-- CSS --}}
    @section('css')
        {{ Html::style('css/app.css') }}
    @show

    {{-- Extra CSS --}}
    @yield('extra-css')
</head>

<body class="@section('body-class')theme-{{ config('uccello.skin', 'uccello') }}@show @yield('body-extra-class')">
    @yield('pre-content')

    @section('content-container')
    <section class="content">
        @yield('content')
    </section>
    @show

    @section('script')
    {{ Html::script(mix('js/manifest.js')) }}
    {{ Html::script(mix('js/vendor.js')) }}
    {{ Html::script(mix('js/app.js')) }}
    @show

    @yield('extra-script')

    @section('autoloader-script')
    {{ Html::script(mix('js/autoloader.js')) }}
    @show
</body>

</html>
