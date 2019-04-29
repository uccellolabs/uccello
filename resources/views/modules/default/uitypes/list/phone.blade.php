<?php $value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record); ?>
@if ($value)
<a href="tel:{{ $value }}" class="primary-text" style="white-space: nowrap">{{ $value }}</a>
@endif