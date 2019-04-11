<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col m2 s5 field-label">
    <?php $label = uctrans($field->label, $module); ?>
    <b title="{{ $label }}">{{ $label }}</b>
</div>
<div class="col {{ $isLarge ? 's7 m10' : 's7 m4' }}">
    <?php $value = $field->uitype->getFormattedValueToDisplay($field, $record); ?>
    @if ($value)
        <div class="truncate">
            <a href="{{ $record->{$field->column} }}" class="primary-text" target="_blank">{{ $value }}</a>
        </div>
    @else
        &nbsp;
    @endif
</div>