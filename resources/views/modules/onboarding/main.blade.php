@extends('uccello::layouts.uccello')

@section('content')
    <main class="p-8 h-full">
        {{-- Welcome text --}}
        <div class="flex flex-col">
            <div class="flex text-primary-500 text-3xl font-semibold">
                Hi guillaume !
                <img src="{{ ucasset('img/hi-style_picto.svg') }}" alt="Style for Hi User">
            </div>
            <div class="flex text-primary-500 text-3xl font-semibold">
                Let's create your first app
            </div>
            <div class="mt-5 w-2/5">Let’s see how to do your app in several seconds or just install a bundle from the market place</div>
        </div>

        {{-- Modules --}}
        <div class="grid grid-cols-3 gap-10 mt-12">
            <div class="flex flex-col border justify-between border-gray-200 w-full bg-white rounded-lg">
                <div class="p-8">
                    <div class="flex items-center">
                        <div class="mr-5">
                            <img src="{{ ucasset('img/module-icon_picto.svg') }}" alt="Icon du module">
                        </div>
                        <div class="text-primary-500 font-semibold text-xs">
                            MODULE DESIGNER
                        </div>
                    </div>
                    <div class="flex text-2xl font-semibold my-5 leading-7">Create your app from scratch from Module designer</div>
                    <div class="flex">
                        <div class="bg-primary-500 py-2 px-6 font-normal rounded-lg text-white">Let's build from scratch</div>
                    </div>
                </div>
                <div class="flex">
                    <img width="100%"  src="{{ ucasset('img/Module_designer_img.png') }}" alt="Image for module designer ">
                </div>
            </div>
            <div class="flex flex-col border justify-between border-gray-200 w-full bg-white rounded-lg">
                <div class="p-8">
                    <div class="flex items-center">
                        <div class="mr-5">
                            <img src="{{ ucasset('img/cart_picto.svg') }}" alt="Icon of module">
                        </div>
                        <div class="text-orange-500 font-semibold text-xs">
                            MARKETPLACE
                        </div>
                    </div>
                    <div class="flex text-2xl font-semibold leading-7 my-5">Create your app from scratch from Module designer</div>
                    <div class="flex">
                        <div class="bg-orange-500 py-2 px-6 font-normal rounded-lg text-white">Let's build from scratch</div>
                    </div>
                </div>
                <div class="flex">
                    <img width="100%"  src="{{ ucasset('img/Marketplace_img.png') }}" alt="Image for markerplace module ">
                </div>
            </div>
            <div class="flex flex-col border justify-between border-gray-200 bg-white rounded-lg">
                <div class="p-8">
                    <div class="flex items-center">
                        <div class="mr-5">
                            <img src="{{ ucasset('img/metrics_picto.svg') }}" alt="Icon of module">
                        </div>
                        <div class="text-primary-500 font-semibold text-xs">
                            DATA DESIGNER
                        </div>
                    </div>
                    <div class="flex text-2xl font-semibold  leading-7 my-5">Create your app from scratch from Module designer</div>
                    <div class="flex">
                        <div class="bg-primary-500 py-2 px-6 font-normal rounded-lg text-white">Let's build from scratch</div>
                    </div>
                </div>
                <div class="flex">
                    <img width="100%"  src="{{ ucasset('img/Data-designer_img.png') }}" alt="Image for module designer ">
                </div>
            </div>
        </div>
    </main>
@endsection
