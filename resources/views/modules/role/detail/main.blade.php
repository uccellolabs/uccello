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
                <span>{{ uctrans('block.profiles', $module) }}</span>
            </div>
        </h2>
    </div>
    <div class="body">
        <div class="row">
            <div class="col-md-12">
                @forelse ($record->profiles->where('domain_id', $domain->id) as $profile)
                <label class="label label-primary font-13">{{ $profile->name }}</label>
                @empty
                <label class="label label-warning font-13">{{ uctrans('no_profile', $module) }}</label>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection