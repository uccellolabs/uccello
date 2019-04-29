<?php
    $color = $record->{$field->column} ? 'green' : 'red'
?>
<div class="left-align">
    <i class="material-icons {{ $color }}-text" style="font-size: 18px">lens</i>
    {{-- <span class="icon-label" style="margin-left: 5px">{{ uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record) }}</span> --}}
</div>