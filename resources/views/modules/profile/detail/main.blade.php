@extends('uccello::modules.default.detail.main')

@section('other-blocks')
<div class="block-header">
    <h2>{{ uctrans('block.permissions', $module) }}</h2>
</div>

<div class="row">
    @foreach ($modules as $_module)
        @continue(!Auth::user()->canAdmin($domain, $_module) || !$_module->isActiveOnDomain($domain))
        <div class="col-md-12">
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
                        </div>
                    </h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php $hasCapability = false; ?>
                            @foreach (uccello()->getCapabilities() as $capability)
                                @if ($record->hasCapabilityOnModule($capability, $_module))
                                <?php $hasCapability = true; ?>
                                <span class="label label-primary font-13">{{ uctrans('capability.' . $capability, $module) }}</span>
                                @endif
                            @endforeach

                            @if (!$hasCapability)
                            <span class="label label-warning font-13">{{ uctrans('capability.nothing', $module )}}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection