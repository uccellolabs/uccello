@extends('uccello::modules.default.edit.main')

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
                <div class="form-group">
                    {{ Form::select(
                        'profiles[]',
                        $profiles,
                        $selectedProfileIds,
                        ['multiple' => 'multiple', 'class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection