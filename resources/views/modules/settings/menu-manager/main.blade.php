@extends('layouts.app')

@section('page', 'menu-manager')

@section('extra-meta')
<meta name="classic-menu-structure" content='{!! json_encode($classicMenu->data ?? '') !!}'>
<meta name="admin-menu-structure" content='{!! json_encode($adminMenu->data ?? '') !!}'>
<meta name="save-url" content="{{ ucroute('uccello.settings.menu.store', $domain) }}">
@endsection

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
                    <li class="active">{{ uctrans('menu.manager', $module) }}</li>
                </ol>
            </div>
        </div>
    </div>
    @show

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card block">
                <div class="header">
                    <h2>
                        <div class="block-label-with-icon">
                            <i class="material-icons">menu</i>
                            <span>{{ uctrans('menu.manager', $module) }}</span>
                        </div>
                        <small>{{ uctrans('menu.manager.description', $module) }}</small>
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Field --}}
                            <div class="form-switch text-right">
                                <div class="switch" style="padding-top: 10px; padding-bottom: 5px;">
                                    <label class="switch-label">
                                        {{ uctrans('menu.type.classic', $module) }}
                                        <input type="checkbox" name="menu-switcher" id="menu-switcher" value="admin" />
                                        <span class="lever switch-col-primary"></span>
                                        {{ uctrans('menu.type.admin', $module) }}
                                    </label>
                                </div>
                            </div>

                            <div class="menu-manager menu-classic dd" data-type="classic">
                                <ol class="dd-list">
                                    @if (empty($menu->data))
                                        @foreach ($modules as $_module)
                                            @continue (!$_module->isActiveOnDomain($domain) || $_module->isAdminModule())
                                            @foreach ($_module->menuLinks as $link)
                                            <li class="dd-item dd-nochildren" data-module="{{ $_module->name }}" data-type="module" data-label="{{ uctrans($link->label, $_module) }}" data-icon="{{ $link->icon ?? 'extension' }}" data-route="{{ $link->route }}" data-module="{{ $_module->name }}" data-color="grey">
                                                <div class="dd-handle">
                                                    <i class="material-icons">{{ $link->icon ?? 'extension' }}</i>
                                                    <span class="icon-label">{{ uctrans($link->label, $_module) }}</span>
                                                    <span class="pull-right col-grey">{{ uctrans('menu.link.type.module', $module) }}</span>
                                                </div>
                                            </li>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </ol>
                            </div>

                            <div class="menu-manager menu-admin dd" data-type="admin" style="display: none">
                                <ol class="dd-list">
                                    @if (empty($menu->data))
                                        @foreach ($modules as $_module)
                                            @continue (!$_module->isActiveOnDomain($domain) || ($_module->name !== 'home' && !$_module->isAdminModule()))
                                            @foreach ($_module->menuLinks as $link)
                                            <li class="dd-item dd-nochildren" data-module="{{ $_module->name }}" data-type="module" data-label="{{ uctrans($link->label, $_module) }}" data-icon="{{ $link->icon ?? 'extension' }}" data-route="{{ $link->route }}" data-module="{{ $_module->name }}" data-color="grey">
                                                <div class="dd-handle">
                                                    <i class="material-icons">{{ $link->icon ?? 'extension' }}</i>
                                                    <span class="icon-label">{{ uctrans($link->label, $_module) }}</span>
                                                    <span class="pull-right col-grey">{{ uctrans('menu.link.type.module', $module) }}</span>
                                                </div>
                                            </li>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </ol>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <a class="waves-effect waves-block btn icon-right bg-green m-b-10" data-config='{"actionType":"modal","modal":"#addGroupModal"}'>
                                <i class="material-icons">folder</i>
                                {{ uctrans('menu.button.add_group', $module) }}
                            </a>

                            <a class="waves-effect waves-block btn icon-right bg-red m-b-10" data-config='{"actionType":"modal","modal":"#addRouteLinkModal"}'>
                                <i class="material-icons">link</i>
                                {{ uctrans('menu.button.add_route_link', $module) }}
                            </a>

                            <a class="waves-effect waves-block btn icon-right bg-primary m-b-10" data-config='{"actionType":"modal","modal":"#addLinkModal"}'>
                                <i class="material-icons">link</i>
                                {{ uctrans('menu.button.add_link', $module) }}
                            </a>

                            <a href="{{ ucroute('uccello.settings.menu.store', $domain) }}"
                                class="save-menu waves-effect waves-block btn icon-right bg-orange">
                                <i class="material-icons">save</i>
                                {{ uctrans('menu.button.save', $module) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-content')
    <div id="addGroupModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        {{ uctrans('menu.button.add_group', $module) }}
                    </h4>
                </div>
                <div class="modal-body">
                    {{-- Label --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="label" class="form-control">
                            <label class="form-label">{{ uctrans('menu.group.label', $module) }}</label>
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="icon" class="form-control">
                            <label class="form-label">{{ uctrans('menu.group.icon', $module) }}</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{ uctrans('button.cancel', $module) }}</button>
                    <button type="button" id="add-group" class="btn btn-link waves-effect col-green">{{ uctrans('button.save', $module) }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="addRouteLinkModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ uctrans('menu.button.add_route_link', $module) }}</h4>
                </div>
                <div class="modal-body">
                    {{-- Label --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="label" class="form-control">
                            <label class="form-label">{{ uctrans('menu.link.label', $module) }}</label>
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="icon" class="form-control">
                            <label class="form-label">{{ uctrans('menu.link.icon', $module) }}</label>
                        </div>
                    </div>

                    {{-- Module --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="module" class="form-control">
                            <label class="form-label">{{ uctrans('menu.link.module', $module) }}</label>
                        </div>
                    </div>

                    {{-- Route --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="route" class="form-control">
                            <label class="form-label">{{ uctrans('menu.link.route', $module) }}</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{ uctrans('button.cancel', $module) }}</button>
                    <button type="button" id="add-route-link" class="btn btn-link waves-effect col-green">{{ uctrans('button.save', $module) }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="addLinkModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ uctrans('menu.button.add_link', $module) }}</h4>
                </div>
                <div class="modal-body">
                    {{-- Label --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="label" class="form-control">
                            <label class="form-label">{{ uctrans('menu.link.label', $module) }}</label>
                        </div>
                    </div>

                    {{-- Icon --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="icon" class="form-control">
                            <label class="form-label">{{ uctrans('menu.link.icon', $module) }}</label>
                        </div>
                    </div>

                    {{-- Url --}}
                    <div class="form-group form-float">
                        <div class="form-line">
                            <input type="text" name="url" class="form-control">
                            <label class="form-label">{{ uctrans('menu.link.url', $module) }}</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">{{ uctrans('button.cancel', $module) }}</button>
                    <button type="button" id="add-link" class="btn btn-link waves-effect col-green">{{ uctrans('button.save', $module) }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    {{ Html::script(ucasset('js/settings/autoloader.js')) }}
@endsection