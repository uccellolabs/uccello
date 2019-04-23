@extends('uccello::modules.default.detail.main')

@section('other-blocks')
<div class="card" style="margin-bottom: 80px">
    <div class="card-content">
        {{-- Title --}}
        <div class="card-title">
            {{-- Icon --}}
            <i class="material-icons left primary-text">lock</i>

            {{-- Label --}}
            {{ uctrans('block.profiles', $module) }}
        </div>

        <div class="row">
            <div class="col s12">
                @forelse ($record->profiles->where('domain_id', $domain->id) as $profile)
                <a href="{{ ucroute('uccello.detail', $domain, 'profile', [ 'id' => $profile->id ]) }}" class="btn-flat waves-effect primary-text">{{ $profile->name }}</a>
                @empty
                <span class="red-text" style="padding: 5px">{{ uctrans('label.no_profile', $module) }}</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection