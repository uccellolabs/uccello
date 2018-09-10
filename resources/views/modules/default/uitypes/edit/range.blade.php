<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col-md-6">
    <div class="form-group form-fixed">
        {{-- Label --}}
        {!! form_label($form->{$field->name}) !!}

        <div class="input-field">
            {{-- Icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            {{-- Field --}}
            <div style="padding-top: 10px; padding-bottom: 5px;">
                <div id="nouislider_range"></div>
                {!! form_widget($form->{$field->name}) !!}
            </div>
        </div>

        @if($isError)
        <div class="help-info">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif
    </div>
</div>