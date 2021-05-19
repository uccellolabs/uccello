@extends('uccello::layouts.uccello')

@section('content')
    {{-- Content --}}
    <div class="overflow-auto scrollbar-thin scrollbar-thumb-rounded-full scrollbar-track-rounded-full scrollbar-thumb-gray-300 scrollbar-track-gray-200" style="height: calc(100vh - 70px)">
        <div class="py-8 px-16">
            {{-- Settings content --}}
            <div x-data="{open:1}">
                {{-- Navigation --}}
                <div class="flex flex-row items-center text-gray-400">
                    <div class="p-4 ml-6 cursor-pointer" x-on:click="open=1" :class="{'text-primary-900 border-b-2 border-primary-900 ':open===1}">Profile</div>
                    <div class="p-4 ml-6 cursor-pointer" x-on:click="open=2" :class="{'text-primary-900 border-b-2 border-primary-900 ':open===2}">Paiment method</div>
                    <div class="p-4 ml-6 cursor-pointer" x-on:click="open=3" :class="{'text-primary-900 border-b-2 border-primary-900 ':open===3}">Purchases</div>
                    <div class="p-4 ml-6 cursor-pointer" x-on:click="open=4" :class="{'text-primary-900 border-b-2 border-primary-900 ':open===4}">Security</div>
                </div>
    
                {{-- Default bg --}}
                <div class="bg-white border-gray-200 rounded-xl border">
                    {{-- Tabs --}}
                    {{-- Tab 1 --}}
                    <div class="" x-show="open===1">
                        {{-- Profile informations --}}
                        <div class="p-7 border-b border-gray-200">
                            <div class="flex flex-row items-center">
                                <div class="p-12 mr-4 rounded-2xl bg-green-300"></div>
                                <div class="flex flex-col leading-4 cursor-pointer" x-on:click="dropdown=!dropdown" @click.away="dropdown = false">
                                    <div class="text-base">Guillaume carbonneau</div>
                                    <div class="text-sm text-gray-400">carbonneau.guillaume@...</div>
                                </div>
                            </div>
                        </div>
                        {{-- Account informations --}}
                        <div class="px-7 py-4 border-b border-gray-200">
                            <div class="text-lg font-semibold mb-4">Account information</div>
                            <div class="flex flex-row justify-between flex-grow">
                                <div class="flex flex-col w-1/3">
                                    <div class="mb-1">First name</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                                <div class="flex flex-col w-1/3 ml-4">
                                    <div class="mb-1">Last name</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                                <div class="flex flex-col w-1/3 ml-4">
                                    <div class="mb-1">Username</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                            </div>
                        </div>
                        {{-- Physical adress --}}
                        <div class="px-7 py-4 border-b border-gray-200">
                            <div class="text-lg font-semibold mb-4">Physical adress</div>
                            <div class="flex flex-row justify-between flex-grow">
                                <div class="flex flex-col w-1/2">
                                    <div class="mb-1">Country</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                                <div class="flex flex-col w-1/2 ml-4">
                                    <div class="mb-1">City</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                            </div>
                            <div class="flex flex-row mt-2 justify-between flex-grow">
                                <div class="flex flex-col w-1/2">
                                    <div class="mb-1">Adress</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                                <div class="flex flex-col w-1/2 ml-4">
                                    <div class="mb-1">Postal code</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                            </div>
                            <div class="flex flex-row mt-2 justify-between flex-grow">
                                <div class="flex flex-col w-1/2">
                                    <div class="mb-1">Phone numer</div>
                                    <input type="tel" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                                <div class="flex flex-col w-1/2 ml-4">
                                    
                                </div>
                            </div>
                        </div>
                        {{-- Language and currency --}}
                        <div class="px-7 py-4 mb-8">
                            <div class="text-lg font-semibold mb-4">Language and currency</div>
                            <div class="flex flex-row justify-between flex-grow">
                                <div class="flex flex-col w-1/2">
                                    <div class="mb-1">Default language</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                                <div class="flex flex-col w-1/2 ml-4">
                                    <div class="mb-1">Default currency</div>
                                    <input type="text" class="h-10 p-4 w-full bg-gray-100 border border-gray-200 rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Tab 2 --}}
                    <div class="" x-show="open===2">
                        Test2
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection