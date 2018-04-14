<!DOCTYPE html>
<html lang="@yield('lang', config('app.locale', 'en'))">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>@yield('title', config('app.name', 'Uccello'))</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <base href="/">

    <!-- Bootstrap Core Css -->
    @section('css')
        {{ Html::style('vendor/uccello/plugins/bootstrap/css/bootstrap.css') }}
        {{ Html::style('vendor/uccello/plugins/node-waves/waves.css') }}
        {{ Html::style('vendor/uccello/plugins/animate-css/animate.css') }}
        {{ Html::style('vendor/uccello/plugins/morrisjs/morris.css') }}
        {{ Html::style('css/vendor/uccello/admin-bsb.min.css') }}
        {{ Html::style('css/vendor/uccello/all-themes.min.css') }}
        {{--  {{ Html::style('css/app.css') }}  --}}

         <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    @show

    @yield('extra-css')
</head>

<body class="theme-{{ config('uccello.skin', 'red') }}">
    @include('uccello::layouts.partials.loader')
    <div class="overlay"></div>
    @include('uccello::layouts.partials.header')
    @include('uccello::layouts.partials.sidebar')

    <section class="content">
        @yield('content')
    </section>

    @section('script')
        {{Html::script('vendor/uccello/plugins/jquery/jquery.min.js')}}
        {{Html::script('vendor/uccello/plugins/bootstrap/js/bootstrap.js')}}
        {{Html::script('vendor/uccello/plugins/bootstrap-select/js/bootstrap-select.js')}}
        {{-- {{Html::script('vendor/uccello/plugins/jquery-slimscroll/jquery.slimscroll.js')}} --}}
        {{Html::script('vendor/uccello/plugins/node-waves/waves.js')}}
    @show    

    @yield('extra-script')
    @section('script-bottom')
        {{Html::script('js/vendor/uccello/admin-bsb.js')}}
        {{-- {{Html::script('js/uccello.js')}} --}}
    @show
</body>

</html>
