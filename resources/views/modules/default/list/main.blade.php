@extends('layouts.uccello')

@section('page', 'list')

{{-- @section('content-class', 'listview') --}}

@section('breadcrumb')
    @include('uccello::modules.default.list.breadcrumb')
@endsection

@section('top-action-buttons')
    <div class="action-buttons right-align">
        @yield('before-descendants-records-button')
        @if (auth()->user()->canSeeDescendantsRecords($domain))
        <!-- Dropdown Trigger -->
        <a class="dropdown-trigger btn-small primary" href="#" data-target="dropdown-descendants-records" data-constrain-width="false" data-alignment="right">
            <span>{{ uctrans('button.see_descendants_records', $module) }}</span>
            <i class="material-icons hide-on-med-and-down right">arrow_drop_down</i>
        </a>

        <!-- Dropdown Structure -->
        <ul id="dropdown-descendants-records" class="dropdown-content" data-table="datatable">
            <li><a href="javascript:void(0);" data-descendants-records-filter="1">{{ uctrans('yes', $module) }}</a></li>
            <li class="active"><a href="javascript:void(0);" data-descendants-records-filter="0">{{ uctrans('no', $module) }}</a></li>
        </ul>
        @endif

        @yield('after-descendants-records-button')
        @yield('before-columns-visibility-button')

        {{-- Columns visibility --}}
        @section('columns-visibility-button')
        <a href="#" class="btn-small waves-effect primary dropdown-trigger" data-target="dropdown-columns" data-close-on-click="false" data-constrain-width="false" data-alignment="right">
            <span class="hide-on-small-only">{!! uctrans('button.columns', $module) !!}</span>
            <i class="material-icons hide-on-med-and-up">view_column</i>
            <i class="material-icons hide-on-med-and-down right">arrow_drop_down</i>
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
        <a href="#" class="btn-small waves-effect primary dropdown-trigger " data-target="dropdown-records-number" data-alignment="right">
            <span class="hide-on-med-and-down">{!! uctrans('filter.show_n_records', $module, ['number' => '<strong class="records-number">'.($selectedFilter->data->length ?? 15).'</strong>']) !!}</span>
            <strong class="records-number hide-on-large-only">{{ $selectedFilter->data->length ?? 15 }}</strong>
            <i class="material-icons hide-on-med-and-down right">arrow_drop_down</i>
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

    <div class="row">
        <div class="col s12">
            <div class="card">
                {{-- Loader --}}
                @section('datatable-loader-top')
                <div class="progress transparent loader" data-table="datatable" style="margin: 0">
                    <div class="indeterminate green"></div>
                </div>
                @show

                <div class="card-content">
                    @yield('before-datatable-table')

                    <div class="autoscroll-x">
                        @section('datatable-table')
                        <table
                            id="datatable"
                            class="striped highlight"
                            data-filter-type="list"
                            data-filter-id="{{ $selectedFilter->id ?? '' }}"
                            data-list-url="{{ ucroute('uccello.list', $domain, $module) }}"
                            data-content-url="{{ ucroute('uccello.list.content', $domain, $module) }}"
                            data-export-url="{{ ucroute('uccello.export', $domain, $module) }}"
                            data-save-filter-url="{{ ucroute('uccello.list.filter.save', $domain, $module) }}"
                            data-delete-filter-url="{{ ucroute('uccello.list.filter.delete', $domain, $module) }}"
                            data-order="{{ !empty($selectedFilter->order_by) ? json_encode($selectedFilter->order_by) : '' }}"
                            data-length="{{ $selectedFilter->data->length ?? 15 }}">
                            <thead>
                                @section('datatable-columns-header')
                                <tr>
                                    <th class="select-column hide-on-small-only">
                                        <br>
                                        <i class="material-icons">search</i>
                                    </th>

                                    @foreach ($datatableColumns as $column)
                                    <th class="sortable" data-field="{{ $column['name'] }}" data-column="{{ $column['db_column'] }}" @if(!$column['visible'])style="display: none"@endif>
                                        <a href="javascript:void(0)" class="column-label">
                                            {{-- Label --}}
                                            {{ uctrans('field.'.$column['name'], $module) }}

                                            {{-- Sort icon --}}
                                            @if (!empty($filterOrderBy[$column['db_column']]))
                                            <i class="fa @if ($filterOrderBy[$column['db_column']] === 'desc')fa-sort-amount-down @else fa-sort-amount-up @endif"></i>
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
                                                $field = $module->fields()->where('name', $column['name'])->first();
                                            ?>
                                            @include($uitypeViewToInclude, [ 'field' => $field ])
                                        </div>
                                    </th>
                                    @endforeach

                                    <th class="actions-column hide-on-small-only hide-on-med-only right-align">
                                        <br>
                                        <a href="javascript:void(0)" class="btn-floating btn-small waves-effect red clear-search" data-tooltip="{{ uctrans('button.clear_search', $module) }}" data-position="top" style="display: none">
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
                                    <td colspan="100%" class="center-align">{{ uctrans('datatable.no_results', $module) }}</td>
                                </tr>
                                @show

                                {{-- Row template used by the query --}}
                                @section('datatable-row-template')
                                <tr class="template hide" data-row-url="{{ ucroute('uccello.detail', $domain, $module, ['id' => 'RECORD_ID']) }}">
                                    <td class="select-column hide-on-small-only">&nbsp;</td>

                                    @foreach ($datatableColumns as $column)
                                    <td data-field="{{ $column['name'] }}">&nbsp;</td>
                                    @endforeach

                                    <td class="actions-column hide-on-small-only hide-on-med-only right-align">
                                        @if (Auth::user()->canUpdate($domain, $module))
                                        <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => 'RECORD_ID']) }}" data-tooltip="{{ uctrans('button.edit', $module) }}" data-position="top" class="edit-btn primary-text"><i class="material-icons">edit</i></a>
                                        @endif

                                        @if (Auth::user()->canDelete($domain, $module))
                                        <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => 'RECORD_ID']) }}" data-tooltip="{{ uctrans('button.delete', $module) }}" data-position="top" class="delete-btn primary-text" data-config='{"actionType":"link","confirm":true,"dialog":{"title":"{{ uctrans('confirm.button.delete_record', $module) }}"}}'><i class="material-icons">delete</i></a>
                                        @endif
                                    </td>
                                </tr>
                                @show

                                {{-- Other rows are generated automaticaly --}}
                            </tbody>
                        </table>
                        @show

                        {{-- Loader --}}
                        @section('datatable-loader-bottom')
                        <div class="loader center-align" data-table="datatable">
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
                                {{ uctrans('datatable.loading', $module) }}
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
@append