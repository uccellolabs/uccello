@extends('uccello::layouts.main')

@section('body-extra-class', 'no-sidebar')

@section('content')
    <div class="row" style="margin-top: 10%">
        <div class="col s12 m8 offset-m2 l4 offset-l4">
            {{-- Form --}}
            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <div class="card">
                    <div class="card-content">
                        <div class="center-align">
                            {{ Html::image(ucasset('images/logo-uccello.png'), null, ['width' => '250']) }}
                        </div>

                        <div class="row" style="margin-top: 30px">
                            {{-- Name --}}
                            <div class="input-field col s12">
                                {{-- Field --}}
                                <i class="material-icons prefix">person</i>
                                <input id="name" type="text" name="name" class="{{ $errors->has('name') ? 'invalid' : '' }}" value="{{ old('name') }}" autofocus>
                                <label for="name">{{ __('uccello::auth.field.name') }}</label>

                                {{-- Error message --}}
                                @if ($errors->has('name'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- Email --}}
                            <div class="input-field col s12">
                                {{-- Field --}}
                                <i class="material-icons prefix">email</i>
                                <input id="email" type="email" name="email" class="{{ $errors->has('email') ? 'invalid' : '' }}" value="{{ old('email') }}" autofocus>
                                <label for="email">{{ __('uccello::auth.field.email') }}</label>

                                {{-- Error message --}}
                                @if ($errors->has('email'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- Password --}}
                            <div class="input-field col s12">
                                {{-- Field --}}
                                <i class="material-icons prefix">lock</i>
                                <input id="password" type="password" name="password" class="{{ $errors->has('password') ? 'invalid' : '' }}">
                                <label for="password">{{ __('uccello::auth.field.password') }}</label>

                                {{-- Error message --}}
                                @if ($errors->has('password'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            {{-- Password confirmation --}}
                            <div class="input-field col s12">
                                {{-- Field --}}
                                <i class="material-icons prefix">lock</i>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="{{ $errors->has('password_confirmation') ? 'invalid' : '' }}">
                                <label for="password_confirmation">{{ __('uccello::auth.field.password_confirmation') }}</label>

                                {{-- Error message --}}
                                @if ($errors->has('password_confirmation'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="card-action right-align">
                        <a class="btn-flat waves-effect primary-text" href="{{ route('login') }}">{{ __('uccello::auth.button.signin')  }}</a>
                        <button class="btn-small waves-effect primary" type="submit">{{ __('uccello::auth.button.signup') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection