@extends('layouts.app')

@section('page', 'index')

@section('content')

    @section('breadcrumb')
    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <div class="breadcrumb pull-left">
                {{-- Module icon --}}
                <a href="{{ ucroute('uccello.settings.dashboard', $domain) }}" class="pull-left module-icon">
                    <i class="material-icons">{{ $module->icon ?? 'extension' }}</i>
                </a>

                <ol class="breadcrumb pull-left">
                    @if ($admin_env)<li><a href="{{ ucroute('uccello.settings.dashboard', $domain) }}">{{ uctrans('breadcrumb.admin', $module) }}</a></li>@endif
                    <li class="active">{{ uctrans('dashboard', $module) }}</li>
                </ol>
            </div>
        </div>
    </div>
    @show

<div class="row clearfix">
    {{-- Domains --}}
    @if (Auth::user()->canAdmin($domain, ucmodule('domain')))
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="icon bg-primary">
                <i class="material-icons">domain</i>
            </div>
            <div class="content">
                <div class="text uppercase">{{ uctrans('domain', ucmodule('domain'))}}</div>
                <div class="number count-to" data-from="0" data-to="{{ $count['domains'] }}" data-speed="1000" data-fresh-interval="20">{{ $count['domains'] }}</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Users --}}
    @if (Auth::user()->canAdmin($domain, ucmodule('user')))
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="icon bg-green">
                <i class="material-icons">person</i>
            </div>
            <div class="content">
                <div class="text uppercase">{{ uctrans('user', ucmodule('user'))}}</div>
                <div class="number count-to" data-from="0" data-to="{{ $count['users'] }}" data-speed="1000" data-fresh-interval="20">{{ $count['users'] }}</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Roles --}}
    @if (Auth::user()->canAdmin($domain, ucmodule('role')))
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="icon bg-red">
                <i class="material-icons">lock</i>
            </div>
            <div class="content">
                <div class="text uppercase">{{ uctrans('role', ucmodule('role'))}}</div>
                <div class="number count-to" data-from="0" data-to="{{ $count['roles'] }}" data-speed="1000" data-fresh-interval="20">{{ $count['roles'] }}</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Modules --}}
    @if (Auth::user()->canAdmin($domain, ucmodule('settings')))
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="icon bg-orange">
                <i class="material-icons">extension</i>
            </div>
            <div class="content">
                <div class="text uppercase">{{ uctrans('modules.count', $module)}}</div>
                <div class="number count-to" data-from="0" data-to="{{ $count['modules'] }}" data-speed="1000" data-fresh-interval="20">{{ $count['modules'] }}</div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection