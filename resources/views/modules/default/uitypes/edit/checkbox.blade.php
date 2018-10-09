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
            <div style="margin-top: 20px;">
                {{-- Field --}}
                {!! form_widget($form->{$field->name}) !!}
                {{-- Label --}}
                {!! form_label($form->{$field->name}, ['label_attr' => ['class' => 'checkbox-label']]) !!}
            </div>
        </div>

        @if($isError)
        <div class="help-info">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif
    </div>
</div>