@extends('layouts.uccello')

@section('page', 'edit')

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                {{-- Admin --}}
                @if ($admin_env)
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.settings.dashboard', $domain) }}">
                        <i class="material-icons left">settings</i>
                        <span class="hide-on-small-only">{{ uctrans('breadcrumb.admin', $module) }}</span>
                    </a>
                </span>
                @endif

                {{-- Module icon --}}
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.list', $domain, $module) }}">
                        <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                        <span class="hide-on-small-only">{{ uctrans($module->name, $module) }}</span>
                    </a>
                </span>
                @if ($record->getKey())<a class="breadcrumb" href="{{ ucroute('uccello.detail', $domain, $module, ['id' => $record->getKey()]) }}">{{ $record->recordLabel ?? $record->getKey() }}</a>@endif
                <span class="breadcrumb active">{{ $record->getKey() ? uctrans('breadcrumb.edit', $module) : uctrans('breadcrumb.create', $module) }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('uccello::modules.default.edit.form')
@endsection