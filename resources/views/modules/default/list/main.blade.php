@extends('layouts.app')

@section('page', 'list')

@section('extra-meta')
    <meta name="datatable-url" content="{{ ucroute('uccello.datatable', $domain, $module) }}">
    <meta name="datatable-columns" content='{!! json_encode($datatableColumns) !!}'>
@endsection

@section('content')

@section('breadcrumb')
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb pull-left">
                {{-- Redirect to previous page. If there is not previous page, redirect to home page --}}
                <a href="{{ URL::previous() !== URL::current() ? URL::previous() : ucroute('uccello.home', $domain, $module) }}" class="pull-left">
                    <i class="material-icons" data-toggle="tooltip" data-placement="top" title="{{ uctrans('button.return', $module) }}">chevron_left</i>
                </a>

                <ol class="breadcrumb pull-left">
                    @if ($admin_env)<li><a href="">{{ uctrans('breadcrumb.admin', $module) }}</a></li>@endif
                    <li><a href="{{ ucroute('uccello.list', $domain, $module) }}">{{ uctrans($module->name, $module) }}</a></li>
                    <li class="active">{{ uctrans('filter.all', $module) }}</li>
                </ol>
            </div>

            <div id="action-buttons">

                <div class="btn-group m-l-10">
                    <button type="button" class="btn bg-primary icon-right waves-effect pull-right dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Show <strong class="records-number">10</strong> records
                        <i class="material-icons">keyboard_arrow_down</i>
                    </button>
                    <ul id="items-number" class="dropdown-menu">
                        <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="1">1</a></li>
                        <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="10">10</a></li>
                        <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="20">20</a></li>
                        <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="50">50</a></li>
                        <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="100">100</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @show

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    @foreach ($datatableColumns as $column)
                                    <th>{{ uctrans('field.'.$column['name'], $module) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="paginator">
            </div>
        </div>
    </div>

    @section('page-action-buttons')
    <div id="page-action-buttons">
        <a href="{{ ucroute('uccello.edit', $domain, $module) }}" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.new', $module) }}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">add</i>
        </a>
    </div>
    @show
@endsection