@extends('uccello::layouts.error')

@section('content-title', '500 Error Page')
@section('content-subtitle', '')

@section('body-extra-class')
five-zero-zero
@endsection

@section('content-container')
<div class="five-zero-zero-container p-t-100">
    <div class="error-code">500</div>
    <div class="error-message">{{ trans('uccello::default.error.server') }}</div>
    <div class="button-place">
        <a href="/" class="btn bg-green btn-lg waves-effect icon-right">
            <i class="material-icons">home</i>
            {{ trans('uccello::default.error.homepage') }}
        </a>

        <a href="/logout" class="btn bg-red btn-lg waves-effect icon-right">
            <i class="material-icons">logout</i>
            {{ trans('uccello::default.error.logout') }}
        </a>
    </div>
</div>
@endsection

@section('script')
    {{ Html::script(ucasset('js/manifest.js')) }}
    {{ Html::script(ucasset('js/vendor.js')) }}
@endsection