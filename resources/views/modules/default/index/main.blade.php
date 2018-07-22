@extends('layouts.app')

@section('page', 'index')

@section('content')
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-12 align-center">
                        {{ Html::image('img/logo-uccello.png', null, ['style' => 'max-width: 500px']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection