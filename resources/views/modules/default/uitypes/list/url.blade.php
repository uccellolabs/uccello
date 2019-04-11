<?php $value = $field->uitype->getFormattedValueToDisplay($field, $record); ?>
@if ($value)
<a href="{{ $value }}" class="primary-text">{{ $value }}</a>
@endif