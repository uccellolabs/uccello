@extends('layouts.app')

@section('extra-script')
{{ Html::script(mix('js/list.js')) }}

<script>
var domainSlug = '{{ $domain->slug }}';
var moduleName = '{{ $module->name }}';
var csrfToken = $('meta[name="csrf-token"]').attr('content');
var filterColumns = JSON.parse('{!! json_encode($filter->columns) !!}');
</script>
@endsection

@section('content')
    <a href="{{ route('edit', ['domain' => $domain->slug, 'module' => $module->name]) }}" class="btn btn-success">
    {{ uctrans('add_record', $module) }}</a>

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
                        <table class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    @foreach ($filter->columns as $column)
                                    <th>{{ uctrans('field.'.$column, $module) }}</th>
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