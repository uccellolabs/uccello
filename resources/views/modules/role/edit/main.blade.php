@extends('uccello::modules.default.edit.main')

@section('other-blocks')
<div class="row" style="margin-bottom: 80px">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                {{-- Title --}}
                <div class="card-title">
                    {{-- Icon --}}
                    <i class="material-icons left primary-text">lock</i>

                    {{-- Label --}}
                    {{ uctrans('block.profiles', $module) }}
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        {{ Form::select(
                            'profiles[]',
                            $profiles,
                            $selectedProfileIds,
                            [ 'multiple' => 'multiple' ]) }}
                        <label>{{ uctrans('block.profiles', $module) }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection