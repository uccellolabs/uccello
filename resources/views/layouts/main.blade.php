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
    <link rel="icon" href="{{ ucasset('images/favicon.png') }}" type="image/x-icon">
    <base href="/">

    {{-- CSS --}}
    @section('css')
        {{ Html::style(mix('css/app.css', 'vendor/uccello/uccello')) }}
    @show

    {{-- Extra CSS --}}
    @yield('uccello-extra-css')

    {{-- For application --}}
    @yield('app-css')
    @yield('extra-css')
</head>

<body class="@section('body-class')theme-{{ config('uccello.skin', 'uccello') }}@show @yield('body-extra-class')">
    @yield('pre-content')

    @section('content-container')
    <section class="content @yield('content-class')">
        {{-- Breadcrumb --}}
        <div class="breadcrumb-container">
            @yield('breadcrumb')
        </div>

        {{-- Content --}}
        @yield('content')
    </section>
    @show

    @yield('extra-content')

    {{-- Flash notifications --}}
    @include('uccello::layouts.partials.notifications.main')

    @section('uccello-script')
    {{ Html::script('//momentjs.com/downloads/moment-with-locales.min.js') }}
    {{ Html::script(mix('js/manifest.js', 'vendor/uccello/uccello')) }}
    {{ Html::script(mix('js/vendor.js', 'vendor/uccello/uccello')) }}
    {{ Html::script(mix('js/app.js', 'vendor/uccello/uccello')) }}
    @show

    @section('uccello-autoloader-script')
    {{ Html::script(mix('js/autoloader.js', 'vendor/uccello/uccello')) }}
    @show

    @yield('uccello-extra-script')

    {{-- For Application --}}
    @yield('app-script')
    @yield('app-extra-script')
</body>

</html>
