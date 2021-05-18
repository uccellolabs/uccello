@extends('uccello::layouts.uccello')

@section('content')
<main class="p-8 h-full">
    {{-- Content --}}

        {{-- Buttons --}}
        <div class="flex flex-row justify-end">
            <div class="bg-white shadow-lg rounded-full p-4">Play</div>
            <div class="bg-white shadow-lg rounded-full p-4 ml-6">?</div>
        </div>

        {{-- Add tools from marketplace --}}
        <div class="flex flex-col mt-20 justify-center m-auto bg-white rounded-xl border border-gray-200 w-3/5">
            {{-- Title, button --}}
            <div class="flex flex-row items-center px-12 py-3 justify-between  border-b border-gray-200">
                <div class="text-2xl font-semibold">Ajouter mes outils depuis la marketplace</div>
                <div class="flex flex-row py-3 px-5 rounded-xl items-center bg-orange-500 bg-opacity-10 text-orange-500">
                    <div class="">Marketplace</div>    
                    <div class="ml-3">
                        <img src="{{ ucasset('img/cart_picto.svg') }}">
                    </div>
                </div>
            </div>
            {{-- Input & submit --}}
            <div class="px-12 py-8">
                <div class="flex flex-col">
                    <label class="font-normal">Clé de licence</label>
                    <div class="flex items-center mt-1">
                        <input type="text" name="" placeholder="Rentrer ma clé de licence pour installer mon outil" class="w-11/12 py-3 px-6 rounded-xl bg-gray-100 text-primary-900 border border-gray-200">
                        
                        <div class="w-1/12 flex justify-center">
                            <div class="rounded-full p-1 text-green-500 bg-green-500 bg-opacity-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-8">
                        <input type="submit" name="" value="Installer mon outil" class="py-3 px-7 bg-primary-500 text-white rounded-xl">
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
