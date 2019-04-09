@extends('uccello::layouts.main')

@section('body-extra-class', 'no-sidebar')

@section('content')
    <div class="row" style="margin-top: 10%">
        <div class="col s12 m8 offset-m2 l4 offset-l4">
            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="card">
                    <div class="card-content">
                        <div class="center-align">
                            {{ Html::image(ucasset('images/logo-uccello.png'), null, ['width' => '250']) }}
                        </div>

                        <div class="row" style="margin-top: 30px">
                            {{-- Identity --}}
                            <div class="input-field col s12">
                                {{-- Field --}}
                                <i class="material-icons prefix">person</i>
                                <input id="identity" type="text" name="identity" class="{{ $errors->has('identity') ? 'invalid' : '' }}" value="{{ old('identity') }}" autofocus>
                                <label for="identity">{{ __('uccello::auth.field.identity') }}</label>

                                {{-- Error message --}}
                                @if ($errors->has('identity') || $errors->has('username') || $errors->has('email'))
                                    <span class="helper-text red-text">
                                        <strong>{{ $errors->first('identity') ?: $errors->first('username') ?: $errors->first('email') }}</strong>
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
                        </div>

                    </div>
                    <div class="card-action right-align">
                        <a class="btn-flat waves-effect primary-text" href="{{ route('password.request') }}">{{ __('uccello::auth.button.password.lost')  }}</a>
                        <button class="btn-small waves-effect primary" type="submit">{{ __('uccello::auth.button.signin') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection