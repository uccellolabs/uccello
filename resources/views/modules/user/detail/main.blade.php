@extends('uccello::modules.default.detail.main')

{{-- Edit password for admin --}}
@if (auth()->user()->canAdmin($domain, $module))
    {{-- Button --}}
    @section('top-action-buttons')
    <div class="action-buttons right-align">
        <a href="#changePasswordModal"
            class="btn-floating btn-small waves-effects orange modal-trigger"
            data-tooltip="{{ uctrans('button.change_password', $module) }}"
            data-position="top">
            <i class="material-icons left">lock</i>
        </a>
    </div>
    @append

    {{-- Modal --}}
    @section('extra-content')
    <div id="changePasswordModal" class="modal modal-fixed-footer" @if($errors->all())data-open="true"@endif>
        <form action="{{ ucroute('uccello.user.password.update_by_admin', $domain, $module, ['id' => $record->getKey()]) }}" method="POST">
            @csrf
            <div class="modal-content">
                <h5>
                    <i class="material-icons left primary-text">lock</i>
                    {{ uctrans('modal.change_password.title', $module) }}
                </h4>

                <div class="row">
                    <div class="col s12">
                        <div class="input-field">
                            <input id="new_password" name="password" type="password">
                            <label for="new_password" class="required">{{ uctrans('modal.change_password.new_password', $module) }}</label>

                            {{-- Error message --}}
                            @if ($errors->has('password'))
                            <span class="helper-text red-text">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                        </div>

                        <div class="input-field">
                            <input id="new_password_confirmation" name="password_confirmation" type="password">
                            <label for="new_password_confirmation" class="required">{{ uctrans('modal.change_password.new_password_confirmation', $module) }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" class="btn-flat waves-effect modal-close">{{ uctrans('button.cancel', $module) }}</a>
                <button type="submit" class="btn-flat waves-effect green-text save">{{ uctrans('button.save', $module) }}</button>
            </div>
        </form>
    </div>
    @append
@endif

@section('other-blocks')
<div class="card" style="margin-bottom: 80px">
    <div class="card-content">
        {{-- Title --}}
        <div class="card-title">
            {{-- Icon --}}
            <i class="material-icons left primary-text">lock</i>

            {{-- Label --}}
            {{ uctrans('block.roles', $module) }}
        </div>

        <div class="row">
            <div class="col s12">
                <ul>
                    @forelse ($record->privilegesOnDomain($domain) as $privilege)
                    <li class="collection-item">
                        <a href="{{ ucroute('uccello.detail', $domain, 'role', [ 'id' => $privilege->role->id ]) }}"
                            class="btn-flat waves-effect primary-text">
                            @if ($privilege->role->domain_id !== $domain->id)
                            {{ $privilege->role->domain->name }} > {{ $privilege->role->name }}
                            @else
                            {{ $privilege->role->name }}
                            @endif
                        </a>

                        {{-- Display in which domain the privilege was defined --}}
                        {{ uctrans('label.defined_in', $module)}} <b>{{ $privilege->domain->name }}</b>
                    </li>

                    @empty
                    <li class="collection-item">
                        <span class="red-text" style="padding: 5px">{{ uctrans('label.no_role', $module) }}</span>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('other-tabs-links')
<li class="tab">
    <a href="#relatedlist_connection_log">
        {{-- Icon --}}
        <i class="material-icons left">login</i>

        {{-- Label --}}
        <span>{{ uctrans('relatedlist.connection_log', $module) }}</span>
    </a>
</li>
@endsection

@section('other-tabs')
<div id="relatedlist_connection_log">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    {{-- Title --}}
                    <span class="card-title">
                        {{-- Icon --}}
                        <i class="material-icons left primary-text">login</i>

                        {{-- Label --}}
                        {{ uctrans('relatedlist.connection_log', $module) }}
                    </span>

                    <div class="row">
                        <div class="col s12">
                            <p>
                                <b>{{ uctrans('label.total_connections', $module) }}</b>
                                {{ $connectionsCount }}
                            </p>

                            @if ($firstConnection)
                            <p>
                                <b>{{ uctrans('label.first_connection', $module) }}</b>
                                {{ $firstConnection->datetime->format(config('uccello.format.php.datetime')) }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <p>
                                <b>
                                    @if ($connectionsCount > config('uccello.users.max_connections_displayed'))
                                        {{ config('uccello.users.max_connections_displayed') }}
                                        {{ strtolower(uctrans('label.last_connections', $module)) }}
                                    @else
                                        {{ uctrans('label.last_connections', $module) }}
                                    @endif
                                </b>
                            </p>

                            <ul class="collection">
                                @forelse ($connections as $connection)
                                <li class="collection-item">{{ $connection->datetime->format(config('uccello.format.php.datetime')) }}</li>
                                @empty
                                <li class="collection-item">{{ uctrans('label.no_connections', $module) }}</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
