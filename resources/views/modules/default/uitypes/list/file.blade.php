<?php
$value = $field->uitype->getFormattedValueToDisplay($field, $record);
$valueParts = explode(';', $value);
$fileName = $valueParts[0];
?>
@if (count($valueParts) === 2)
<a href="{{ ucroute('uccello.download', $domain, $module, [ 'id' => $record->getKey(), 'field' => $field->column ]) }}"
    title="{{ uctrans('button.download_file', $module) }}">
    {{ $fileName }}
</a>
@endif