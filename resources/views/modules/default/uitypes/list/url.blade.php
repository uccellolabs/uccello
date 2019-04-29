<?php $value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record); ?>
@if ($value)
<a href="{{ $value }}" class="primary-text">{{ $value }}</a>
@endif