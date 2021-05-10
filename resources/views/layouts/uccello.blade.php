{{-- <img src="{{ ucasset('img/') }}"> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css', 'vendor/uccello/uccello') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <title>@yield('title', 'Uccello')</title>
    @livewireStyles
</head>
<body class="flex text-primary-900 bg-gray-100">
    {{-- Sidebar --}}
    <aside class="z-20 flex flex-col w-1/6 h-screen bg-white border border-gray-200 text-xs overflow-auto">
        {{-- Logo & Workspace --}}
        <div class="text-base font-semibold py-7 px-5 bg-white sticky top-0">
            <div class="">Logo entreprise</div>
        </div>
        <div class="px-5 mb-10">
            
            <div class="flex flex-row rounded bg-gray-100 border border-gray-200 p-2">
                <div class="w-5/6 flex justify-between items-center">
                    <div class="">My workspace</div>
                    <div class="mr-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <div class="w-1/6 flex justify-center items-center border-l border-gray-200 pl-1">
                    <div class="flex items-center text-primary-900">
                        <img class="text-primary-900" src="{{ ucasset('img/filter_picto.svg') }}">
                    </div>
                </div>
            </div>
        </div>
        {{-- Sidebar content --}}
        <div class="px-5 mb-7 text-gray-500 font-normal">
            {{-- Dashboard --}}
            <div class="mb-10" x-data="{open:false}">
                {{-- Dashboard title --}}
                <div class="flex flex-row justify-between cursor-pointer" x-on:click="open=!open">
                    <div class="flex flex-row text-primary-500 font-semibold text-xs">DASHBOARD</div>
                    <div class="flex flex-row items-center justify-between">
                        <div class="text-white bg-primary-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-2 text-primary-900"  :class="{'transform rotate-180 duration-300':open===true, 'transform duration-300':open===false}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
                {{-- Dashboard content --}}
                <div class="my-4" x-show.transition="open">
                    {{-- Tool Item --}}
                    <div class="flex flex-row items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Qualité</div>
                        </div>
                    </div>
                    <div class="flex flex-row items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Budget</div>
                        </div>
                    </div>
                    <div class="flex flex-row items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Sécurité</div>
                        </div>
                    </div>
                    <div class="flex flex-row items-center">
                        <div class="flex flex-row items-center py-2">
                            <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                            <div class="">Conformité</div>
                        </div>
                    </div>
                </div>
            </div> {{-- End Dashboard --}}

            {{-- Modules --}}
            <div class="mb-10">
                {{-- Modules title --}}
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row text-primary-500 font-semibold text-xs">MES MODULES</div>
                    <div class="flex flex-row items-center justify-between">
                        <div class="text-white bg-primary-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="cursor-pointer" x-data="{hovered:false}" x-on:mouseover="hovered=true" x-on:mouseout="hovered=false">
                            <div class="ml-2 w-5">
                                <div class="text-primary-900" x-show="hovered">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modules content --}}
                {{-- Category --}}
                <div class="" x-data="{open:true}">
                    {{-- Category title --}}
                    <div class="flex flex-row justify-between text-primary-900 my-4 cursor-pointer" x-on:click="open=!open">
                        <div class="">Catégorie 1</div>
                        <div class="text-primary-900" :class="{'transform rotate-180 duration-300':open===true, 'transform duration-300':open===false}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>
                    </div>
                    {{-- Category content --}}
                    <div class="" x-show.transition="open">
                        {{-- Module Item --}}
                        <div class="flex flex-row justify-between items-center cursor-pointer" x-data="{hovered:false}" x-on:mouseover="hovered=true" x-on:mouseout="hovered=false">
                            <div class="flex flex-row items-center py-2">
                                <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                                <div class="">Module 1</div>
                            </div>
                            <div class="ml-2 text-primary-900 w-5" x-show="hovered">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between items-center cursor-pointer" x-data="{hovered:false}" x-on:mouseover="hovered=true" x-on:mouseout="hovered=false">
                            <div class="flex flex-row items-center py-2">
                                <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                                <div class="">Module 2</div>
                            </div>
                            <div class="ml-2 text-primary-900 w-5" x-show="hovered">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </div>
                        </div>
                        </div>
                    </div>
                <div class="" x-data="{open:false}">
                    {{-- Category title --}}
                    <div class="flex flex-row justify-between text-primary-900 my-4 cursor-pointer" x-on:click="open=!open">
                        <div class="">Catégorie 2</div>
                        <div class="text-primary-900" :class="{'transform rotate-180 duration-300':open===true, 'transform duration-300':open===false}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>
                    </div>
                    {{-- Category content --}}
                    <div class="" x-show.transition="open">
                        {{-- Module Item --}}
                        <div class="flex flex-row justify-between items-center cursor-pointer" x-data="{hovered:false}" x-on:mouseover="hovered=true" x-on:mouseout="hovered=false">
                            <div class="flex flex-row items-center py-2">
                                <div class="p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                                <div class="">Module 1</div>
                            </div>
                            <div class="ml-2 text-primary-900" x-show="hovered">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row items-center py-2">
                        <div class="border border-primary-500 border-dashed p-2 mr-5 bg-primary-500 bg-opacity-10 rounded-full"><img src="{{ ucasset('img/module-icon_picto.svg') }}"></div>
                        <div class="">Créer un module</div>
                    </div>
                </div>
            </div> {{-- End Modules --}}

            {{-- Tools --}}
            <div class="mb-10" x-data="{open:true}">
                {{-- Tools title --}}
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row text-primary-500 font-semibold text-xs cursor-pointer" x-on:click="open=!open">MES OUTILS</div>
                    <div class="flex flex-row items-center justify-between">
                        <div class="text-white bg-primary-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="ml-2 text-primary-900" :class="{'transform rotate-180 duration-300':open===true, 'transform duration-300':open===false}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 15l7-7 7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Tools content --}}
                <div class="my-4" x-show.transition="open">
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

    
    <div class="flex flex-col w-5/6">
        {{-- Header --}}
        <header class="z-20 flex flex-row justify-end items-center h-16 w-full text-xs bg-white border-b border-gray-200">
            <ul class="flex flex-row items-center mr-8">
                {{-- Marketplace --}}
                <li class="mr-6">
                    <div class="flex flex-row items-center py-3 px-4 text-orange-500 bg-orange-500 bg-opacity-10 rounded">
                        <div class="mr-3"><img src="{{ ucasset('img/cart_picto.svg') }}"></div>
                        <div class="">Marketplace</div>
                    </div>
                </li>
                {{-- Notifications --}}
                <li class="mr-6">
                    <div class="pl-8 border-l border-gray-200"><img src="{{ ucasset('img/notif_picto.svg') }}"></div>
                </li>
                {{-- User connected --}}
                <li>
                    <div class="flex flex-row items-center">
                        <div class="p-4 mr-2 rounded-md bg-green-300"></div>
                        <div class="flex flex-col leading-4">
                            <div class="text-base">Mes settings</div>
                            <div class="text-sm text-gray-400">carbonneau.guillaume@...</div>
                        </div>
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </li>

            </ul>
        </header>
         
        @yield('content')
    </div>

    @livewireScripts
</body>
</html>
