@extends('uccello::modules.default.index.main')

@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col m6 offset-m3 align-center">
                            {{ Html::image(ucasset('images/logo-uccello.png'), null, ['class' => 'responsive-img']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection