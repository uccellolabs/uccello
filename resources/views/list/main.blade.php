@extends('uccello::layouts.uccello')

@section('title', 'List')

@section('content')
    <h1 class="mb-4 font-bold">{{ strtoupper($module->name) }}</h1>
    <livewire:uc-datatable :workspace="$workspace" :module="$module"/>
@endsection
