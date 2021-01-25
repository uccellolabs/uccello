@extends('uccello::modules.default.detail.main')

@section('other-blocks')
<div class="card" style="margin-bottom: 80px">
    <div class="card-content">
        {{-- Title --}}
        <div class="card-title">
            {{-- Icon --}}
            <i class="material-icons left primary-text">lock</i>

            {{-- Label --}}
            {{ uctrans('block.permissions', $module) }}
        </div>

        <div class="row">
            <div class="col s12">
                <table id="permissions-table" class="striped highlight">
                    <thead>
                        <tr>
                            <th>{{ uctrans('label.modules', $module) }}</th>
                            @foreach (uccello()->getCapabilities() as $capability)
                                <?php
                                    $isApiCapability = strpos($capability->name, 'api-') !== false;
                                    $packagePrefix = $capability->package ? $capability->package . '::' : '';
                                ?>
                                <th class="center-align @if ($isApiCapability)for-api hide @endif">{{ trans($packagePrefix . 'capability.' . $capability->name) }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                    @foreach ($modules as $_module)
                        @continue(!Auth::user()->canAdmin($domain, $_module) || !$_module->isActiveOnDomain($domain))
                        <tr>
                            <td>
                                <i class="material-icons left">{{ $_module->icon ?? 'extension' }}</i>
                                {{ uctrans($_module->name, $_module) }}
                            </td>

                            @foreach (uccello()->getCapabilities() as $capability)
                            <?php $isApiCapability = strpos($capability->name, 'api-') !== false; ?>
                            <td class="center-align @if ($isApiCapability)for-api hide @endif" @if ($record->hasCapabilityOnModule($capability, $_module))data-checked="true"@endif>
                                @if (uccello()->isCrudModule($_module)
                                    || !in_array($capability->name, ['create', 'update', 'delete', 'api-retrieve', 'api-create', 'api-update', 'api-delete']))
                                    @if ($record->hasCapabilityOnModule($capability, $_module))
                                        <i class="material-icons green-text">check</i>
                                    @else
                                        <i class="material-icons red-text">close</i>
                                    @endif
                                @endif
                            </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('uccello-extra-script')
    {{ Html::script(mix('js/profile/autoloader.js', 'vendor/uccello/uccello')) }}
@append
