@extends('uccello::modules.default.detail.main')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="breadcrumb pull-left">
                {{-- Module icon --}}
                <a href="{{ ucroute('uccello.preference', $domain, $module) }}" class="pull-left module-icon">
                    <i class="material-icons">{{ $module->icon ?? 'extension' }}</i>
                </a>

                <ol class="breadcrumb pull-left">
                    <li class="active">{{ uctrans('preference', $module) }}</li>
                </ol>
            </div>
        </div>

        {{-- Custom links --}}
        @section ('custom-links')
            @include('uccello::modules.default.detail.links')
        @show
    </div>
@endsection

@section('page-action-buttons')
    <div id="page-action-buttons">
        <a href="{{ ucroute('uccello.edit', $domain, $module) }}" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.edit', $module) }}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">edit</i>
        </a>
    </div>
@endsection