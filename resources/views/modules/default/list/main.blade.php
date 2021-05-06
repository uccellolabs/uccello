@extends('uccello::layouts.uccello')

@section('title', 'List')

@section('content')
<div class="p-8 mr-12">
    <div class="mb-4 flex flex-row items-center">
        {{-- Title --}}
        <div class="flex flex-row items-center">
            <h1 class="text-primary-900 text-3xl font-semibold">{{ strtoupper($module->name) }}</h1>
        </div>
    </div>
    {{-- Table --}}
    <div  class="w-full overflow-auto bg-white border border-gray-200 rounded-lg px-7" style="height: calc(100vh - 200px)">
        <livewire:uc-datatable :workspace="$workspace" :module="$module"/>
    </div>
    
</div>
@endsection
