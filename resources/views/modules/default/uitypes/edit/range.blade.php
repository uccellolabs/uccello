<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-sm-6 col-xs-12' }}">
    <div class="form-group form-fixed">
        {{-- Label --}}
        {!! form_label($form->{$field->name}) !!}

        <div class="input-field">
            {{-- Icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            {{-- Field --}}
            <div class="p-t-10">
            <div class="nouislider_range"
                data-min="{{ $field->data->min ?? '0' }}"
                data-max="{{ $field->data->max ?? '100' }}"
                data-start="{{ $field->data->start ?? '0' }}"
                data-step="{{ $field->data->step ?? '1' }}"
                data-margin="{{ $field->data->margin ?? '' }}"
                data-limit="{{ $field->data->limit ?? '' }}"
                data-value="{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}"></div>
                {!! form_widget($form->{$field->name}) !!}
            </div>
        </div>

        @if($isError)
        <div class="help-info m-l-5">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif

        {{-- Add help info if defined --}}
        @if($field->data->info ?? false)
        <div class="help-info">
            {{ uctrans($field->data->info, $module) }}
        </div>
        @endif
    </div>
</div>