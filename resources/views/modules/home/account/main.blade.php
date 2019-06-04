@extends('layouts.uccello')

@section('page', 'user-account')

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                {{-- Module icon --}}
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute('uccello.list', $domain, $module) }}">
                        <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                        <span class="hide-on-small-only">{{ uctrans($module->name, $module) }}</span>
                    </a>
                </span>
                <span class="breadcrumb active">Mon compte</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col s12 m4">
            <div class="card profile-card">
                <div class="card-content primary center-align profile-image">
                    <img src="{{ 'https://www.gravatar.com/avatar/' . md5(auth()->user()->email) . '?d=mm' }}" alt="{{ $user->name }}" class="circle">
                </div>
                <div class="card-content center-align">
                    <h4 style="margin-top: 30px; margin-bottom: 0">{{ $user->name }}</h4>
                    <span class="grey-text">{{ $user->email }}</span>

                    <div style="margin-top: 20px">
                    @foreach($user->rolesOnDomain($domain) as $role)
                        <span class="primary-text" style="margin: 7px">{{ $role->name }}</span>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col s12 m8">
            <div class="card">
                <div class="card-tabs">
                    <ul class="tabs grey lighten-4">
                        <li class="tab">
                            <a @if(!session('form_name') || session('form_name') === 'profile')class="active"@endif href="#change_profile">
                                <i class="material-icons left">person</i>
                                {{ uctrans('account.profile', ucmodule('user')) }}
                            </a>
                        </li>
                        <li class="tab">
                            <a @if(session('form_name') === 'password')class="active"@endif href="#change_password">
                                <i class="material-icons left">lock</i>
                                {{ uctrans('account.password', ucmodule('user')) }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-content">
                    <div id="change_profile">
                        <form action="{{ ucroute('uccello.user.profile.update', $domain) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="input-field col s12 @if($errors->has('username'))invalid @endif">
                                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}">
                                    <label for="username" class="required">{{ uctrans('field.username', ucmodule('user')) }}</label>
                                    @if ($errors->has('username'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('username') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="input-field col s12 @if($errors->has('name'))invalid @endif">
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
                                    <label for="name" class="required">{{ uctrans('field.name', ucmodule('user')) }}</label>
                                    @if ($errors->has('name'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('name') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="input-field col s12 @if($errors->has('email'))invalid @endif">
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
                                    <label for="email" class="required">{{ uctrans('field.email', ucmodule('user')) }}</label>
                                    @if ($errors->has('email'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="s12 center-align">
                                    <button type="submit" class="btn waves-effect primary">{{ uctrans('button.save', $module) }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="change_password">
                        <form action="{{ ucroute('uccello.user.password.update', $domain) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="input-field col s12 @if($errors->has('current_password'))invalid @endif">
                                    <input type="password" id="current_password" name="current_password">
                                    <label for="current_password" class="required">{{ uctrans('field.current_password', ucmodule('user')) }}</label>
                                    @if ($errors->has('current_password'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('current_password') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="input-field col s12 @if($errors->has('password'))invalid @endif">
                                    <input type="password" id="password" name="password">
                                    <label for="password" class="required">{{ uctrans('field.new_password', ucmodule('user')) }}</label>
                                    @if ($errors->has('password'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('password') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="input-field col s12 @if($errors->has('password_confirmation'))invalid @endif">
                                    <input type="password" id="password_confirmation" name="password_confirmation">
                                    <label for="password_confirmation" class="required">{{ uctrans('field.new_password_confirm', ucmodule('user')) }}</label>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('password_confirmation') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="s12 center-align">
                                    <button type="submit" class="btn waves-effect primary">{{ uctrans('button.save', $module) }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection