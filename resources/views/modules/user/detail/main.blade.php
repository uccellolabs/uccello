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
            <div class="col s12">
                <ul>
                @forelse ($record->privilegesOnDomain($domain) as $privilege)
                    <li class="collection-item">
                        <a href="{{ ucroute('uccello.detail', $domain, 'role', [ 'id' => $privilege->role->id ]) }}" class="btn-flat waves-effect primary-text">
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