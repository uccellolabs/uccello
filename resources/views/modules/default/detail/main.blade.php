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
                    @if ($admin_env)<li><a href="">{{ uctrans('breadcrumb.admin', $module) }}</a></li>@endif
                    <li><a href="{{ ucroute('uccello.list', $domain, $module) }}">{{ uctrans($module->name, $module) }}</a></li>
                    <li class="active">{{ $record->recordLabel ?? $record->getKey() }}</li>
                </ol>
            </div>
        </div>
    </div>
    @show

    {{-- Tab list --}}
    <ul class="nav nav-tabs m-b-25" role="tablist">
        {{-- Tabs --}}
        @foreach ($module->tabs as $i => $tab)
        <li role="presentation" @if ($i === 0)class="active"@endif>
            <a href="#{{ $tab->id }}" data-toggle="tab">
                <i class="material-icons">{{ $tab->icon ?? 'view_headline' }}</i> {{ uctrans($tab->label, $module) }}
            </a>
        </li>
        @endforeach

        {{-- Related lists --}}
        @foreach ($module->relatedlists as $relatedlist)
        @continue(!empty($relatedlist->tab_id))
        <li role="presentation">
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
                    $count = $model->$countMethod($relatedlist, $module, $record->id);
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
            <div role="tabpanel" class="tab-pane fade in @if ($i === 0)active @endif" id="{{ $tab->id }}">
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

                {{-- Tab related lists --}}
                @foreach ($tab->relatedlists as $relatedlist)
                <?php
                    $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule);
                ?>
                <div role="tabpanel" class="tab-pane fade in dataTable-container" id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}">
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
                                <div class="col-sm-4 action-buttons">
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
                        <a href="{{ ucroute('uccello.edit', $domain, $relatedlist->relatedModule, ['id' => 'RECORD_ID']) }}" title="{{ uctrans('button.edit', $relatedlist->relatedModule) }}" class="edit-btn"><i class="material-icons">edit</i></a>
                        @endif

                        @if (Auth::user()->canDelete($domain, $relatedlist->relatedModule))
                        <a href="{{ ucroute('uccello.delete', $domain, $relatedlist->relatedModule, ['id' => 'RECORD_ID']) }}" title="{{ uctrans('button.delete', $relatedlist->relatedModule) }}" class="delete-btn"><i class="material-icons">delete</i></a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            {{-- Related lists --}}
            @foreach ($module->relatedlists as $relatedlist)
            @continue(!empty($relatedlist->tab_id))
            <?php
                $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule);
            ?>
            <div role="tabpanel" class="tab-pane fade in dataTable-container" id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}">
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
                            <div class="col-sm-4 action-buttons">
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
                    <a href="{{ ucroute('uccello.edit', $domain, $relatedlist->relatedModule, ['id' => 'RECORD_ID']) }}" title="{{ uctrans('button.edit', $relatedlist->relatedModule) }}" class="edit-btn"><i class="material-icons">edit</i></a>
                    @endif

                    @if (Auth::user()->canDelete($domain, $relatedlist->relatedModule))
                    <a href="{{ ucroute('uccello.delete', $domain, $relatedlist->relatedModule, ['id' => 'RECORD_ID']) }}" title="{{ uctrans('button.delete', $relatedlist->relatedModule) }}" class="delete-btn"><i class="material-icons">delete</i></a>
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
        <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => $record->getKey()]) }}" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.edit', $module) }}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">edit</i>
        </a>

        <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => $record->getKey()]) }}" class="btn bg-red btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.delete', $module) }}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">delete</i>
        </a>
    </div>
    @show
@endsection