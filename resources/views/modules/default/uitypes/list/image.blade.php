<?php
$value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record);
?>
@if ($value)
<img src="{{ $value }}" class="responsive-img" style="max-width: 150px">
@endif