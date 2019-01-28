<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col-sm-2 col-xs-5">
    <strong>{{ uctrans($field->label, $module) }}</strong>
</div>
<div class="{{ $isLarge ? 'col-sm-10 col-xs-7' : 'col-sm-4 col-xs-7' }}">
    <a href="{{ ucroute('uccello.detail', $domain, $field->data->module, ['id' => $record->{$field->column}]) }}">{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}</a>&nbsp;
</div>