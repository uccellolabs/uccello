@extends('layouts.app')

@section('content')
    <a href="{{ route('edit', ['domain' => $domain->slug, 'module' => $module->name]) }}" class="btn btn-success">
    {{ uctrans('add_record', $module) }}</a>
@endsection