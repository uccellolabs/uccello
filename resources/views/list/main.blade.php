@extends('layouts.app')

@section('content')
    <a href="{{ route('edit', ['domain' => $domain->slug, 'module' => $module->name]) }}" class="btn btn-success">Add record</a>
@endsection