@extends('uccello::layouts.uccello')

@section('title', 'List')

@section('content')
<div class="p-8">
    <div class="mb-4 flex flex-row items-center justify-between">
        {{-- Title --}}
        <div class="flex flex-row items-center">
            <h1 class="text-primary-900 text-3xl font-semibold">{{ strtoupper($module->name) }}</h1>
        </div>
        <div class="flex flex-row items-center">
            <div class="cursor-pointer bg-white p-2 rounded-lg shadow-sm flex items-center">
                <img src="{{ ucasset('img/download_picto.svg') }}">
            </div>
            {{-- Grid --}}
            <div class="text-sm relative" x-data="{dropdown:false}">
                <div class="cursor-pointer bg-white p-2 rounded-lg shadow-sm ml-4 flex items-center" x-on:click="dropdown=!dropdown">
                    <img src="{{ ucasset('img/grid_picto.svg') }}">
                </div>
                {{-- Dropdown --}}
                <div class="z-20 w-44 shadow-sm bg-white absolute top-12 left-3 border border-gray-200 rounded-md" x-show="dropdown" @click.away="dropdown = false">
                    <div class="flex flex-col p-3">
                        <div class="flex items-center flex-row justify-between py-1 px-2 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Date</div>
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between py-1 px-2 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Ordre du jour</div>
                            <div class="text-primary-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between py-1 px-2 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Participants</div>
                            <div class="text-primary-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between py-1 px-2 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Assigné à</div>
                            <div class="text-primary-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between py-1 px-2 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Compte rendu</div>
                            <div class="text-primary-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between py-1 px-2 mb-2">
                            <div class="">Compte rendu</div>
                            <div class="text-primary-900 text-opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between py-1 px-2 mb-2">
                            <div class="">Création</div>
                            <div class="text-primary-900 text-opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between py-1 px-2 mb-2">
                            <div class="">Mise à jour</div>
                            <div class="text-primary-900 text-opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Filter --}}
            <div class="text-sm relative" x-data="{dropdown:false}">
                <div class="cursor-pointer bg-white p-3 rounded-lg shadow-sm ml-4 flex items-center justify-end" x-on:click="dropdown=!dropdown">
                    <img src="{{ ucasset('img/filter-blue_img.png') }}">
                </div>
                {{-- Dropdown --}}
                <div class="z-20 w-44 shadow-sm bg-white absolute top-12 left-3 border border-gray-200 rounded-md" x-show="dropdown" @click.away="dropdown = false">
                    <div class="flex flex-col p-3">
                        <div class="flex items-center flex-row justify-between py-1 px-2 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Filter 1</div>
                        </div>
                        <div class="flex items-center flex-row justify-between py-1 px-2 mb-2 bg-gray-100 rounded-lg">
                            <div class="">Filter 2</div>
                        </div>
                        <div class="flex items-center flex-row justify-between py-1 px-2 bg-gray-100 rounded-lg">
                            <div class="">Filter 3</div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200" x-data="{modale:false}">
                        <div class="p-2 flex flex-col cursor-pointer" x-on:click="modale=!modale">
                            + Add a filter
                        </div>
                        <div class="" x-show="modale">
                            {{-- Modal --}}
                            <div class="z-30 fixed top-24 left-1/3 w-1/3 bg-white border border-gray-200 rounded-lg"@click.away="modale = false">
                                {{-- Modal title --}}
                                <div class="px-7 pt-7 flex flex-row items-center">
                                    <div class="p-7 shadow-lg bg-primary-500 rounded-lg mr-2">
                                        <img src="{{ ucasset('img/metrics-GDS_picto.svg') }}" alt="Metrics" class="w-8">
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="text-3xl ml-6 leading-10 font-semibold">Ajouter un filtre</div>
                                        <div class="text-sm ml-6 font-semibold">Vous pouvez créer un nouveau filtre à partir de la configuration actuelle de la liste.</div>
                                    </div>
                                </div>
                                {{-- Modal content --}}
                                <div class="px-7 pt-7">
                                    <div class="mb">
                                        <div class="mb-2">Name your filter</div>
                                        <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
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
                                    <div class="p-7 flex flex-row items-center justify-between">
                                        <div class="flex flex-row items-center py-3 px-8 cursor-pointer text-primary-900 border border-primary-900 rounded-md"  x-on:click="modale=!modale">
                                            Annuler
                                        </div>
                                        <div class="flex flex-row items-center py-3 px-8 cursor-pointer bg-primary-500 text-white rounded-md">
                                            Sauvegarder
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                             {{-- Overlay --}}
                            <div class="z-10 bg-primary-900 bg-opacity-50 fixed top-0 bottom-0 right-0 left-0"></div>   
                        </div>
                    </div>
                </div>
            </div>
            <div class="cursor-pointer bg-primary-500 text-white p-2 rounded-lg shadow-sm ml-4 flex items-center">
                + Add Member
            </div>
        </div>
    </div>
    {{-- Table --}}
    <div  class="w-full overflow-auto  scrollbar-thin scrollbar-thumb-rounded-full scrollbar-track-rounded-full scrollbar-thumb-gray-300 scrollbar-track-gray-200 bg-white border border-gray-200 rounded-lg px-7" style="height: calc(100vh - 200px)">
        <livewire:uc-datatable :workspace="$workspace" :module="$module"/>
    </div>
    
</div>
@endsection
