@extends('uccello::layouts.uccello')

@section('content')

<div>
    <div class="p-8">
        {{-- Name your module --}}
        <div class="bg-white border border-gray-200 rounded-xl w-7/12 m-auto">
            {{-- Step title --}}
            <div class="border-b border-gray-200">
                <div class="py-4 px-7 flex justify-between items-center">
                    <div class="text-2xl font-semibold">Name your module</div>
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </div>
                </div>
            </div>
            {{-- Step content --}}
            <div class="border-b border-gray-200">
                <div class="p-7">
                    <div class="flex flex-row justify-start whitespace-nowrap w-full">
                        <div class="flex flex-col w-1/12">
                            <div class="mb-2">Icon</div>
                            <div class="flex flex-row items-center">
                                <div class="bg-gray-100 p-1 rounded-full border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div class="">|</div>
                            </div>
                        </div>
                        <div class="flex flex-col w-3/12 ml-6">
                            <div class="mb-2">Nom du module (pluriel)</div>
                            <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded" placeholder="Nom module">
                        </div>
                        <div class="flex flex-col w-3/12 ml-6">
                            <div class="mb-2">Nom du module (singulier)</div>
                            <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded" placeholder="Nom module">
                        </div>
                        <div class="flex flex-col w-3/12 ml-6 relative" x-data="{dropdown:false}">
                            <div class="mb-2">Category</div>
                            <div class="flex flex-row items-center relative" x-on:click="dropdown=!dropdown">
                                <div class="absolute">
                                    <img width="80%" class="pl-2" src="{{ ucasset('img/search_picto.svg') }}">
                                </div>
                                <input type="text" class="h-10 p-4 pl-6 w-full bg-gray-100 border border-gray-200 rounded" placeholder="Nom module">
                            </div>
                            {{-- Dropdown --}}
                            <div class="top-0 left-0 right-0 w-full z-20 shadow-lg border border-gray-200 rounded-b-lg bg-white" x-show="dropdown" @click.away="dropdown = false">
                                <div class="ml-6">
                                    <div class="p-2">Category 1</div>
                                    <div class="p-2">Category 1</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row justify-start whitespace-nowrap w-full mt-4">
                        <div class="w-1/12">
                        </div>
                        <div class="flex flex-col w-6/12 ml-6">
                            <div class="mb-2">Slug</div>
                            <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded" placeholder="table-name">
                        </div>
                        <div class="w-3/12"></div>
                    </div>
                </div>
            </div>
            {{-- Next step --}}
            <div class="">
                <div class="py-3 px-7 flex justify-between items-center">
                    <div class="text-2xl font-semibold">Advanced configuration</div>
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="mt-10"></div>
    
        {{-- Advanced configuration --}}
        <div class="bg-white border border-gray-200 rounded-xl w-7/12 m-auto">
            {{-- Step title --}}
            <div class="border-b border-gray-200">
                <div class="py-4 px-7 flex justify-between items-center">
                    <div class="text-2xl font-semibold">Advanced configuration</div>
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </div>
                </div>
            </div>
            {{-- Step content --}}
            <div class="border-b border-gray-200">
                <div class="p-7">
                    <div class="mb-2">
                        <div class="">Module d'administration</div>
                    </div>
                    <div class="flex flex-row mb-3">
                        <div class="flex items-center" x-data="{toggle: '0'}">
                            <div class="flex items-center relative rounded-full w-10 h-5 transition duration-200 ease-linear border-2 border-primary-900">
                              <label for="toggle"
                                    class="absolute left-1 bg-primary-900 w-3 h-3 rounded-full transition transform duration-100 ease-linear cursor-pointer"
                                    :class="[toggle === '1' ? 'translate-x-4' : 'translate-x-0']"></label>
                              <input type="checkbox" id="toggle" name="toggle"
                                    class="appearance-none w-full h-full active:outline-none focus:outline-none"
                                    x-on:click="toggle === '0' ? toggle = '1' : toggle = '0'"/>
                            </div>
                        </div>
                        <div class="ml-2">Oui</div>
                    </div>
                    <div class="" x-data="{choice:1}">
                        <div class="mb-2">Qui aura accès aux données de ce module ?</div>
                        <div class="flex flex-col">
                            <div class="flex flex-row items-start border border-gray-200 rounded-md p-3" :class="{'border-primary-500 bg-blue-50':choice===1}">
                                <div class="mr-3">
                                    <input class="border-primary-900 checked:bg-primary-900" type="radio" name="choice" id="public" checked x-on:click="choice=1">
                                </div>
                                <div class="flex flex-col">
                                    <div class="font-semibold">Public</div>
                                    <div class="">Tous les utilisateurs ayant accès à ce module dans ce workspace pourront voir kes données</div>
                                </div>
                            </div>
                            <div class="flex flex-row items-start border border-gray-200 rounded-md p-3 mt-2" :class="{'border-primary-500 bg-blue-50':choice===2}">
                                <div class="mr-3">
                                    <input class="border-primary-900 checked:bg-primary-900" type="radio" name="choice" id="private" x-on:click="choice=2">
                                </div>
                                <div class="flex flex-col">
                                    <div class="font-semibold">Privé</div>
                                    <div class="">- Ajout d'un champ "Assigné à" dans vos colonnes</div>
                                    <div class="">- Aront accès aux données : les administrateurs, les supérieurs hiérarchiques et tous users ou teams assignés.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Next step --}}
            <div class="">
                
            </div>
    
        </div>

        <div class="mt-10"></div>

        {{-- Columns configuration --}}
        <div class="bg-white border border-gray-200 rounded-xl w-7/12 m-auto">
            {{-- Step title --}}
            <div class="border-b border-gray-200">
                <div class="py-4 px-7 flex justify-between items-center">
                    <div class="text-2xl font-semibold">Columns configuration</div>
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </div>
                </div>
            </div>
            {{-- Step content --}}
            <div class="border-b border-gray-200">
                <div class="p-7">
                    <div class="mb-8" x-data="{open:false}">
                        <div class="flex flex-row items-center my-2">
                            <div class="w-2/12 flex flex-row items-center justify-between">
                                <div class="">
                                    <div class="p-1 bg-pink-400 rounded-full"></div>
                                </div>
                                <div class="">Entête 1</div>
                                <div class="mr-2">
                                    <img width=70% src="{{ ucasset('img/pen-icon_picto.svg') }}">
                                </div>
                            </div>
                            <div class="w-9/12 border-t border-primary-900"></div>
                            <div class="w-1/12 flex justify-end">
                                <div class="bg-primary-500 rounded-full text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between">
                            <div class="w-4/12 flex flex-col" x-data="{dropdown:false}">
                                <div class="mb-1">Type de champ</div>
                                <div class="bg-gray-100 border border-gray-200 rounded-md p-2">
                                    <div class="flex flex-row justify-between items-center cursor-pointer" x-on:click="dropdown=!dropdown">
                                        <div class="flex flex-row items-center">
                                            <div class="mr-1">
                                                <img width=70% src="{{ ucasset('img/type-group_picto.svg') }}">
                                            </div>
                                            <div class="">Champ lié</div>
                                        </div>
                                        <div class=""><img width=70% src="{{ ucasset('img/triangle_picto.svg') }}"></div>
                                    </div>
                                    {{--  Dropdown --}}
                                    <div class="" x-show="dropdown" @click.away="dropdown = false">
                                        <div class="flex flex-row py-2">
                                            <div class="mr-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h7" />
                                                </svg>
                                            </div>
                                            <div class="">Texte simple</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-4/12 flex flex-col">
                                <div class="mb-1">Nom système</div>
                                <input type="text" class="w-full bg-gray-100 border border-gray-200 rounded-md p-2">
                            </div>
                            <div class="w-2/12 flex flex-col">
                                <div class="mb-3">Obligatoire</div>
                                <div class="flex flex-row">
                                    <div class="flex items-center" x-data="{toggle2: '0'}">
                                        <div class="flex items-center relative rounded-full w-10 h-5 transition duration-200 ease-linear border-2 border-primary-900">
                                          <label for="toggle2"
                                                class="absolute left-1 bg-primary-900 w-3 h-3 rounded-full transition transform duration-100 ease-linear cursor-pointer"
                                                :class="[toggle2 === '1' ? 'translate-x-4' : 'translate-x-0']"></label>
                                          <input type="checkbox" id="toggle2" name="toggle2"
                                                class="appearance-none w-full h-full active:outline-none focus:outline-none"
                                                x-on:click="toggle2 === '0' ? toggle2 = '1' : toggle2 = '0'"/>
                                        </div>
                                    </div>
                                    <div class="ml-2">Oui</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="bg-primary-500 bg-opacity-10 rounded-lg">
                                <div class="p-5">
                                    <div class="flex flex-row justify-between">
                                        <div class="flex flex-col w-4/12">
                                            <div class="mb-1">Module lié</div>
                                            <div class="flex flex-row items-center relative">
                                                <img width="13%" class="absolute pl-2" src="{{ ucasset('img/modal-group_picto.svg') }}">
                                                <input type="text" class="w-full bg-gray-100 border border-primary-500 rounded-md pl-8 p-2">
                                            </div>
                                        </div>
                                        <div class="flex flex-col w-4/12">
                                            <div class="mb-1">Libellé du champ</div>
                                            <input type="text" class="w-full bg-gray-100 border border-primary-500 rounded-md pl-3 p-2">
                                        </div>
                                        <div class="flex flex-col w-1/12">
                                            <div class="mb-1">Afficher</div>
                                            <div class="mt-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex w-1/12 items-end">
                                            <div class="">
                                                <div class="p-2 bg-primary-900 text-white rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="p-2 bg-primary-900 rounded-md text-center text-white cursor-pointer">
                                            + Lier à un nouveau champ
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-8" x-data="{open:false}">
                        <div class="flex flex-row items-center my-2">
                            <div class="w-2/12 flex flex-row items-center justify-between">
                                <div class="">
                                    <div class="p-1 bg-green-400 rounded-full"></div>
                                </div>
                                <div class="">Entête 2</div>
                                <div class="mr-2">
                                    <img width=70% src="{{ ucasset('img/pen-icon_picto.svg') }}">
                                </div>
                            </div>
                            <div class="w-9/12 border-t border-primary-900"></div>
                            <div class="w-1/12 flex justify-end">
                                <div class="bg-primary-500 rounded-full text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row justify-between">
                            <div class="w-4/12 flex flex-col" x-data="{dropdown:false}">
                                <div class="mb-1">Type de champ</div>
                                <div class="bg-gray-100 border border-gray-200 rounded-md p-2">
                                    <div class="flex flex-row justify-between items-center cursor-pointer" x-on:click="dropdown=!dropdown">
                                        <div class="flex flex-row items-center">
                                            <div class="mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h7" />
                                                </svg>
                                            </div>
                                            <div class="">Texte simple</div>
                                        </div>
                                        <div class=""><img width=70% src="{{ ucasset('img/triangle_picto.svg') }}"></div>
                                    </div>
                                    {{--  Dropdown --}}
                                    <div class="" x-show="dropdown" @click.away="dropdown = false">
                                        <div class="flex flex-row py-2">
                                            <div class="mr-2">
                                                <img width=70% src="{{ ucasset('img/type-group_picto.svg') }}">
                                            </div>
                                            <div class="">Champ lié</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-4/12 flex flex-col">
                                <div class="mb-1">Nom système</div>
                                <input type="text" class="w-full bg-gray-100 border border-gray-200 rounded-md p-2">
                            </div>
                            <div class="w-2/12 flex flex-col">
                                <div class="mb-3">Obligatoire</div>
                                <div class="flex flex-row">
                                    <div class="flex items-center" x-data="{toggle2: '0'}">
                                        <div class="flex items-center relative rounded-full w-10 h-5 transition duration-200 ease-linear border-2 border-primary-900">
                                          <label for="toggle2"
                                                class="absolute left-1 bg-primary-900 w-3 h-3 rounded-full transition transform duration-100 ease-linear cursor-pointer"
                                                :class="[toggle2 === '1' ? 'translate-x-4' : 'translate-x-0']"></label>
                                          <input type="checkbox" id="toggle2" name="toggle2"
                                                class="appearance-none w-full h-full active:outline-none focus:outline-none"
                                                x-on:click="toggle2 === '0' ? toggle2 = '1' : toggle2 = '0'"/>
                                        </div>
                                    </div>
                                    <div class="ml-2">Oui</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg mt-6 cursor-pointer">
                            <div class="flex items-center justify-center p-1" x-on:click="open=!open">
                                <div class="">Paramètres avancés</div>
                                <div class="ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="&" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            {{-- Open advanced settings --}}
                            <div class="p-1" x-show="open">
                                <div class="">
                                    <div class="flex flex-row items-center">
                                        <div class="w-2/4">
                                            Les données de cette colonne seront affichées
                                        </div>
                                        <div class="w-1/3 bg-gray-100 border border-gray-200 rounded-md p-2" x-data="{choice:false}">
                                            <div class="flex flex-row justify-between items-center cursor-pointer" x-on:click="choice=!choice">
                                                <div class="flex flex-row items-center">
                                                    <div class="">Champ lié</div>
                                                </div>
                                                <div class=""><img width=70% src="{{ ucasset('img/triangle_picto.svg') }}"></div>
                                            </div>
                                            {{--  Dropdown --}}
                                            <div class="" x-show="choice" @click.away="choice = false">
                                                <div class="flex flex-row py-2">
                                                    <div class="">Texte simple</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between">
                                    <div class="flex flex-col">
                                        <div class="flex flex-row py-2 items-center">
                                            <div class="mr-1"><input type="checkbox" class="form-checkbox rounded-full h-4 w-4 text-green-600" checked></div>
                                            <div class="">Vue display</div>
                                        </div>
                                        <div class="flex flex-row py-2 items-center">
                                            <div class="mr-1"><input type="checkbox" class="form-checkbox rounded-full h-4 w-4 text-green-600" checked></div>
                                            <div class="">Vue display</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex flex-row py-2 items-center">
                                            <div class="mr-1"><input type="checkbox" class="form-checkbox rounded-full h-4 w-4 text-green-600" checked></div>
                                            <div class="">Vue display</div>
                                        </div>
                                        <div class="flex flex-row py-2 items-center">
                                            <div class="mr-1"><input type="checkbox" class="form-checkbox rounded-full h-4 w-4 text-green-600" checked></div>
                                            <div class="">Vue display</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex flex-row py-2 items-center">
                                            <div class="mr-1"><input type="checkbox" class="form-checkbox rounded-full h-4 w-4 text-green-600" checked></div>
                                            <div class="">Vue display</div>
                                        </div>
                                        <div class="flex flex-row py-2 items-center">
                                            <div class="mr-1"><input type="checkbox" class="form-checkbox rounded-full h-4 w-4 text-green-600" checked></div>
                                            <div class="">Vue display</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <div class="flex flex-row py-2 items-center">
                                            <div class="mr-1"><input type="checkbox" class="form-checkbox rounded-full h-4 w-4 text-green-600" checked></div>
                                            <div class="">Vue display</div>
                                        </div>
                                        <div class="flex flex-row py-2 items-center">
                                            <div class="mr-1"><input type="checkbox" class="form-checkbox rounded-full h-4 w-4 text-green-600" checked></div>
                                            <div class="">Vue display</div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- Next step --}}
            <div class="">
            </div>
        </div>

        {{-- Create a new link n*n --}}
        <div class="bg-white border border-gray-200 rounded-xl w-7/12 m-auto">
            {{-- Step title --}}
            <div class="border-b border-gray-200">
                <div class="py-4 px-7 flex justify-between items-center">
                    <div class="text-2xl font-semibold">Create a new link n*n</div>
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </div>
                </div>
            </div>
            {{-- Step content --}}
            <div class="border-b border-gray-200">
                <div class="p-7">
                    <div class="flex flex-row justify-between">
                        <div class="flex flex-col w-4/12">
                            <div class="mb-1">Module lié</div>
                            <div class="flex flex-row items-center relative">
                                <img width="13%" class="absolute pl-2" src="{{ ucasset('img/modal-group_picto.svg') }}">
                                <input type="text" class="w-full bg-gray-100 border border-primary-500 rounded-md pl-8 p-2">
                            </div>
                        </div>
                        <div class="flex flex-col w-4/12">
                            <div class="mb-1">Libellé du champ</div>
                            <input type="text" class="w-full bg-gray-100 border border-primary-500 rounded-md pl-3 p-2">
                        </div>
                        <div class="flex flex-col w-1/12">
                            <div class="mb-1">Afficher</div>
                            <div class="mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex w-1/12 items-end">
                            <div class="">
                                <div class="p-2 bg-primary-900 text-white rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="p-2 bg-primary-900 rounded-md text-center text-white cursor-pointer">
                            + Lier à un nouveau champ
                        </div>
                    </div>
                </div>
            </div>
            {{-- Next step --}}
            <div class="">
                
            </div>
    
        </div>
        {{-- Create a new link n*n --}}
        <div class="bg-white border border-gray-200 rounded-xl w-7/12 m-auto">
            {{-- Step title --}}
            <div class="border-b border-gray-200">
                <div class="py-4 px-7 flex justify-between items-center">
                    <div class="text-2xl font-semibold">Details vue configuration</div>
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </div>
                </div>
            </div>
            {{-- Step content --}}
            <div class="border-b border-gray-200">
                <div class="p-7">
                    <div class="border rounded-lg border-gray-200 shadow-md">
                        <div class="p-2">
                            <div class="items-center flex flex-row justify-between">
                                <div class="flex flex-row items-center">
                                    <div class="p-2 text-primary-900 bg-gray-100 border border-gray-200 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <img width=70% src="{{ ucasset('img/triangle_picto.svg') }}">
                                    </div>
                                    <div class="ml-4">
                                        Titre bloc 1 Vue détaillée
                                    </div>
                                </div>
                                <div class="flex flex-row items-center">
                                    <div class="">
                                        <img width=70% src="{{ ucasset('img/6dot-menu_picto.svg') }}">
                                    </div>
                                    <div class="ml-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row mt-5 justify-between">
                                <div class="w-1/2 mr-2 bg-gray-100 p-2 rounded-md flex flex-row justify-between">
                                    <div class="flex flex-row items-center">
                                        <div class="mr-2"><div class="bg-green-400 rounded-full p-1"></div></div>
                                        <div class="">Entête 2</div>
                                    </div>
                                    <div class="flex flex-row items-center">
                                        <div class="">
                                            <img class="" src="{{ ucasset('img/ext-arrows_picto.svg') }}">
                                        </div>
                                        <div class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-1/2 ml-2 bg-gray-100 p-2 rounded-md flex flex-row justify-between">
                                    <div class="flex flex-row items-center">
                                        <div class="mr-2"><div class="bg-purple-400 rounded-full p-1"></div></div>
                                        <div class="">Entête 2</div>
                                    </div>
                                    <div class="flex flex-row items-center">
                                        <div class="">
                                            <img class="" src="{{ ucasset('img/ext-arrows_picto.svg') }}">
                                        </div>
                                        <div class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row mt-3 justify-between">
                                <div class="w-full mr-2 bg-gray-100 p-2 rounded-md flex flex-row justify-between">
                                    <div class="flex flex-row items-center">
                                        <div class="mr-2"><div class="bg-red-400 rounded-full p-1"></div></div>
                                        <div class="">Entête 3</div>
                                    </div>
                                    <div class="flex flex-row items-center">
                                        <div class="">
                                            <img width="90%" src="{{ ucasset('img/in-arrows_picto.svg') }}">
                                        </div>
                                        <div class="ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row mt-3 text-primary-500 cursor-pointer">
                                <div class="py-1 m-auto px-3 border border-primary-500 rounded-lg flex flex-row items-center">
                                    <div class="mr-2">Insérez un champs</div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 border-2 border-dashed border-gray-200 rounded-lg">
                        <div class="p-2">
                            <div class="flex">
                                <div class="m-auto py-1 px-3 bg-primary-500 rounded-md text-white">Ajoutez un bloc +</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Next step --}}
            <div class="">
                
            </div>
    
        </div>
    </div>
</div>

@endsection

