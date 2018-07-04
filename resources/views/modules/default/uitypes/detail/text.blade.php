<?php $isLarge = $field->data->large ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
    {{ $field->uitype->getDisplayedValue($field, $record) }}
</div>