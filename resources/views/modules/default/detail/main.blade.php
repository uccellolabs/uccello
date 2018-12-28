@extends('layouts.app')

@section('page', 'detail')

@section('extra-meta')
<meta name="record" content="{{ $record->id }}">
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
                    <li><a href="{{ ucroute('uccello.list', $domain, $module) }}">{{ uctrans($module->name, $module) }}</a></li>
                    <li class="active">{{ $record->recordLabel ?? $record->getKey() }}</li>
                </ol>
            </div>
        </div>

        @section ('custom-links')
        <div class="col-sm-8 col-xs-12 text-right">

            @yield('other-links')

            @if (count($module->detailLinks) > 0)
            @foreach ($module->detailLinks as $link)
                <div class="btn-group m-l-10">
                @include('uccello::layouts.partials.link.main', ['link' => $link])
                </div>
            @endforeach
            @endif

            @if (count($module->detailActionLinks) > 0)
            <div class="btn-group m-l-10">
                <button type="button" class="btn bg-primary icon-right waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    {!! uctrans('button.action', $module) !!}
                    <i class="material-icons">keyboard_arrow_down</i>
                </button>
                <ul class="dropdown-menu">
                    @foreach ($module->detailActionLinks as $link)
                        <li>
                            @include('uccello::layouts.partials.link.main', ['link' => $link])
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @show
    </div>
    @show

    {{-- Tab list --}}
    <ul class="nav nav-tabs m-b-25" role="tablist">
        {{-- Tabs --}}
        @foreach ($module->tabs as $i => $tab)
        <li role="presentation" @if ((empty($selectedTabId) && empty($selectedRelatedlistId) && $i === 0) || $selectedTabId === $tab->id)class="active"@endif>
            <a href="#{{ $tab->id }}" data-toggle="tab">
                <i class="material-icons">{{ $tab->icon ?? 'view_headline' }}</i> {{ uctrans($tab->label, $module) }}
            </a>
        </li>
        @endforeach

        {{-- One tab by related list --}}
        @foreach ($module->relatedlists as $relatedlist)
        @continue(!empty($relatedlist->tab_id) || !Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
        <li role="presentation" @if ($selectedRelatedlistId === $relatedlist->id)class="active"@endif>
            <a href="#relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}" data-toggle="tab">
                {{-- Icon --}}
                <i class="material-icons">{{ $relatedlist->icon ?? $relatedlist->relatedModule->icon }}</i>

                {{-- Label --}}
                {{ uctrans($relatedlist->label, $module) }}

                {{-- Badge --}}
                <?php
                    $relatedModule = $relatedlist->relatedModule;
                    $countMethod = $relatedlist->method . 'Count';

                    $model = new $relatedModule->model_class;
                    $count = $model->$countMethod($relatedlist, $record->id);
                ?>
                @if ($count > 0)
                <span class="badge bg-green">{{ $count }}</span>
                @endif
            </a>
        </li>
        @endforeach
    </ul>

    <div class="detail-blocks">
    @section('default-blocks')
        <div class="tab-content">
            {{-- Tabs and blocks --}}
            @foreach ($module->tabs as $i => $tab)
            <div role="tabpanel" class="tab-pane fade in @if ((empty($selectedTabId) && empty($selectedRelatedlistId) && $i === 0) || $selectedTabId === $tab->id)active @endif" id="{{ $tab->id }}">
                @foreach ($tab->blocks as $block)
                <div class="card block">
                    <div class="header">
                        <h2>
                            <div @if($block->icon)class="block-label-with-icon"@endif>

                                {{-- Icon --}}
                                @if($block->icon)
                                <i class="material-icons">{{ $block->icon }}</i>
                                @endif

                                {{-- Label --}}
                                <span>{{ uctrans($block->label, $module) }}</span>
                            </div>

                            {{-- Description --}}
                            @if ($block->description)
                                <small>{{ uctrans($block->description, $module) }}</small>
                            @endif
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row">
                        {{-- Display all block's fields --}}
                        @foreach ($block->fields as $field)
                            @continue(!$field->isDetailable())
                            <?php
                                // If a special template exists, use it. Else use the generic template
                                $uitypeViewName = sprintf('uitypes.detail.%s', $field->uitype->name);
                                $uitypeFallbackView = 'uccello::modules.default.uitypes.detail.text';
                                $uitypeViewToInclude = uccello()->view($field->uitype->package, $module, $uitypeViewName, $uitypeFallbackView);
                            ?>
                            @include($uitypeViewToInclude)
                        @endforeach
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Related lists as block --}}
                @foreach ($tab->relatedlists as $relatedlist)
                @continue(!Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
                <?php
                    $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule);
                ?>
                <div role="tabpanel" class="tab-pane fade in dataTable-container" id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}" data-button-size="mini">
                    <div class="card block">
                        <div class="header">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h2>
                                        <div class="block-label-with-icon">
                                            {{-- Icon --}}
                                            <i class="material-icons">{{ $relatedlist->icon ?? $relatedlist->relatedModule->icon }}</i>

                                            {{-- Label --}}
                                            <span>{{ uctrans($relatedlist->label, $module) }}</span>
                                        </div>
                                    </h2>
                                </div>
                                <div class="col-sm-4 action-buttons text-right">
                                    {{-- Select button --}}
                                    {{-- @if ($relatedlist->canSelect() && Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
                                    <button class="btn bg-orange btn-circle waves-effect waves-circle waves-float btn-relatedlist-select" title="{{ uctrans('relatedlist.button.select', $module) }}" data-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">playlist_add_check</i>
                                    </button>
                                    @endif --}}

                                    {{-- Add button --}}
                                    @if ($relatedlist->canAdd() && Auth::user()->canCreate($domain, $relatedlist->relatedModule))
                                    <a href="{{ $relatedlist->getAddLink($domain, $record->id) }}" class="btn bg-green btn-circle waves-effect waves-circle waves-float btn-relatedlist-add" title="{{ uctrans('relatedlist.button.add', $module) }}" data-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">playlist_add</i>
                                    </a>
                                    @endif

                                    {{-- Action buttons for related list --}}
                                </div>
                            </div>

                        </div>
                        <div class="body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable" data-relatedlist="{{ $relatedlist->id }}" data-related-module="{{ $relatedlist->relatedModule->name }}" data-url="{{ ucroute('uccello.datatable', $domain, $relatedlist->relatedModule) }}" data-columns='{!! json_encode($datatableColumns) !!}'>
                                            <thead>
                                                <tr>
                                                    <th class="select-column">&nbsp;</th>

                                                    @foreach ($datatableColumns as $column)
                                                    <th>
                                                        {{ uctrans('field.'.$column['name'], $relatedlist->relatedModule) }}
                                                    </th>
                                                    @endforeach

                                                    <th class="actions-column">&nbsp;</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="paginator"></div>

                    {{-- Template to use in the table --}}
                    <div class="template hide">
                        @if (Auth::user()->canUpdate($domain, $relatedlist->relatedModule))
                        <a href="{{ $relatedlist->getEditLink($domain, $record->id) }}" title="{{ uctrans('button.edit', $relatedlist->relatedModule) }}" class="edit-btn"><i class="material-icons">edit</i></a>
                        @endif

                        @if (Auth::user()->canDelete($domain, $relatedlist->relatedModule))
                        <a href="{{ $relatedlist->getDeleteLink($domain, $record->id) }}" title="{{ uctrans('button.delete', $relatedlist->relatedModule) }}" class="delete-btn"><i class="material-icons">delete</i></a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            {{-- Related lists as tab --}}
            @foreach ($module->relatedlists as $relatedlist)
            @continue(!empty($relatedlist->tab_id) || !Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
            <?php
                $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule);
            ?>
            <div role="tabpanel" class="tab-pane fade in dataTable-container @if ($selectedRelatedlistId === $relatedlist->id)active @endif" id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}" data-button-size="mini">
                <div class="card block">
                    <div class="header">
                        <div class="row">
                            <div class="col-sm-4">
                                <h2>
                                    <div class="block-label-with-icon">
                                        {{-- Icon --}}
                                        <i class="material-icons">{{ $relatedlist->icon ?? $relatedlist->relatedModule->icon }}</i>

                                        {{-- Label --}}
                                        <span>{{ uctrans($relatedlist->label, $module) }}</span>
                                    </div>
                                </h2>
                            </div>
                            <div class="col-sm-8 action-buttons text-right">
                                {{-- Select button --}}
                                {{-- @if ($relatedlist->canSelect() && Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
                                <button class="btn bg-orange btn-circle waves-effect waves-circle waves-float btn-relatedlist-select" title="{{ uctrans('relatedlist.button.select', $module) }}" data-toggle="tooltip" data-placement="top">
                                    <i class="material-icons">playlist_add_check</i>
                                </button>
                                @endif --}}

                                {{-- Add button --}}
                                @if ($relatedlist->canAdd() && Auth::user()->canCreate($domain, $relatedlist->relatedModule))
                                <a href="{{ $relatedlist->getAddLink($domain, $record->id) }}" class="btn bg-green btn-circle waves-effect waves-circle waves-float btn-relatedlist-add" title="{{ uctrans('relatedlist.button.add', $module) }}" data-toggle="tooltip" data-placement="top">
                                    <i class="material-icons">playlist_add</i>
                                </a>
                                @endif

                                {{-- Action buttons for related list --}}
                            </div>
                        </div>

                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover dataTable" data-relatedlist="{{ $relatedlist->id }}" data-related-module="{{ $relatedlist->relatedModule->name }}" data-url="{{ ucroute('uccello.datatable', $domain, $relatedlist->relatedModule) }}" data-columns='{!! json_encode($datatableColumns) !!}'>
                                        <thead>
                                            <tr>
                                                <th class="select-column">&nbsp;</th>

                                                @foreach ($datatableColumns as $column)
                                                <th>
                                                    {{ uctrans('field.'.$column['name'], $relatedlist->relatedModule) }}
                                                </th>
                                                @endforeach

                                                <th class="actions-column">&nbsp;</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="paginator"></div>

                {{-- Template to use in the table --}}
                <div class="template hide">
                    @if (Auth::user()->canUpdate($domain, $relatedlist->relatedModule))
                    <a href="{{ $relatedlist->getEditLink($domain, $record->id) }}" title="{{ uctrans('button.edit', $relatedlist->relatedModule) }}" class="edit-btn"><i class="material-icons">edit</i></a>
                    @endif

                    @if (Auth::user()->canDelete($domain, $relatedlist->relatedModule))
                    <a href="{{ $relatedlist->getDeleteLink($domain, $record->id) }}" title="{{ uctrans('button.delete', $relatedlist->relatedModule) }}" class="delete-btn"><i class="material-icons">delete</i></a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @show

        {{-- Other blocks --}}
        @yield('other-blocks')
    </div>

    @section('page-action-buttons')
    <div id="page-action-buttons">
        @if (Auth::user()->canUpdate($domain, $module))
        <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => $record->getKey()]) }}" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.edit', $module) }}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">edit</i>
        </a>
        @endif

        @if (Auth::user()->canDelete($domain, $module))
        <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => $record->getKey()]) }}" class="btn bg-red btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.delete', $module) }}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">delete</i>
        </a>
        @endif
    </div>
    @show
@endsection