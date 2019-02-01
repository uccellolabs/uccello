<?php

// Get uitype instance
$uitypeClass = $field->uitype->class;
$uitype = new $uitypeClass();

// Get reference data
$referenceField = $uitype->getReferenceField($field);
$referenceRecord = $uitype->getReferenceRecord($field, $record);

// If a special template exists for the reference field, use it. Else use the generic template
$uitypeViewName = sprintf('uitypes.edit.%s', $referenceField->uitype->name);
$uitypeFallbackView = 'uccello::modules.default.uitypes.edit.text';
$uitypeViewToInclude = uccello()->view($referenceField->uitype->package, $module, $uitypeViewName, $uitypeFallbackView);
?>
@include($uitypeViewToInclude)