@extends('uccello::layouts.main')

@section('body-class')
fp-page
@endsection

@section('content')
<div class="fp-box">
    <div class="logo">
        <a href="javascript:void(0);">Uccello</b></a>
        <small>Advanced Admin Panel - For Laravel</small>
    </div>
    <div class="card">
        <div class="body">
            <form id="forgot_password" method="POST" action="{{ route('password.request') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="msg">
                    Enter your new password.
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">email</i>
                    </span>
                    <div class="form-line">
                        <input id="email" type="email" placeholder="{{ __('E-Mail Address') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input id="password-confirm" type="password" placeholder="{{ __('Confirm Password') }}" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" required>

                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <button class="btn btn-block btn-lg bg-primary waves-effect" type="submit">{{ __('Reset Password') }}</button>

                <div class="row m-t-20 m-b--5 align-center">
                    <a href="{{ route('register') }}">{{ __('Register') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    {{ Html::script(mix('js/manifest.js', 'vendor/uccello/uccello')) }}
    {{ Html::script(mix('js/vendor.js', 'vendor/uccello/uccello')) }}
@endsection