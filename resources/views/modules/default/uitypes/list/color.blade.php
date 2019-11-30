<?php $color = $record->{$field->name} ?? 'transparent'; ?>
<div style="position: relative">
    <span class="left">{{ uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record) }}</span>
    <span style="position: absolute; top: -29px; font-size: 52px; color: {{ $color }}">&#9632;</span>
</div>