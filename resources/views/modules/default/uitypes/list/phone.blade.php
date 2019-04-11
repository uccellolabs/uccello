<?php $value = $field->uitype->getFormattedValueToDisplay($field, $record); ?>
@if ($value)
<a href="tel:{{ $value }}" style="white-space: nowrap">{{ $value }}</a>
@endif