@extends('layouts.uccello')

@section('page', 'user-account')

<?php $userModule = ucmodule('user'); ?>

@section('breadcrumb')
    <div class="nav-wrapper">
        <div class="col s12">
            <div class="breadcrumb-container left">
                {{-- Module icon --}}
                <span class="breadcrumb">
                    <a class="btn-flat" href="{{ ucroute($module->defaultRoute, $domain, $module) }}">
                        <i class="material-icons left">{{ $module->icon ?? 'extension' }}</i>
                        <span class="hide-on-small-only">{{ uctrans($module->name, $module) }}</span>
                    </a>
                </span>
                <span class="breadcrumb active">{{ uctrans('label.my_account', $userModule) }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row user-account">
        <div class="col s12 m4">
            <div class="card profile-card">
                {{-- Avatar --}}
                <div class="card-content primary center-align profile-image">
                    {{-- Initials --}}
                    <div class="circle initials" @if($user->avatarType !== 'initials')style="display: none"@endif>{{ $user->initials }}</div>

                    {{-- Gravatar --}}
                    <img src="{{ 'https://www.gravatar.com/avatar/' . md5($user->email) . '?d=mm' }}" alt="{{ $user->name }}" class="circle gravatar" @if($user->avatarType !== 'gravatar')style="display: none"@endif>

                    {{-- Image --}}
                    <img src="{{ asset($user->image) }}" alt="{{ $user->name }}" class="circle image" @if($user->avatarType !== 'image')style="display: none"@endif>
                </div>
                <div class="card-content center-align">
                    {{-- Name and Email --}}
                    <h4 style="margin-top: 30px; margin-bottom: 0">{{ $user->name }}</h4>
                    <span class="grey-text">{{ $user->email }}</span>

                    {{-- Roles --}}
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
                                {{ uctrans('account.profile', $userModule) }}
                            </a>
                        </li>
                        <li class="tab">
                            <a @if(session('form_name') === 'avatar')class="active"@endif href="#change_avatar">
                                <i class="material-icons left">person_pin</i>
                                {{ uctrans('account.avatar', $userModule) }}
                            </a>
                        </li>
                        <li class="tab">
                            <a @if(session('form_name') === 'password')class="active"@endif href="#change_password">
                                <i class="material-icons left">lock</i>
                                {{ uctrans('account.password', $userModule) }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-content">
                    {{-- Profile --}}
                    <div id="change_profile">
                        <form action="{{ ucroute('uccello.user.profile.update', $domain) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="input-field col s12 @if($errors->has('username'))invalid @endif">
                                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}">
                                    <label for="username" class="required">{{ uctrans('field.username', $userModule) }}</label>
                                    @if ($errors->has('username'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('username') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="input-field col s12 @if($errors->has('name'))invalid @endif">
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
                                    <label for="name" class="required">{{ uctrans('field.name', $userModule) }}</label>
                                    @if ($errors->has('name'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('name') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="input-field col s12 @if($errors->has('email'))invalid @endif">
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
                                    <label for="email" class="required">{{ uctrans('field.email', $userModule) }}</label>
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

                    {{-- Avatar --}}
                    <div id="change_avatar">
                        <form action="{{ ucroute('uccello.user.avatar.update', $domain) }}" method="POST">
                            @csrf
                            <label class="active required">{{ uctrans('label.avatar_type', $userModule) }}</label>
                            <p>
                                <label>
                                    <input class="with-gap" name="avatar_type" type="radio" value="initials" @if($user->avatarType === 'initials')checked @endif />
                                    <span class="black-text">{{ uctrans('account.avatar_type.initials', $userModule) }}</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input class="with-gap" name="avatar_type" type="radio" value="gravatar" @if($user->avatarType === 'gravatar')checked @endif />
                                    <span class="black-text">{{ uctrans('account.avatar_type.gravatar', $userModule) }}</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input class="with-gap" name="avatar_type" type="radio" value="image" @if($user->avatarType === 'image')checked @endif />
                                    <span class="black-text">{{ uctrans('account.avatar_type.image', $userModule) }}</span>
                                </label>
                            </p>

                            <p class="info" style="margin-top: 15px">
                                <i class="material-icons primary-text left">info</i>
                                <span class="avatar-initials">{{ uctrans('account.avatar_description.initials', $userModule) }}</span>
                                <span class="avatar-gravatar" style="display: none">{!! uctrans('account.avatar_description.gravatar', $userModule, ['url' => '<a href="http://www.gravatar.com" target="_blank">www.gravatar.com</a>']) !!}</span>
                                <span class="avatar-image" style="display: none"></span>
                            </p>

                            <div class="image-upload" style="margin-top: 30px; @if($user->avatarType !== 'image')display: none @endif">
                                <img id="avatar-image-preview" src="{{ $user->image }}" style="max-height: 250px; max-width: 100%">
                                <input id="avatar-file" type="file" style="display: none">

                                <div>
                                    <a href="javascript:void(0)" class="btn waves-effect green" id="upload-link">
                                        <i class="material-icons left">cloud_upload</i>
                                        {{ uctrans('account.upload_image', $userModule) }}
                                    </a>
                                </div>
                            </div>

                            <input id="avatar-input" type="hidden" name="avatar" />

                            <div class="center-align" style="margin-top: 30px">
                                <a href="javascript:void(0)" id="avatar-submit" class="btn waves-effect primary">{{ uctrans('button.save', $module) }}</a>
                            </div>
                        </form>
                    </div>

                    {{-- Password --}}
                    <div id="change_password">
                        <form action="{{ ucroute('uccello.user.password.update', $domain) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="input-field col s12 @if($errors->has('current_password'))invalid @endif">
                                    <input type="password" id="current_password" name="current_password">
                                    <label for="current_password" class="required">{{ uctrans('field.current_password', $userModule) }}</label>
                                    @if ($errors->has('current_password'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('current_password') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="input-field col s12 @if($errors->has('password'))invalid @endif">
                                    <input type="password" id="password" name="password">
                                    <label for="password" class="required">{{ uctrans('field.new_password', $userModule) }}</label>
                                    @if ($errors->has('password'))
                                        <span class="helper-text red-text">
                                            {{ $errors->first('password') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="input-field col s12 @if($errors->has('password_confirmation'))invalid @endif">
                                    <input type="password" id="password_confirmation" name="password_confirmation">
                                    <label for="password_confirmation" class="required">{{ uctrans('field.new_password_confirm', $userModule) }}</label>
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

@section('script')
    {!! Html::script(mix('js/user/autoloader.js', 'vendor/uccello/uccello')) !!}
@append