{{-- <img src="{{ asset('img/') }}" --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css', 'vendor/uccello/uccello') }}" rel="stylesheet">
    <title>@yield('title', 'Uccello')</title>
    @livewireStyles
</head>
<body class="flex">
    {{-- Sidebar --}}
    <aside class="w-1/6 h-screen bg-white border border-gray-200 text-sm overflow-auto">
        {{-- Logo & Workspace --}}
        <div class="text-base font-semibold py-7 px-5 bg-white sticky top-0">
            <div class="">Logo entreprise</div>
        </div>
        <div class="px-5 mb-10">
            
            <div class="bg-gray-100 border border-gray-200 p-2">
                <div class="">
                    <div class="">My workspace</div>
                    <div class=""></div>
                </div>
                <div class=""></div>
            </div>
        </div>
        {{-- Sidebar content --}}
        <div class="pl-8 pr-5 text-gray-500 font-normal">
            {{-- Dashboard --}}
            <div class="mb-10" x-data="{open:false}">
                {{-- Dashboard title --}}
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row text-primary-500 font-semibold text-xs">DASHBOARD</div>
                    <div class="flex flex-row items-center justify-between">
                        <div class="text-white bg-primary-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-2 text-primary-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div> {{-- End Dashboard --}}

            {{-- Modules --}}
            <div class="mb-10" x-data="{open:false}">
                {{-- Modules title --}}
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row text-primary-500 font-semibold text-xs">MES MODULES</div>
                    <div class="flex flex-row items-center justify-between">
                        <div class="text-white bg-primary-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-2 text-primary-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Modules content --}}
                {{-- Category --}}
                <div class="">
                    {{-- Category title --}}
                    <div class="flex flex-row justify-between text-primary-900 my-4">
                        <div class="">Catégorie 1</div>
                        <div class="text-primary-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>
                    </div>
                    {{-- Category content --}}
                    {{-- Module Item --}}
                    <div class="flex flex-row justify-between items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Module 1</div>
                        </div>
                        <div class="ml-2 text-primary-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Module 2</div>
                        </div>
                        <div class="ml-2 text-primary-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-row items-center py-2">
                            <div class="border border-primary-500 border-dashed p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Créer un module</div>
                        </div>
                    </div>
                </div>
                <div class="">
                    {{-- Category title --}}
                    <div class="flex flex-row justify-between text-primary-900 my-4">
                        <div class="">Catégorie 2</div>
                        <div class="text-primary-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>
                    </div>
                    {{-- Category content --}}
                    {{-- Module Item --}}
                    <div class="flex flex-row justify-between items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Module 1</div>
                        </div>
                        <div class="ml-2 text-primary-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-row items-center py-2">
                            <div class="border border-primary-500 border-dashed p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Créer un module</div>
                        </div>
                    </div>
                </div>
            </div> {{-- End Modules --}}

            {{-- Tools --}}
            <div class="mb-10" x-data="{open:false}">
                {{-- Tools title --}}
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row text-primary-500 font-semibold text-xs">MES OUTILS</div>
                    <div class="flex flex-row items-center justify-between">
                        <div class="text-white bg-primary-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-2 text-primary-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Tools content --}}
                <div class="">
                    {{-- Tool Item --}}
                    <div class="flex flex-row items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Workflow designer</div>
                        </div>
                    </div>
                    <div class="flex flex-row items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Document designer</div>
                        </div>
                    </div>
                    <div class="flex flex-row items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Data designer</div>
                        </div>
                    </div>
                    <div class="flex flex-row items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Module designer</div>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-row items-center py-2">
                            <div class="border border-primary-500 border-dashed p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Ajouter un outil</div>
                        </div>
                    </div>
                </div>
            </div> {{-- End Tools --}}
        </div>
    </aside>

    {{-- Header --}}

    {{-- Content --}}
    <main class="p-12">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
