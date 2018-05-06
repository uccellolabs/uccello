<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col-md-6">
    <div class="form-group">
        {{-- Label --}}
        {!! form_label($form->{$field->name}) !!}

        <div class="input-group">            
            {{-- Icon if defined --}}
            @if($field->icon ?? false)
            <span class="input-group-addon">
                <i class="material-icons">{{ $field->icon }}</i>
            </span>
            @endif

            {{-- Field --}}
            <div class="switch" style="padding-top: 10px; padding-bottom: 5px;">
                <label>
                    {{ uctrans('no', $module) }}
                    {!! form_widget($form->{$field->name}) !!}
                    <span class="lever"></span>
                    {{ uctrans('yes', $module) }}
                </label>
            </div>
        </div>

        @if($isError)
        <div class="help-info">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif
    </div>
</div>