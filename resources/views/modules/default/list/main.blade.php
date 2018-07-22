@extends('layouts.app')

@section('page', 'list')

@section('extra-meta')
    <meta name="datatable-url" content="{{ ucroute('uccello.datatable', $domain, $module) }}">
    <meta name="datatable-columns" content='{!! json_encode($datatableColumns) !!}'>
@endsection

@section('content')
    <div id="action-buttons" class="m-b-10">
        <a href="{{ ucroute('uccello.edit', $domain, $module) }}" class="btn btn-success">
        {{ uctrans('add_record', $module) }}
        </a>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                {{-- <div class="header">
                    <h2>
                        BASIC EXAMPLE
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                </div> --}}
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
        </div>
    </div>
@endsection