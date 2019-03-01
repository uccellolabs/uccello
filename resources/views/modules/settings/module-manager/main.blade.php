@extends('layouts.app')

@section('page', 'module-manager')

@section('breadcrumb')
    <div class="row">
        <div class="col-xs-12">
            <div class="breadcrumb pull-left">
                {{-- Module icon --}}
                <a href="{{ ucroute('uccello.settings.dashboard', $domain) }}" class="pull-left module-icon">
                    <i class="material-icons">{{ $module->icon ?? 'extension' }}</i>
                </a>

                <ol class="breadcrumb pull-left">
                    @if ($admin_env)<li><a href="{{ ucroute('uccello.settings.dashboard', $domain) }}">{{ uctrans('breadcrumb.admin', $module) }}</a></li>@endif
                    <li class="active">{{ uctrans('module.manager', $module) }}</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card block" id="modules-list">
                <div class="header">
                    <h2>
                        <div class="block-label-with-icon">
                            <i class="material-icons">extension</i>
                            <span>{{ uctrans('module.manager.main_modules', $module) }}</span>
                        </div>
                        <small>{{ uctrans('module.manager.description', $module) }}</small>
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        @foreach ($mainModules as $_module)
                            @include('uccello::modules.settings.module-manager.module')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card block" id="modules-list">
                <div class="header">
                    <h2>
                        <div class="block-label-with-icon">
                            <i class="material-icons">settings</i>
                            <span>{{ uctrans('module.manager.admin_modules', $module) }}</span>
                        </div>
                        <small>{{ uctrans('module.manager.description', $module) }}</small>
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        @foreach ($adminModules as $_module)
                            @include('uccello::modules.settings.module-manager.module')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('uccello-extra-script')
    {{ Html::script(mix('js/settings/autoloader.js', 'vendor/uccello/uccello')) }}
@endsection