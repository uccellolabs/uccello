<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col m2 s5 field-label">
    <?php $label = uctrans($field->label, $module); ?>
    <b title="{{ $label }}">{{ $label }}</b>
</div>
<div class="col {{ $isLarge ? 's7 m10' : 's7 m4' }}">
    <?php $value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record); ?>
    @if ($value)
        <div class="truncate">
            <a href="{{ ucroute('uccello.detail', $domain, 'user', ['id' => $record->{$field->column}]) }}" class="primary-text">{{ $value }}</a>
        </div>
    @else
        &nbsp;
    @endif
</div>