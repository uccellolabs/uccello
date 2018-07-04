<?php $isLarge = $field->data->large ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
<a href="{{ ucroute('uccello.detail', $domain, $field->data->module,['id' => $record->{$field->name}]) }}">{{ $field->uitype->getDisplayedValue($field, $record) }}</a>
</div>