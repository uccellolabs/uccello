@extends('uccello::layouts.uccello')

@section('content')
    {{-- Content --}}
    <div class="overflow-auto scrollbar-thin scrollbar-thumb-rounded-full scrollbar-track-rounded-full scrollbar-thumb-gray-300 scrollbar-track-gray-200">
        <div class="p-8">
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
                <div class="bg-white border-gray-200 rounded-xl">
                    {{-- Tabs --}}
                    {{-- Tab 1 --}}
                    <div class="" x-show="open===1">
                        Test1
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