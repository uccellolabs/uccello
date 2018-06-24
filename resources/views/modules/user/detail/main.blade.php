@extends('uccello::modules.default.detail.main')

@section('other-blocks')
<div class="card block">
    <div class="header">
        <h2>
            <div class="block-label-with-icon">
                {{-- Icon --}}
                @if ($module->icon)
                <i class="material-icons">lock</i>
                @endif

                {{-- Label --}}
                <span>{{ uctrans('block.roles', $module) }}</span>
            </div>
        </h2>
    </div>
    <div class="body">
        <div class="row">
            <div class="col-md-12">
                @forelse ($record->rolesOnDomain($domain) as $role)
                <label class="label label-primary font-13">{{ $role->name }}</label>
                @empty
                <label class="label bg-deep-orange font-13">{{ uctrans('no_role', $module) }}</label>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection