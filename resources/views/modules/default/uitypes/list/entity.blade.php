<?php $value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record); ?>
@if ($value)
<a href="{{ ucroute('uccello.detail', $domain, $field->data->module, ['id' => $record->{$field->column}]) }}"
    class="primary-text">{{ $value }}</a>
@endif