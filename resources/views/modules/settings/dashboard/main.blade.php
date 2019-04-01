@extends('layouts.app')

@section('page', 'index')

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
                <span class="breadcrumb active">{{ uctrans('dashboard', $module) }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Domains --}}
        @if (Auth::user()->canAdmin($domain, ucmodule('domain')))
        <div class="col s12 m6 l3">
            <div class="card horizontal info-box">
                <div class="icon primary">
                    <i class="material-icons">domain</i>
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <div class="text uppercase">{{ uctrans('domain', ucmodule('domain'))}}</div>
                        <div class="number count-to" data-from="0" data-to="{{ $count['domains'] }}" data-speed="1000" data-fresh-interval="20">{{ $count['domains'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Users --}}
        @if (Auth::user()->canAdmin($domain, ucmodule('user')))
        <div class="col s12 m6 l3">
            <div class="card horizontal info-box">
                <div class="icon green">
                    <i class="material-icons">person</i>
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <div class="text uppercase truncate">{{ uctrans('user', ucmodule('user'))}}</div>
                        <div class="number count-to" data-from="0" data-to="{{ $count['users'] }}" data-speed="1000" data-fresh-interval="20">{{ $count['users'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Roles --}}
        @if (Auth::user()->canAdmin($domain, ucmodule('role')))
        <div class="col s12 m6 l3">
            <div class="card horizontal info-box">
                <div class="icon red">
                    <i class="material-icons">lock</i>
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <div class="text uppercase truncate">{{ uctrans('role', ucmodule('role'))}}</div>
                        <div class="number count-to" data-from="0" data-to="{{ $count['roles'] }}" data-speed="1000" data-fresh-interval="20">{{ $count['roles'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Modules --}}
        @if (Auth::user()->canAdmin($domain, ucmodule('settings')))
        <div class="col s12 m6 l3">
            <div class="card horizontal info-box">
                <div class="icon orange">
                    <i class="material-icons">extension</i>
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <div class="text uppercase truncate">{{ uctrans('modules.count', $module)}}</div>
                        <div class="number count-to" data-from="0" data-to="{{ $count['modules'] }}" data-speed="1000" data-fresh-interval="20">{{ $count['modules'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection