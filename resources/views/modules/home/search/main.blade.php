@extends('layouts.uccello')

@section('page', 'search')

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.home', $domain) }}">
                        <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                        <span class="hide-on-small-only">{{ uctrans('home', $module) }}</span>
                    </a>
                </span>
                <span class="breadcrumb">
                    <a class="btn-flat" href="javascript:void(0)">
                        <i class="material-icons left">search</i>
                        <span class="hide-on-small-only">{{ uctrans('breadcrumb.search', $module) }}</span>
                    </a>
                </span>
                <span class="breadcrumb active">{{ ucfirst(request('q')) }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        @foreach($searchResults->groupByType() as $moduleName => $modelSearchResults)
        <?php $_module = ucmodule($moduleName); ?>
        <div class="col s12 m6">
            <div class="card ">
                <div class="card-content">
                    <span class="card-title">
                        <i class="material-icons left primary-text">{{ $_module->icon ?? 'extension' }}</i>
                        {{ uctrans($moduleName, $_module) }}
                        <span class="badge green white-text" style="margin-top: 5px">{{ $modelSearchResults->count() }}</span>
                    </span>

                    <div class="collection">                    
                    @foreach($modelSearchResults as $searchResult)
                        <a href="{{ ucroute('uccello.detail', $domain, $moduleName, [ 'id' => $searchResult->searchable->id ]) }}" class="collection-item primary-text">{{ $searchResult->title }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @endforeach
    </div>
    

    
@endsection