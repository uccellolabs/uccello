@extends('uccello::layouts.error')

@section('content-title', '403 Error Page')
@section('content-subtitle', '')

@section('body-extra-class')
four-zero-four
@endsection

@section('content-container')
<div class="four-zero-four-container p-t-100">
    <div class="error-code">403</div>
    <div class="error-message">{{ trans('uccello::default.error.not.allowed') }}</div>
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
    {{ Html::script(mix('js/manifest.js', 'vendor/uccello/uccello')) }}
    {{ Html::script(mix('js/vendor.js', 'vendor/uccello/uccello')) }}
@endsection