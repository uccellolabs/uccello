
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title>@yield('title', config('app.name', 'Uccello'))</title>

        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- Page name --}}
        <meta name="page" content="@yield('page')">

        @if($domain ?? false)
        {{-- Domains tree urls --}}
        <meta name="domains-tree-default-url" content="{{ ucroute('uccello.domains.tree.root', $domain) }}">
        <meta name="domains-tree-children-url" content="{{ ucroute('uccello.domains.tree.children', $domain) }}">
        <meta name="domains-tree-open-all" content="{{ config('uccello.domains.open_tree', true) }}">
        {{-- Domain --}}
        <meta name="domain" content="{{ $domain->slug }}">
        {{-- Module --}}
        <meta name="module" content="{{ $module->name }}">
        @endif

        @yield('extra-meta')

        {{-- Favicon --}}
        <link rel="icon" href="{{ ucasset('images/favicon.png') }}" type="image/x-icon">
        <base href="/">

        {{-- CSS --}}
        @section('uccello-css')
            {{ Html::style(mix('css/materialize.css', 'vendor/uccello/uccello')) }}
            {{ Html::style(mix('css/app.css', 'vendor/uccello/uccello')) }}
        @show

        {{-- Extra CSS --}}
        @yield('uccello-extra-css')

        {{-- For application --}}
        @yield('css')
        @yield('extra-css')
    </head>

    <body class="@section('body-class')theme-{{ config('uccello.theme', 'uccello') }}@show @yield('body-extra-class')">
        @yield('pre-content')

        @section('content-container')
        <main id="app">
            <div class="content @yield('content-class')">
                {{-- Content --}}
                @yield('content')

                @yield('extra-content')
            </div>
        </main>

        @yield('post-content')

        {{-- Flash notifications --}}
        @include('uccello::layouts.partials.notifications.main')

        {{-- Translations --}}
        @translations('uctranslations')
        @yield('translations')

        @section('uccello-script')
        {{-- {{ Html::script('//momentjs.com/downloads/moment-with-locales.min.js') }} --}}
        {{ Html::script(mix('js/manifest.js', 'vendor/uccello/uccello')) }}
        {{ Html::script(mix('js/vendor.js', 'vendor/uccello/uccello')) }}
        {{ Html::script(mix('js/app.js', 'vendor/uccello/uccello')) }}
        @show

        @yield('script')

        @section('uccello-autoloader-script')
        {{ Html::script(mix('js/autoloader.js', 'vendor/uccello/uccello')) }}
        @show

        @yield('uccello-extra-script')
        @yield('extra-script')
    </body>
</html>
