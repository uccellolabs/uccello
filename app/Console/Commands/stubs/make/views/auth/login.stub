@extends('uccello::layouts.main')

@section('body-class')
login-page
@endsection

@section('content')
<div class="login-box">
    <div class="logo">
        <a href="javascript:void(0);">Uccello</a>
        <small>Advanced Admin Panel - For Laravel</small>
    </div>
    <div class="card">
        <div class="body">
            <form id="sign_in" method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="msg">Sign in to start your session</div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input id="identity" type="text" class="form-control{{ $errors->has('identity') ? ' is-invalid' : '' }}" name="identity" value="{{ old('identity') }}" required autofocus>
                    </div>
                    @if ($errors->has('identity'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('identity') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                    </div>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8 p-t-5">
                        <input type="checkbox" name="rememberme" id="rememberme" {{ old('remember') ? 'checked' : '' }} class="filled-in chk-col-pink">
                        <label for="rememberme">{{ __('Remember Me') }}</label>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn btn-block bg-primary waves-effect" type="submit">{{ __('Login') }}</button>
                    </div>
                </div>
                <div class="row m-t-15 m-b--20">
                    <div class="col-xs-6">
                        <a href="{{ route('register') }}">Register Now!</a>
                    </div>
                    <div class="col-xs-6 align-right">
                        <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    {{ Html::script(ucasset('js/manifest.js')) }}
    {{ Html::script(ucasset('js/vendor.js')) }}
@endsection