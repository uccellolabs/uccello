<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col-sm-2 col-xs-5">
    <strong>{{ uctrans($field->label, $module) }}</strong>
</div>
<div class="{{ $isLarge ? 'col-sm-10 col-xs-7' : 'col-sm-4 col-xs-7' }}">
    @if($record->{$field->column} && $field->data->public ?? false)
        <div class="img-container">
            <img src="{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}" class="img-responsive">
        </div>
    @else
        {{ $field->uitype->getFormattedValueToDisplay($field, $record) }}
    @endif
    &nbsp;
</div>