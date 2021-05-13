@extends('uccello::layouts.uccello')

@section('title', 'List')

@section('content')
<div class="p-8">
    <div class="flex flex-row items-center justify-between mb-4">
        {{-- Title --}}
        <div class="flex flex-row items-center">
            <h1 class="text-3xl font-semibold text-primary-900">{{ strtoupper($module->name) }}</h1>
        </div>
        <div class="flex flex-row items-center">
            <div class="flex items-center p-2 bg-white rounded-lg shadow-sm cursor-pointer">
                <img src="{{ ucasset('img/download_picto.svg') }}">
            </div>

            {{-- Visible fields --}}
            <livewire:uc-datatable-columns :workspace="$workspace" :module="$module"/>

            {{-- Filter --}}
            <div class="relative text-sm" x-data="{dropdown:false}">
                <div class="flex items-center justify-end p-3 ml-4 bg-white rounded-lg shadow-sm cursor-pointer" x-on:click="dropdown=!dropdown">
                    <img src="{{ ucasset('img/filter-blue_img.png') }}">
                </div>
                {{-- Dropdown --}}
                <div class="absolute z-20 bg-white border border-gray-200 rounded-md shadow-sm w-44 top-12 left-3" x-show="dropdown" @click.away="dropdown = false">
                    <div class="flex flex-col p-3">
                        <div class="flex flex-row items-center justify-between px-2 py-1 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Filter 1</div>
                        </div>
                        <div class="flex flex-row items-center justify-between px-2 py-1 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Filter 2</div>
                        </div>
                        <div class="flex flex-row items-center justify-between px-2 py-1 bg-gray-100 rounded-lg">
                            <div class="">Filter 3</div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200" x-data="{modale:false}">
                        <div class="flex flex-col p-2 cursor-pointer" x-on:click="modale=!modale">
                            + Add a filter
                        </div>
                        <div class="" x-show="modale">
                            {{-- Modal --}}
                            <div class="fixed z-30 w-1/3 bg-white border border-gray-200 rounded-lg top-24 left-1/3"@click.away="modale = false">
                                {{-- Modal title --}}
                                <div class="flex flex-row items-center px-7 pt-7">
                                    <div class="mr-2 rounded-lg shadow-lg p-7 bg-primary-500">
                                        <img src="{{ ucasset('img/metrics-GDS_picto.svg') }}" alt="Metrics" class="w-8">
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="ml-6 text-3xl font-semibold leading-10">Ajouter un filtre</div>
                                        <div class="ml-6 text-sm font-semibold">Vous pouvez créer un nouveau filtre à partir de la configuration actuelle de la liste.</div>
                                    </div>
                                </div>
                                {{-- Modal content --}}
                                <div class="px-7 pt-7">
                                    <div class="mb">
                                        <div class="mb-2">Name your filter</div>
                                        <input type="text" class="w-full h-10 p-4 bg-gray-100 border border-gray-200 rounded">
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex flex-row items-center py-3">
                                            <input type="checkbox" class="mr-2">
                                            <div class="">Sauvegarder les colonnes affichées</div>
                                        </div>
                                        <div class="flex flex-row items-center py-3">
                                            <input type="checkbox" class="mr-2">
                                            <div class="">Sauvegarder les conditions de recherche</div>
                                        </div>
                                        <div class="flex flex-row items-center py-3">
                                            <input type="checkbox" class="mr-2">
                                            <div class="">Sauvegarder l’ordre de tri</div>
                                        </div>
                                        <div class="flex flex-row items-center py-3">
                                            <input type="checkbox" class="mr-2">
                                            <div class="">Sauvegarder le nombre de lignes affichées</div>
                                        </div>
                                        <div class="flex flex-row items-center py-3">
                                            <input type="checkbox" class="mr-2">
                                            <div class="">Partager le filtre avec les autres utilisateurs</div>
                                        </div>
                                        <div class="flex flex-row items-center py-3">
                                            <input type="checkbox" class="mr-2">
                                            <div class="">Appliquer ce filtre par défaut</div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Buttons --}}
                                <div class="border-t border-gray-200">
                                    <div class="flex flex-row items-center justify-between p-7">
                                        <div class="flex flex-row items-center px-8 py-3 border rounded-md cursor-pointer text-primary-900 border-primary-900"  x-on:click="modale=!modale">
                                            Annuler
                                        </div>
                                        <div class="flex flex-row items-center px-8 py-3 text-white rounded-md cursor-pointer bg-primary-500">
                                            Sauvegarder
                                        </div>
                                    </div>
                                </div>

                            </div>
                             {{-- Overlay --}}
                            <div class="fixed top-0 bottom-0 left-0 right-0 z-10 bg-opacity-50 bg-primary-900"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center p-2 ml-4 text-white rounded-lg shadow-sm cursor-pointer bg-primary-500">
                + Add Member
            </div>
        </div>
    </div>
    {{-- Table --}}
    <div  class="w-full overflow-auto bg-white border border-gray-200 rounded-lg scrollbar-thin scrollbar-thumb-rounded-full scrollbar-track-rounded-full scrollbar-thumb-gray-300 scrollbar-track-gray-200 px-7" style="height: calc(100vh - 200px)">
        <livewire:uc-datatable :workspace="$workspace" :module="$module"/>
    </div>

</div>
@endsection
