<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>@yield('title', 'Uccello')</title>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <main class="m-12">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
