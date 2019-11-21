@extends('layouts.uccello')

@section('page', 'tree')

@section('extra-meta')
<meta name="module-tree-default-url" content="{{ ucroute('uccello.tree.root', $domain, $module) }}">
<meta name="module-tree-children-url" content="{{ ucroute('uccello.tree.children', $domain, $module) }}">
<meta name="module-tree-open-all" content="{{ config('uccello.treeview.open_tree', true) }}">
@append

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
                <a class="btn-flat" href="{{ ucroute($module->defaultRoute, $domain, $module) }}">
                    <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                    <span class="hide-on-small-only">{{ uctrans($module->name, $module) }}</span>
                </a>
            </span>

            {{-- Link to List View --}}
            <a href="{{ ucroute('uccello.list', $domain, $module) }}"
                class="btn-floating btn-small waves-effect orange z-depth-0 hide-on-small-only"
                data-position="top"
                data-tooltip="{{ uctrans('button.listview', $module) }}">
                    <i class="material-icons">list</i>
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="card-title">
                        {{-- Icon --}}
                        <i class="material-icons left primary-text">account_tree</i>

                        {{-- Label --}}
                        {{ uctrans($module->name, $module) }}

                        <div class="input-field right treeview-search-bar" style="margin: 0; width: 40%">
                            <i class="material-icons prefix primary-text">search</i>
                            <input type="text" id="record-name">
                            <label for="record-name">{{ uctrans('treeview.search', $module) }}</label>
                        </div>
                    </div>

                    @if ($totalCount > 0)
                    <div id="treeview">
                        {{-- Generated automatically --}}
                    </div>
                    @else
                    <div class="center-align red-text">{{ uctrans('treeview.no_records', $module) }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @section('page-action-buttons')
        {{-- Create button --}}
        @if (Auth::user()->canCreate($domain, $module))
        <div id="page-action-buttons">
            <a href="{{ ucroute('uccello.edit', $domain, $module) }}" class="btn-floating btn-large waves-effect green" data-tooltip="{{ uctrans('button.new', $module) }}" data-position="top">
                <i class="material-icons">add</i>
            </a>
        </div>
        @endif
    @show
@endsection