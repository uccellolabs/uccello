@extends('layouts.app')

@section('page', 'module-manager')

@section('content')

    @section('breadcrumb')
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <div class="breadcrumb pull-left">
                {{-- Redirect to previous page. If there is not previous page, redirect to list view --}}
                <a href="{{ URL::previous() !== URL::current() ? URL::previous() : ucroute('uccello.list', $domain, $module) }}" class="pull-left">
                    <i class="material-icons" data-toggle="tooltip" data-placement="top" title="{{ uctrans('button.return', $module) }}">chevron_left</i>
                </a>

                <ol class="breadcrumb pull-left">
                    @if ($admin_env)<li><a href="{{ ucroute('uccello.settings.dashboard', $domain) }}">{{ uctrans('breadcrumb.admin', $module) }}</a></li>@endif
                    <li class="active">{{ uctrans('module.manager', $module) }}</li>
                </ol>
            </div>
        </div>
    </div>
    @show

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card block" id="modules-list">
                <div class="header">
                    <h2>
                        <div class="block-label-with-icon">
                            <i class="material-icons">extension</i>
                            <span>{{ uctrans('module.manager', $module) }}</span>
                        </div>
                        <small>{{ uctrans('module.manager.description', $module) }}</small>
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        @foreach ($modules as $_module)
                            <div class="col-md-3 col-sm-4">
                                <div style="margin-top: 20px;">
                                    <input id="checkbox-{{ $_module->id }}"
                                        class="module-activation filled-in @if($_module->isMandatory())chk-col-grey @else chk-col-green @endif"
                                        type="checkbox"
                                        data-module="{{ $_module->name }}"
                                        @if($_module->isActiveOnDomain($domain))checked="checked"@endif
                                        @if($_module->isMandatory() || !Auth::user()->canAdmin($domain, $_module))disabled="disabled"@endif>
                                    <label for="checkbox-{{ $_module->id }}" class="checkbox-label">
                                        <i class="material-icons">{{ $_module->icon ?? 'extension' }}</i>
                                        <span class="icon-label">{{ uctrans($_module->name, $_module) }}</span>
                                    </label>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    {{ Html::script(ucasset('js/settings/autoloader.js')) }}
@endsection