@extends('layouts.app')

@section('page', 'index')

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
                    <li class="active">{{ uctrans($module->name, $module) }}</li>
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
                <div class="text uppercase">{{ uctrans('domains.count', $module)}}</div>
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
                <div class="text uppercase">{{ uctrans('users.count', $module)}}</div>
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
                <div class="text uppercase">{{ uctrans('roles.count', $module)}}</div>
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