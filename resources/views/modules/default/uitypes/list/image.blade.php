<?php
$value = $field->uitype->getFormattedValueToDisplay($field, $record);
?>
@if ($value)
<img src="{{ $value }}" class="responsive-img" style="max-width: 150px">
@endif