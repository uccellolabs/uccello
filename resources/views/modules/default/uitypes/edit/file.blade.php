<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-sm-6 col-xs-12' }}">
    <div class="form-group form-fixed">
        {{-- Label --}}
        {!! form_label($form->{$field->name}) !!}

        <div class="input-field">
            {{-- Add icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            <div class="form-line {{ $isError ? 'focused error' : ''}}">
                {{-- Field --}}
                {!! form_widget($form->{$field->name}) !!}
                <input type="hidden" name="delete-{{$field->name}}" value="0">
            </div>
        </div>

        @if($record->{$field->column} && isset($field->data->public) && $field->data->public === true)
            <img src="{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}">
        @else
            {{ $field->uitype->getFormattedValueToDisplay($field, $record) }}
        @endif

        @if($isError)
        <div class="help-info">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif
    </div>
</div>