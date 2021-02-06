@extends('uccello::layouts.main')

@section('body-extra-class', 'no-sidebar')

@section('css')
    {{-- {!! Html::style(mix('css/app.css')) !!} --}}
@append

@section('content')
    <div class="row" style="margin-top: 10%">
        <div class="col s12 m8 offset-m2 l4 offset-l4">
            @if (session('status'))
                {{-- Confirmation --}}
                <div class="card">
                    <div class="card-content">
                        <div class="center-align">
                            {{ Html::image(ucasset('images/logo-uccello.png'), null, ['width' => '250']) }}
                        </div>

                        <p class="center-align" style="margin-top: 30px">{{ session('status') }}</p>
                    </div>
                </div>
            @else
                {{-- Form --}}
                <form method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf
                    <div class="card">
                        <div class="card-content">
                            <div class="center-align">
                                {{ Html::image(ucasset('images/logo-uccello.png'), null, ['width' => '250']) }}
                            </div>

                            <p style="margin-top: 30px">{!! __('uccello::auth.info.password.reset') !!}</p>

                            <div class="row" style="margin-top: 30px">
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
                            </div>

                        </div>
                        <div class="card-action right-align">
                            <a class="btn-flat waves-effect primary-text" href="{{ route('login') }}">{{ __('uccello::auth.button.signin')  }}</a>
                            <button class="btn-small waves-effect primary" type="submit">{{ __('uccello::auth.button.password.send_link') }}</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection
