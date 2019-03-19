@extends('layouts.app')

@section('page', 'list')

{{-- @section('content-class', 'listview') --}}

{{-- @section('breadcrumb')
    <div class="row">
        <div class="col s6">
            <div class="breadcrumb pull-left">
                {{-- Module icon - -}}
                <a href="{{ ucroute('uccello.list', $domain, $module) }}" class="pull-left module-icon">
                    <i class="material-icons">{{ $module->icon ?? 'extension' }}</i>
                </a>

                <ol class="breadcrumb filters pull-left">
                    @if ($admin_env)<li><a href="{{ ucroute('uccello.settings.dashboard', $domain) }}">{{ uctrans('breadcrumb.admin', $module) }}</a></li>@endif
                    <li><a href="{{ ucroute('uccello.list', $domain, $module) }}">{{ uctrans($module->name, $module) }}</a></li>
                    <li>
                        <select class="filter show-tick" data-live-search="true">
                            @foreach ($filters as $filter)
                            <option value="{{ $filter->id }}" @if($selectedFilter && $filter->id == $selectedFilter->id)selected="selected"@endif>{{ uctrans($filter->name, $module) }}</option>
                            @endforeach
                        </select>
                    </li>
                </ol>

                {{-- Export - -}}
                <div class="pull-right export">
                    <a href="javascript:void(0)" class="action-button" data-position="top" data-tooltip="{{ uctrans('button.export', $module) }}" data-config='{"actionType":"modal", "modal":"#exportModal"}'>
                        <i class="material-icons bg-primary">cloud_download</i>
                    </a>
                </div>

                {{-- Manage filters - -}}
                <div class="pull-right manage-filters">
                    <a href="#" class="action-button dropdown-trigger" data-target="dropdown-filter" data-position="top" data-tooltip="{{ uctrans('button.manage_filters', $module) }}">
                        <i class="material-icons bg-green">filter_list</i>
                    </a>
                    <ul id="dropdown-filter" class="dropdown-content">
                        <li>
                            <a href="#!" class="add-filter" data-config='{"actionType":"modal", "modal":"#addFilterModal"}'>
                                <i class="material-icons">add</i>
                                {{ uctrans('button.add_filter', $module) }}
                            </a>
                        </li>
                        <li>
                            <a class="delete-filter" @if(!$selectedFilter || $selectedFilter->readOnly)disabled @endif>
                                <i class="material-icons">delete</i>
                                {{ uctrans('button.delete_filter', $module) }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

@section('top-action-buttons')
    <div class="action-buttons right-align">
        {{-- Columns visibility --}}
        <a href="#" class="btn-small waves-effect primary dropdown-trigger" data-target="dropdown-columns">
            {!! uctrans('button.columns', $module) !!}
            <i class="material-icons">keyboard_arrow_down</i>
        </a>
        <ul id="dropdown-columns" class="dropdown-content columns" data-table="datatable">
            @foreach ($datatableColumns as $column)
            <li @if($column['visible'])class="active"@endif><a href="javascript:void(0);" class="waves-effect waves-block column-visibility" data-field="{{ $column['name'] }}">{{ uctrans('field.'.$column['name'], $module) }}</a></li>
            @endforeach
        </ul>

        {{-- Records number --}}
        <a href="#" class="btn-small waves-effect primary dropdown-trigger" data-target="dropdown-records-number">
            {!! uctrans('filter.show_n_records', $module, ['number' => '<strong class="records-number">'.($selectedFilter->data->length ?? 15).'</strong>']) !!}
            <i class="material-icons">keyboard_arrow_down</i>
        </a>
        <ul id="dropdown-records-number" class="dropdown-content records-number" data-table="datatable">
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="15">15</a></li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="30">30</a></li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="50">50</a></li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="100">100</a></li>
        </ul>
    </div>
@endsection

@section('content')
    @yield('before-table')

    <div class="row clearfix">
        <div class="col s12" style="min-height: 600px">
            <div class="card">
                <div class="card-content p-t-0">
                    <div>
                        <table
                            id="datatable"
                            class="striped highlight responsive-table"
                            data-filter-type="list"
                            data-filter-id="{{ $selectedFilter->id ?? '' }}"
                            data-list-url="{{ ucroute('uccello.list.content', $domain, $module, ['_token' => csrf_token()]) }}"
                            data-length="{{ $selectedFilter->data->length ?? 15 }}">
                            <thead>
                                <tr>
                                    <th class="select-column">&nbsp;</th>

                                    @foreach ($datatableColumns as $column)
                                    <th data-field="{{ $column['name'] }}" data-column="{{ $column['db_column'] }}" @if(!$column['visible'])style="display: none"@endif>
                                        {{ uctrans('field.'.$column['name'], $module) }}
                                        <div class="hide-on-small-only hide-on-med-only">
                                            <?php
                                                $searchValue = null;
                                                if ($selectedFilter && !empty($selectedFilter->conditions->search->{$column['name']})) {
                                                    $searchValue = $selectedFilter->conditions->search->{$column['name']};
                                                }

                                                // If a special template exists, use it. Else use the generic template
                                                $uitypeViewName = sprintf('uitypes.search.%s', $column[ 'uitype' ]);
                                                $uitypeFallbackView = 'uccello::modules.default.uitypes.search.text';
                                                $uitypeViewToInclude = uccello()->view($column[ 'package' ], $module, $uitypeViewName, $uitypeFallbackView);
                                            ?>
                                            @include($uitypeViewToInclude)
                                        </div>
                                    </th>
                                    @endforeach

                                    <th class="actions-column hide-on-small-only hide-on-med-only right-align">
                                        <a href="javascript:void(0)" class="clear-search red-text" data-tooltip="{{ uctrans('button.clear_search', $module) }}" data-position="top" style="display: none">
                                            <i class="material-icons">close</i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- No result --}}
                                <tr class="no-results" style="display: none">
                                    <td colspan="{{ count($datatableColumns) + 2 }}" class="center-align">{{ uctrans('no_results', $module) }}</td>
                                </tr>

                                {{-- Row template used by the query --}}
                                <tr class="template hide" data-row-url="{{ ucroute('uccello.detail', $domain, $module, ['id' => 'RECORD_ID']) }}">
                                    <td class="select-column">&nbsp;</td>

                                    @foreach ($datatableColumns as $column)
                                    <td data-field="{{ $column['name'] }}">&nbsp;</td>
                                    @endforeach

                                    <td class="actions-column hide-on-small-only hide-on-med-only right-align">
                                        @if (Auth::user()->canUpdate($domain, $module))
                                        <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => 'RECORD_ID']) }}" data-tooltip="{{ uctrans('button.edit', $module) }}" data-position="left" class="edit-btn primary-text"><i class="material-icons">edit</i></a>
                                        @endif

                                        @if (Auth::user()->canDelete($domain, $module))
                                        <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => 'RECORD_ID']) }}" data-tooltip="{{ uctrans('button.delete', $module) }}" data-position="left" class="delete-btn primary-text" data-config='{"actionType":"link","confirm":true,"dialog":{"title":"{{ uctrans('button.delete.confirm', $module) }}"}}'><i class="material-icons">delete</i></a>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Other rows are generated automaticaly --}}
                            </tbody>
                        </table>

                        {{-- Loader --}}
                        <div class="loader center-align hide" data-table="datatable">
                            <div class="preloader-wrapper big active">
                                <div class="spinner-layer spinner-primary-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div>
                                    <div class="gap-patch">
                                        <div class="circle"></div>
                                    </div>
                                    <div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                {{ uctrans('loading', $module) }}
                            </div>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="center-align">
                        <ul class="pagination" data-table="datatable">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('after-table')

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

@section('extra-content')
    {{-- Add filter modal --}}
    @include("uccello::modules.default.list.modal.add-filter")
    {{-- Export modal --}}
    @include("uccello::modules.default.list.modal.export")
@endsection