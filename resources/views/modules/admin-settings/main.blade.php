@extends('uccello::layouts.uccello')

@section('content')
    {{-- Content --}}
    <main class="p-8 mr-12">
        {{-- Admin settings --}}
        {{-- Admin settings title --}}
        <div class="flex flex-row items-center">
            <div class="mr-2"><img src="{{ ucasset('img/settings-icon_picto.svg') }}"></div>
            <div class="text-primary-900 text-3xl font-semibold">ADMIN SETTINGS</div>
        </div>
        
        {{-- Admin settings content --}}
        <div class="mt-8" x-data="{open:true}">
            {{-- Navigation --}}
            <div class="flex flex-row items-center text-gray-400">
                <div class="p-4 ml-6 cursor-pointer" x-on:click="open=!open" :class="{'text-primary-900 border-b-2 border-primary-900 ':open===true}">Users</div>
                <div class="p-4 ml-6" x-on:click="">Mon instance</div>
                <div class="p-4 ml-6" x-on:click="">Mes plans</div>
                <div class="p-4 ml-6" x-on:click="">Mon compte</div>
                <div class="p-4 ml-6" x-on:click="">Mes webhook</div>
                <div class="ml-6 p-4" x-on:click="">
                    <div class="bg-black rounded-full text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Default bg --}}
            <div class="bg-white border border-gray-200 rounded">
                {{-- Sub navigation --}}
                {{-- Users sub --}}
                <div class="flex flex-row justify-between items-center text-primary-900" x-show="open">
                    <div class="flex">
                        <div class="p-4 ml-6" :class="{'border-b-2 border-primary-500 ':open===true}">Mes users</div>
                        <div class="p-4 ml-6">Tous les rôles</div>
                    </div>
                    <div class="p-2" x-data="{add_user:false}">
                        <div class="py-2 px-4 text-white bg-primary-900 rounded cursor-pointer" x-on:click="add_user=!add_user">+ Inviter un user</div>
                        {{-- Pop-up add a user --}}
                        <div class="" x-show="add_user">
                            {{-- Pop-up --}}
                            <div class="fixed left-1/3 top-14 bg-white w-1/3 border border-gray-200 rounded-2xl z-30">
                                {{-- Pop-up title --}}
                                <div class="p-7 text-2xl border-b border-gray-200 font-semibold">Invite user(s)</div>
                                {{-- Pop-up content --}}
                                <div class="p-7">
                                    <div class="">Email</div>
                                    <div class="h-20 max-h-36 overflow-auto bg-gray-100 border border-gray-200 rounded" contenteditable="true"></div>

                                    <div class="flex flex-row my-2">
                                        <div class="flex w-4/12">Workspace</div>
                                        <div class="flex w-7/12 ml-1">Rôles</div>
                                    </div>

                                    <div class="flex flex-row my-2">
                                        <div class="p-2 w-4/12 flex flex-row items-center justify-between bg-gray-100 border border-gray-200 rounded">
                                            <div class="">Uccello</div>
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div contenteditable="true" class="flex overflow-auto h-10 max-h-24 w-7/12 ml-2 p-2 flex-row items-center bg-gray-100 border border-gray-200 rounded">
                                            
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
                                    <div class="flex flex-row my-2">
                                        <div class="p-2 w-4/12 flex flex-row items-center justify-between bg-gray-100 border border-gray-200 rounded">
                                            <div class="">Uccello</div>
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div contenteditable="true" class="flex h-10 max-h-24 overflow-auto w-7/12 ml-2 p-2 flex-row items-center bg-gray-100 border border-gray-200 rounded">
                                            
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

                                    <div class="mt-4 p-2 bg-primary-900 text-white rounded text-center">
                                        + Invite on another workspace
                                    </div>

                                    <div class="flex flex-row items-center justify-between mt-8">
                                        <div class="flex flex-row items-center py-3 px-8 cursor-pointer text-primary-900 border border-primary-900 rounded"  x-on:click="add_user=!add_user">
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                            <div class="ml-2">Annuler</div>
                                        </div>
                                        <div class="flex flex-row items-center py-3 px-8 cursor-pointer bg-primary-500 text-white rounded">
                                            <div class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                </svg>
                                            </div>
                                            <div class="ml-2">Send</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Overlay --}}
                            <div class="bg-primary-900 bg-opacity-50 z-20 fixed top-0 bottom-0 right-0 left-0"></div>
                        </div>
                        
                    </div>
                </div>
                {{-- Mon instance sub --}}
                {{-- Mes plans sub --}}
                {{-- ... --}}

                {{-- Navigation content --}}
                <div class="">

                </div>
            </div>
        </div>
        
    </main>
@endsection
