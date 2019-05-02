@extends('uccello::modules.default.edit.main')

@section('other-blocks')
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                {{-- Title --}}
                <div class="card-title">
                    {{-- Icon --}}
                    <i class="material-icons left primary-text">lock</i>

                    {{-- Label --}}
                    {{ uctrans('block.permissions', $module) }}

                    <div class="switch right api-switch hide">
                        <label>
                            <span class="black-text">{{ uctrans('label.api_capabilities', $module) }}</span>
                            <input type="checkbox" id="manage-api-capabilities">
                            <span class="lever"></span>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <table id="permissions-table" class="striped highlight">
                            <thead>
                                <tr>
                                    <th>
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                class="filled-in select-all" />
                                                <span class="black-text">
                                                    {{ uctrans('label.modules', $module) }}
                                                </span>
                                            </label>
                                        </p>
                                    </th>
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
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                class="filled-in select-row" />
                                                <span class="black-text">
                                                    <i class="material-icons left">{{ $_module->icon ?? 'extension' }}</i>
                                                    {{ uctrans($_module->name, $_module) }}
                                                </span>
                                            </label>
                                        </p>
                                    </td>

                                    @foreach (uccello()->getCapabilities() as $capability)
                                    <?php $isApiCapability = strpos($capability->name, 'api-') !== false; ?>
                                    <td class="center-align @if ($isApiCapability)for-api hide @endif">
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                class="filled-in select-item"
                                                name="{{ 'permissions['.$_module->name.']['.$capability->name.']' }}"
                                                value="1"
                                                @if ($record->hasCapabilityOnModule($capability, $_module))checked="checked"@endif />
                                                <span></span>
                                            </label>
                                        </p>
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
    </div>
</div>
@endsection

@section('uccello-extra-script')
    {{ Html::script(mix('js/profile/autoloader.js', 'vendor/uccello/uccello')) }}
@append