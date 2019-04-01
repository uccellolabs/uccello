@extends('layouts.app')

@section('page', 'menu-manager')

@section('extra-meta')
<meta name="main-menu-structure" content='{!! json_encode($mainMenuJson ?? '') !!}'>
<meta name="admin-menu-structure" content='{!! json_encode($adminMenuJson ?? '') !!}'>
<meta name="save-url" content="{{ ucroute('uccello.settings.menu.store', $domain) }}">
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
                <span class="breadcrumb active">{{ uctrans('menu.manager', $module) }}</span>
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
                        <i class="material-icons left primary-text">menu</i>

                        {{-- Label --}}
                        {{ uctrans('menu.manager', $module) }}

                        {{-- Description --}}
                        <small class="with-icon">{{ uctrans('menu.manager.description', $module) }}</small>
                    </div>

                    <div class="row">
                        <div class="col s12 m5 l4 offset-m2 offset-l3">
                            {{-- Menu switcher --}}
                            <div class="input-field">
                                <div class="switch center-align" style="margin-top: 10px">
                                    <label>
                                        <b class="black-text">{{ uctrans('menu.type.main', $module) }}</b>
                                        <input type="checkbox" name="menu-switcher" id="menu-switcher" value="admin" />
                                        <span class="lever"></span>
                                        <b class="primary-text">{{ uctrans('menu.type.admin', $module) }}</b>
                                    </label>
                                    <input type="hidden" id="selected-menu" value="main" />
                                </div>
                            </div>

                            <div class="menu-manager menu-main dd" data-type="main">
                                <ol class="dd-list">
                                    @if (empty($menu->data))
                                        @foreach ($domain->notAdminModules as $_module)
                                            @foreach ($_module->menuLinks as $link)
                                            @include('uccello::modules.settings.menu-manager.item')
                                            @endforeach
                                        @endforeach
                                    @endif
                                </ol>
                            </div>

                            <div class="menu-manager menu-admin dd" data-type="admin" style="display: none">
                                <ol class="dd-list">
                                    @if (empty($menu->data))
                                        @foreach ($domain->adminModules as $_module)
                                            @foreach ($_module->menuLinks as $link)
                                            @include('uccello::modules.settings.menu-manager.item')
                                            @endforeach
                                        @endforeach
                                    @endif
                                </ol>
                            </div>
                        </div>

                        <div class="col s12 m3 btn-actions">
                            <a href="#groupModal" class="btn waves-effect modal-trigger green" style="width: 100%;">
                                <i class="material-icons left">folder</i>
                                {{ uctrans('menu.button.add_group', $module) }}
                            </a>

                            {{-- <a href="#routeLinkModal" class="btn waves-effect modal-trigger red" style="width: 100%;  margin-top: 10px">
                                <i class="material-icons left">link</i>
                                {{ uctrans('menu.button.add_route_link', $module) }}
                            </a> --}}

                            <a href="#linkModal" class="btn waves-effect modal-trigger primary" style="width: 100%; margin-top: 10px">
                                <i class="material-icons left">link</i>
                                {{ uctrans('menu.button.add_link', $module) }}
                            </a>

                            <a href="{{ ucroute('uccello.settings.menu.reset', $domain) }}"
                                id="btn-reset-menu"
                                class="btn waves-effect orange"
                                style="width: 100%; margin-top: 10px">
                                <i class="material-icons left">cancel</i>
                                {{ uctrans('menu.button.reset', $module) }}
                            </a>

                            <div class="center-align" style="margin-top: 10px">
                                <span class="green-text saved" style="display: none">{{ uctrans('menu.saved', $module) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-content')
    @include('uccello::modules.settings.menu-manager.modal.group')
    @include('uccello::modules.settings.menu-manager.modal.route-link')
    @include('uccello::modules.settings.menu-manager.modal.link')
@endsection

@section('uccello-extra-script')
    {{ Html::script(mix('js/settings/autoloader.js', 'vendor/uccello/uccello')) }}
@endsection