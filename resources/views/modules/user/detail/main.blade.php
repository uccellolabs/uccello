@extends('uccello::modules.default.detail.main')

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
            <div class="input-field col s12">
                @forelse ($record->rolesOnDomain($domain) as $role)
                <a href="{{ ucroute('uccello.detail', $domain, 'role', [ 'id' => $role->id ]) }}" class="btn-small waves-effect primary">{{ $role->name }}</a>
                @empty
                <span class="red white-text" style="padding: 5px">{{ uctrans('label.no_role', $module) }}</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection