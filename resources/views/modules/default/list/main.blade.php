@extends('layouts.app')

@section('page', 'list')

{{-- @section('content-class', 'listview') --}}

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                {{-- Module icon --}}
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.list', $domain, $module) }}">
                        <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                        <span>{{ uctrans($module->name, $module) }}</span>
                    </a>
                </span>

                {{-- Filters list --}}
                <span class="breadcrumb" href="#">
                    <a class="dropdown-trigger btn-flat" href="#" data-target="filters-list">
                        <i class="material-icons right">arrow_drop_down</i>
                        <span class="primary-text">{{ uctrans($selectedFilter->name, $module) }}</span>
                    </a>
                    <ul id="filters-list" class="dropdown-content">
                        @foreach ($filters as $filter)
                        <li><a href="javascript:void(0)" data-id="{{ $filter->id }}" @if($selectedFilter && $filter->id == $selectedFilter->id)class="active"@endif>{{ uctrans($filter->name, $module) }}</a></li>
                        @endforeach
                    </ul>
                </span>

                {{-- Filters management --}}
                <a class="dropdown-trigger btn-floating btn-small waves-effect green z-depth-0" href="#" data-target="filters-management" data-constrain-width="false"  data-position="top" data-tooltip="{{ uctrans('button.manage_filters', $module) }}">
                    <i class="material-icons">filter_list</i>
                </a>
                <ul id="filters-management" class="dropdown-content">
                    <li>
                        <a href="javascript:void(0)" class="add-filter" data-config='{"actionType":"modal", "modal":"#addFilterModal"}'>
                            <i class="material-icons left">add</i>
                            {{ uctrans('button.add_filter', $module) }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="delete-filter" @if(!$selectedFilter || $selectedFilter->readOnly)disabled @endif>
                            <i class="material-icons left">delete</i>
                            {{ uctrans('button.delete_filter', $module) }}
                        </a>
                    </li>
                </ul>

                {{-- Export --}}
                <a class="btn-floating btn-small waves-effect primary z-depth-0" data-position="top" data-tooltip="{{ uctrans('button.export', $module) }}" data-config='{"actionType":"modal", "modal":"#exportModal"}'>
                    <i class="material-icons">cloud_download</i>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('top-action-buttons')
    <div class="action-buttons right-align">
        @yield('before-columns-visibility-button')

        {{-- Columns visibility --}}
        @section('columns-visibility-button')
        <a href="#" class="btn-small waves-effect primary dropdown-trigger" data-target="dropdown-columns" data-close-on-click="false" data-constrain-width="false" data-alignment="right">
            {!! uctrans('button.columns', $module) !!}
            <i class="material-icons">keyboard_arrow_down</i>
        </a>
        <ul id="dropdown-columns" class="dropdown-content columns" data-table="datatable">
            @foreach ($datatableColumns as $column)
            <li @if($column['visible'])class="active"@endif><a href="javascript:void(0);" class="waves-effect waves-block column-visibility" data-field="{{ $column['name'] }}">{{ uctrans('field.'.$column['name'], $module) }}</a></li>
            @endforeach
        </ul>
        @show

        @yield('after-columns-visibility-button')
        @yield('before-records-button')

        {{-- Records number --}}
        @section('records-button')
        <a href="#" class="btn-small waves-effect primary dropdown-trigger" data-target="dropdown-records-number" data-alignment="right">
            {!! uctrans('filter.show_n_records', $module, ['number' => '<strong class="records-number">'.($selectedFilter->data->length ?? 15).'</strong>']) !!}
            <i class="material-icons">keyboard_arrow_down</i>
        </a>
        <ul id="dropdown-records-number" class="dropdown-content records-number" data-table="datatable">
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="15">15</a></li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="30">30</a></li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="50">50</a></li>
            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="100">100</a></li>
        </ul>
        @show

        @yield('after-records-button')
    </div>
@endsection

@section('content')
    @yield('before-datatable-card')

    <div class="row clearfix">
        <div class="col s12" style="min-height: 600px">
            <div class="card">
                <div class="card-content p-t-0">
                    @yield('before-datatable-table')

                    <div>
                        @section('datatable-table')
                        <table
                            id="datatable"
                            class="striped highlight responsive-table"
                            data-filter-type="list"
                            data-filter-id="{{ $selectedFilter->id ?? '' }}"
                            data-list-url="{{ ucroute('uccello.list.content', $domain, $module, ['_token' => csrf_token()]) }}"
                            data-order="{{ !empty($selectedFilter->order_by) ? json_encode($selectedFilter->order_by) : '' }}"
                            data-length="{{ $selectedFilter->data->length ?? 15 }}">
                            <thead>
                                @section('datatable-columns-header')
                                <tr>
                                    <th class="select-column">&nbsp;</th>

                                    @foreach ($datatableColumns as $column)
                                    <th class="sortable" data-field="{{ $column['name'] }}" data-column="{{ $column['db_column'] }}" @if(!$column['visible'])style="display: none"@endif>
                                        <a href="javascript:void(0)" class="column-label">
                                            {{-- Label --}}
                                            {{ uctrans('field.'.$column['name'], $module) }}

                                            {{-- Sort icon --}}
                                            @if (!empty($filterOrderBy[$column['name']]))
                                            <i class="fa @if ($filterOrderBy[$column['name']] === 'desc')fa-sort-amount-down @else fa-sort-amount-up @endif"></i>
                                            @else
                                            <i class="fa fa-sort-amount-up" style="display: none"></i>
                                            @endif
                                        </a>
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
                                @show
                            </thead>

                            <tbody>
                                {{-- No result --}}
                                @section('datatable-no-results')
                                <tr class="no-results" style="display: none">
                                    <td colspan="{{ count($datatableColumns) + 2 }}" class="center-align">{{ uctrans('no_results', $module) }}</td>
                                </tr>
                                @show

                                {{-- Row template used by the query --}}
                                @section('datatable-row-template')
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
                                @show

                                {{-- Other rows are generated automaticaly --}}
                            </tbody>
                        </table>
                        @show

                        {{-- Loader --}}
                        @section('datatable-loader')
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
                        @show
                    </div>

                    @yield('after-datatable-table')
                    @yield('before-datatable-pagination')

                    {{-- Pagination --}}
                    @section('datatable-pagination')
                    <div class="center-align">
                        <ul class="pagination" data-table="datatable">
                        </ul>
                    </div>
                    @show

                    @yield('after-datatable-pagination')
                </div>
            </div>
        </div>
    </div>

    @yield('after-datatable-card')

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