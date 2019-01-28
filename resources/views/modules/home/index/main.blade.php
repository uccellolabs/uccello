@extends('uccello::modules.default.index.main')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 align-center">
                            {{ Html::image(ucasset('images/logo-uccello.png'), null, ['class' => 'img-responsive']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection