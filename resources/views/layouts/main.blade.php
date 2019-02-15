
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
        <base href="/">

        {{-- CSS --}}
        @section('css')
            {{ Html::style(ucasset('css/materialize.css')) }}
            {{ Html::style(ucasset('css/app.css')) }}
        @show

        {{-- Extra CSS --}}
        @yield('extra-css')
    </head>

    <body class="@section('body-class')theme-{{ config('uccello.theme', 'uccello') }}@show @yield('body-extra-class')">
        {{-- Loader --}}
        <div class="progress loader">
            <div class="indeterminate"></div>
        </div>

        @yield('pre-content')

        @section('content-container')
        <main id="app">
            <div class="content @yield('content-class')">
                {{-- Breadcrumb --}}
                <div class="breadcrumb-container">
                    @yield('breadcrumb')
                </div>

                {{-- Content --}}
                @yield('content')

                @yield('extra-content')
            </div>
        </main>

        {{-- Flash notifications --}}
        @include('uccello::layouts.partials.notifications.main')

        @section('script')
        {{ Html::script(asset('js/app.js')) }}
        {{-- {{ Html::script(ucasset('js/manifest.js')) }}
        {{ Html::script(ucasset('js/vendor.js')) }}
        {{ Html::script(ucasset('js/app.js')) }} --}}
        {{ Html::script(ucasset('js/app.js')) }}
        @show

        @section('autoloader-script')
        {{-- {{ Html::script(ucasset('js/autoloader.js')) }} --}}
        @show

        @yield('extra-script')
    </body>
</html>