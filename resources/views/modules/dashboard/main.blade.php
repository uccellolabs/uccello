@extends('uccello::layouts.uccello')

@section('content')
    {{-- Content --}}
    <main class="p-8">
        {{-- Dashboard --}}
        {{-- Dashboard title --}}
        <div class="flex flex-row items-center">
            <div class="text-primary-900 text-3xl font-semibold">DASHBOARD</div>
        </div>
        
        {{-- Dashboards content --}}
        <div class="mt-8 p-8 z-0 flex items-center justify-center bg-white rounded-lg border border-gray-200" x-data="{open:false}">
            {{-- Default bg --}}
            <img src="{{ ucasset('img/dashboard-bg_img.svg') }}">

            {{-- Button add a dashbaord --}}
            <div class="absolute z-10 flex items-center bg-primary-500 rounded-lg cursor-pointer p-3 text-white" x-on:click="open=!open">
                <div class="bg-primary-500 shadow-lg rounded">
                    <img class="w-10/12" src="{{ ucasset('img/metrics.png') }}">
                </div>
                <div class="ml-2">+ Add a dashboard</div>
            </div>
            {{-- Add by GDS modal  --}}
            <div class="" x-show="open">
                {{-- Modal --}}
                <div class="fixed left-1/3 top-28 bg-white w-1/3 border border-gray-200 rounded-2xl z-40">
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

                        <div class="flex flex-row mb-2">
                            <div class="flex w-4/12">Workspace</div>
                            <div class="flex w-7/12 ml-1">Rôles</div>
                        </div>

                        <div class="flex flex-row my-2">
                            <div class="p-2 h-10 w-4/12 flex flex-row items-center justify-between bg-gray-100 border border-gray-200 rounded">
                                <div class="">Market team</div>
                                <div class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <div contenteditable="true" class="flex h-10 w-7/12 ml-2 p-2 flex-row items-center bg-gray-100 border border-gray-200 rounded">
                                Market team
                            </div>
                            <div class="flex w-1/12 ml-2 items-center">
                                <div class="">
                                    <div class="p-2 bg-primary-900 text-white rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 cursor-pointer bg-primary-900 text-white rounded text-center">
                            + Invite on another workspace
                        </div>
                    </div>
                    <div class="border-t border-gray-200 my-2"></div>
                    <div class="p-7">
                        <div class="">
                            <div class="">Insert <a href="https://funnel.io/marketing-reporting-google-data-studio?utm_source=google&utm_medium=cpc&utm_campaign=g_eu_generic&utm_term=datastudio-eu">Google Data Studio</a></div>
                            <div class="">
                                <input type="text" class="h-12 p-4 w-full bg-gray-100 border border-gray-200 rounded" value="http://googledatastudio">
                            </div>
                        </div>
                        <div class="flex flex-row items-center justify-between mt-8">
                            <div class="flex flex-row items-center py-3 px-8 cursor-pointer text-primary-900 border border-primary-900 rounded-md"  x-on:click="open=!open">
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
            </div>
        </div>
        
    </main>
@endsection
