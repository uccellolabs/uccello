<?php
    $color = $record->{$field->column} ? 'green' : 'red';
    $icon = $record->{$field->column} ? 'check' : 'close';
?>
<div class="left-align">
    <i class="material-icons {{ $color }}-text">{{ $icon }}</i>
    {{-- <span class="icon-label" style="margin-left: 5px">{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}</span> --}}
</div>