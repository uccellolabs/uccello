@extends('uccello::modules.default.edit.main')

@section('other-blocks')
<div class="block-header">
    <h2>{{ uctrans('block.permissions', $module) }}</h2>
</div>

@foreach ($modules as $_module)
<?php if (!Auth::user()->canAdmin($domain, $_module)) continue; ?>
{{-- <strong></strong> --}}
<div class="card block">
    <div class="header">
        <h2>                    
            <div class="block-label-with-icon">
                {{-- Icon --}}
                @if ($_module->icon)
                <i class="material-icons">{{ $_module->icon }}</i>
                @endif

                {{-- Label --}}
                <span>{{ uctrans($_module->name, $_module) }}</span>
                {{-- <span>{{ uctrans('block.permissions', $module) }}</span> --}}
            </div>

            {{-- Description --}}
            {{-- <small>{{ uctrans('block.permissions.description', $module) }}</small>  --}}
        </h2>
    </div>
    <div class="body">
        <div class="row">
            @foreach (uccello()->getCapabilities() as $capability)
                <div class="col-md-3 col-sm-6 switch">
                    <label>
                        <input type="checkbox" name="capabilities[{{ $_module->name }}][{{ $capability }}]">
                        <span class="lever switch-col-blue"></span>
                        {{ uctrans('capability.' . $capability, $module) }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endforeach
@endsection