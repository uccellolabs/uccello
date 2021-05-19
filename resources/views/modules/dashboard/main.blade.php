@extends('uccello::layouts.uccello')

@section('content')
    {{-- Content --}}
    <div class="p-8">
        {{-- Dashboard --}}
        {{-- Dashboard title --}}
        <div class="flex flex-row items-center">
            <div class="text-primary-900 text-3xl font-semibold">DASHBOARD</div>
        </div>
        {{-- Navigation --}}
        <div class="relative" x-data="{tabs:1}">
            <div class="flex flex-row items-center text-gray-400">
                {{-- Nav title 1 --}}
                <div class="" x-data="{hovered:false}" x-on:mouseover="hovered=true" x-on:mouseout="hovered=false">
                    <div class="flex items-center p-4 ml-6 cursor-pointer" x-on:click="tabs=1" :class="{'text-primary-900 border-b-2 border-primary-900 ':tabs===1}">
                        <div class="">Tab 1</div>
                        <div class="ml-2 text-primary-900 w-5">
                            <div class="" x-show="hovered">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Nav title 2 --}}
                <div class="" x-data="{hovered:false}" x-on:mouseover="hovered=true" x-on:mouseout="hovered=false">
                    <div class="flex items-center p-4 ml-6 cursor-pointer" x-on:click="tabs=2" :class="{'text-primary-900 border-b-2 border-primary-900 ':tabs===2}">
                        <div class="">Tab 2</div>
                        <div class="ml-2 text-primary-900 w-5">
                            <div class="" x-show="hovered">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="p-4 ml-6 cursor-pointer" :class="{'text-primary-900 border-b-2 border-primary-900 ':open===2}">
                        <div class="flex flex-row items-center">
                            <div class="bg-black rounded-full text-white mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="">Add a dashboard</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Tab 1 --}}
            <div class="" x-show="tabs===1">
                
            </div>
            {{-- Add a dashboard --}}
            <div class="">
                {{-- Dashboards content --}}
                <div class="p-8 z-0 flex items-center justify-center bg-white rounded-lg border border-gray-200">
                    {{-- Default bg --}}
                    <img width="92%" src="{{ ucasset('img/dashboard-bg_img.svg') }}">

                    {{-- Button add a dashbaord --}}
                    <div class="absolute z-10 flex items-center bg-primary-500 rounded-lg cursor-pointer p-3 text-white">
                        <div class="bg-primary-500 shadow-lg rounded">
                            <img class="w-10/12" src="{{ ucasset('img/metrics.png') }}">
                        </div>
                        <div class="ml-2">+ Add a dashboard</div>
                    </div>
                </div>
            </div>

            {{-- Dropdown --}}
            <div class="absolute">
                <div class="text-xs absolute right-0 top-12 w-32 bg-white border border-gray-200 rounded-lg shadow-lg">
                    <div class="flex flex-col p-3 font-semibold">
                        <div class="flex items-center flex-row py-1">
                            <div class="mr-2 text-primary-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </div>
                            <div class="">Edit</div>
                        </div>
                        <div class="flex items-center flex-row py-1">
                            <div class="mr-2 text-primary-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="">Delete</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php /*
            {{-- Add by GDS modal  --}}
        <div class="">
            {{-- Modal --}}
            <div class="fixed left-1/3 top-28 bg-white w-1/3 border border-gray-200 rounded-2xl z-40" @click.away="showmodal = false">
                {{-- Modal title --}}
                <div class="px-7 pt-7 flex flex-row items-center">
                    <div class="p-7 shadow-lg bg-primary-500 rounded-lg mr-2">
                        <img src="{{ ucasset('img/metrics-GDS_picto.svg') }}" alt="Metrics" class="w-8">
                    </div>
                    <div class="text-3xl ml-6 leading-10 font-semibold">Add a new Google Data Studio dashboard</div>
                </div>
                {{-- Modal content --}}
                <div class="p-7">
                    <div class="mb-2">Name your dashboard</div>
                    <input type="text" class="h-12 p-4 w-full bg-gray-100 border border-gray-200 rounded">

                    <div class="flex flex-row items-center mt-7 mb-4">
                        <div class="w-3/12">Limit visibility</div>
                        <div class="w-8/12 border-t border-primary-900"></div>
                        <div class="w-1/12 flex justify-end">
                            <div class="bg-primary-500 rounded-full text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-row my-2" x-data="{search:false}">
                        <div class="flex items-start w-full justify-center">
                            <div class="relative w-full flex items-center">
                                <img src="{{ ucasset('img/search_picto.svg') }}" class="absolute ml-2">
                                <div type="text" class="flex items-center font-normal text-sm h-12 p-4 pl-8 w-full bg-gray-100 border border-gray-200 rounded" contenteditable="true">
                                    {{-- Tags --}}
                                    <div class="ml-2 py-1 pl-2 pr-1 items-center rounded-xl bg-white shadow-sm flex flex-row justify-between">
                                        <div class="">Entête 1</div>
                                        <div class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-2 px-2 py-1 cursor-pointer text-white items-center rounded-xl bg-primary-500 shadow-sm" x-on:click="search=!search">
                                        <div class="">+ Add user or team</div>
                                    </div>
                                </div>
                            </div>
                            {{-- Dropdown --}}
                            <div class="mt-3 text-sm flex flex-col absolute bg-white rounded-md shadow-lg w-36 h-36 p-1" x-show="search">
                                <div class="flex flex-row items-center">
                                    <img src="{{ ucasset('img/search_picto.svg') }}" class="absolute ml-1" width="12%">
                                    <input type="text" class="pl-6 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                                <div class="overflow-auto">
                                    <div class="flex flex-row p-1 items-center">
                                        <div class="text-primary-500 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div class="">Market team</div>
                                    </div>
                                    <div class="flex flex-row p-1 items-center">
                                        <div class="text-primary-500 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="">John doe 1</div>
                                    </div>
                                    <div class="flex flex-row p-1 items-center">
                                        <div class="text-primary-500 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="">John doe 1</div>
                                    </div>
                                    <div class="flex flex-row p-1 items-center">
                                        <div class="text-primary-500 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="">John doe 1</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 mt-1 mb-2"></div>
                <div class="p-7">
                    <div class="">
                        <div class="">Insert <a href="https://funnel.io/marketing-reporting-google-data-studio?utm_source=google&utm_medium=cpc&utm_campaign=g_eu_generic&utm_term=datastudio-eu">Google Data Studio</a></div>
                        <div class="">
                            <input type="text" class="h-12 p-4 w-full bg-gray-100 border border-gray-200 rounded" value="http://googledatastudio">
                        </div>
                    </div>
                    <div class="flex flex-row items-center justify-between mt-8">
                        <div class="flex flex-row items-center py-3 px-8 cursor-pointer text-primary-900 border border-primary-900 rounded-md">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="ml-2">Annuler</div>
                        </div>
                        <div class="flex flex-row items-center py-3 px-8 cursor-pointer bg-primary-500 text-white rounded-md">
                            Display dashboard
                        </div>
                    </div>
                </div>
            </div>
            {{-- Overlay --}}
            <div class="bg-primary-900 bg-opacity-50 z-10 fixed top-0 bottom-0 right-0 left-0"></div>
        </div> */ ?>

    </div>
@endsection
