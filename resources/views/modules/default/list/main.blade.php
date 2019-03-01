@extends('layouts.app')

@section('page', 'list')

@section('extra-meta')
    <meta name="datatable-url" content="{{ ucroute('uccello.datatable', $domain, $module) }}">
@endsection

@section('content-class', 'dataTable-container listview')

@section('breadcrumb')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <div class="breadcrumb pull-left">
                        {{-- Module icon --}}
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

                        {{-- Export --}}
                        <div class="pull-right export">
                            <a href="javascript:void(0)" class="action-button" data-config='{"actionType":"modal", "modal":"#exportModal"}'>
                                <i class="material-icons bg-primary" data-toggle="tooltip" data-placement="top" title="{{ uctrans('button.export', $module) }}">cloud_download</i>
                            </a>
                        </div>

                        {{-- Manage filters --}}
                        <div class="pull-right manage-filters">
                            <a href="javascript:void(0);" class="action-button dropdown-toggle" data-toggle="dropdown">
                                <i class="material-icons bg-green" data-toggle="tooltip" data-placement="top" title="{{ uctrans('button.manage_filters', $module) }}">filter_list</i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="btn btn-link btn-block add-filter" data-config='{"actionType":"modal", "modal":"#addFilterModal"}'>
                                        <i class="material-icons">add</i>
                                        <span>{{ uctrans('button.add_filter', $module) }}</span>
                                    </button>
                                </li>
                                <li>
                                    <button class="btn btn-link btn-block delete-filter" @if(!$selectedFilter || $selectedFilter->readOnly)disabled @endif>
                                        <i class="material-icons">delete</i>
                                        <span>{{ uctrans('button.delete_filter', $module) }}</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="action-buttons col-sm-6 col-xs-12">
                    <div class="btn-group m-l-10 pull-right">
                        <button type="button" class="btn bg-primary icon-right waves-effect pull-right dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {!! uctrans('filter.show_n_records', $module, ['number' => '<strong class="records-number">'.($selectedFilter->data->length ?? 15).'</strong>']) !!}
                            <i class="material-icons">keyboard_arrow_down</i>
                        </button>
                        <ul id="items-number" class="dropdown-menu">
                            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="15">15</a></li>
                            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="30">30</a></li>
                            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="50">50</a></li>
                            <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="100">100</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @yield('before-table')

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 card-container" style="min-height: 600px">
            <div class="card">
                <div class="body p-t-0">
                    <div class="table-responsive" style="min-height: 300px">
                        <table
                            class="table table-striped table-hover dataTable"
                            data-filter-type="list"
                            data-filter-id="{{ $selectedFilter->id ?? '' }}">
                            <thead>
                                <tr>
                                    <th class="select-column">&nbsp;</th>

                                    @foreach ($datatableColumns as $column)
                                    <th data-name="{{ $column['name'] }}" data-label="{{ uctrans('field.'.$column['name'], $module) }}">
                                        {{ uctrans('field.'.$column['name'], $module) }}
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
                                    </th>
                                    @endforeach

                                    <th class="actions-column hidden-xs">
                                        <a class="clear-search pull-left col-red" title="{{ uctrans('button.clear_search', $module) }}" data-toggle="tooltip" data-placement="top"><i class="material-icons">close</i></a>
                                    </th>
                                </tr>
                            </thead>
                        </table>

                        <div class="row loader m-t-100">
                            <div class="col-md-12 align-center">
                                <div class="preloader pl-size-xl">
                                    <div class="spinner-layer pl-light-green">
                                        <div class="circle-clipper left">
                                            <div class="circle"></div>
                                        </div>
                                        <div class="circle-clipper right">
                                            <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="paginator m-b-25">
            </div>
        </div>
    </div>

    @yield('after-table')

    @section('page-action-buttons')
        {{-- Create button --}}
        @if (Auth::user()->canCreate($domain, $module))
        <div id="page-action-buttons">
            <a href="{{ ucroute('uccello.edit', $domain, $module) }}" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.new', $module) }}" data-toggle="tooltip" data-placement="top">
                <i class="material-icons">add</i>
            </a>
        </div>
        @endif
    @show

    {{-- Template to use in the table --}}
    <div class="template hide">
        @if (Auth::user()->canUpdate($domain, $module))
        <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => 'RECORD_ID']) }}" title="{{ uctrans('button.edit', $module) }}" class="edit-btn hidden-xs"><i class="material-icons">edit</i></a>
        @endif

        @if (Auth::user()->canDelete($domain, $module))
        <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => 'RECORD_ID']) }}" title="{{ uctrans('button.delete', $module) }}" class="delete-btn hidden-xs" data-config='{"actionType":"link","confirm":true,"dialog":{"title":"{{ uctrans('button.delete.confirm', $module) }}"}}'><i class="material-icons">delete</i></a>
        @endif
    </div>
@endsection

@section('extra-content')
    {{-- Add filter modal --}}
    @include("uccello::modules.default.list.modal.add-filter")
    {{-- Export modal --}}
    @include("uccello::modules.default.list.modal.export")
@endsection