@extends('layouts.uccello')

@section('page', 'module-manager')

@section('extra-meta')
<meta name="module-activation-url" content="{{ ucroute('uccello.settings.module.activation', $domain) }}">
@append

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.settings.dashboard', $domain) }}">
                        <i class="material-icons left">settings</i>
                        <span>{{ uctrans('breadcrumb.admin', $module) }}</span>
                    </a>
                </span>
                <span class="breadcrumb active">{{ uctrans('module.manager', $module) }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Tabs --}}
        <div class="col s12">
          <ul class="tabs transparent">
            <li class="tab">
                <a href="#main-modules">
                    <i class="material-icons left">extension</i>
                    {{ uctrans('module.manager.tab.main_modules', $module) }}
                </a>
            </li>
            <li class="tab">
                <a href="#admin-modules" class="active">
                    <i class="material-icons left">settings</i>
                    {{ uctrans('module.manager.tab.admin_modules', $module) }}
                </a>
            </li>
          </ul>
        </div>

        {{-- Main --}}
        <div id="main-modules" class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        {{-- Icon --}}
                        <i class="material-icons left primary-text">extension</i>

                        {{-- Label --}}
                        {{ uctrans('module.manager.main_modules', $module) }}

                        {{-- Description --}}
                        <small class="with-icon">{{ uctrans('module.manager.description', $module) }}</small>
                    </div>

                    <div class="row">
                        @foreach ($mainModules as $_module)
                            @include('uccello::modules.settings.module-manager.module')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Admin --}}
        <div id="admin-modules" class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        {{-- Icon --}}
                        <i class="material-icons left primary-text">settings</i>

                        {{-- Label --}}
                        {{ uctrans('module.manager.admin_modules', $module) }}

                        {{-- Description --}}
                        <small class="with-icon">{{ uctrans('module.manager.description', $module) }}</small>
                    </div>

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