<?php
    $color = $record->{$field->column} ? 'green' : 'red'
?>
<div class="valign-wrapper">
    <i class="material-icons {{ $color }}-text" style="font-size: 18px">lens</i>
    <span class="icon-label" style="margin-left: 5px">{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}</span>
</div>