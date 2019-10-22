<?php $value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record); ?>
@if ($value)
<a href="{{ ucroute('uccello.detail', $domain, ucrecord($record->{$field->column})->module->name, ['id' => ucrecord($record->{$field->column})->getKey()]) }}"
    class="primary-text">{{ $value }}</a>
@endif