<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col m2 s5 field-label">
    <?php $label = uctrans($field->label, $module); ?>
    <b title="{{ $label }}">{{ $label }}</b>
</div>
<div class="col {{ $isLarge ? 's7 m10' : 's7 m4' }}">
    <?php
        $value = $record->{$field->column};
        $color = $value ? 'green' : 'red';
        $icon = $record->{$field->column} ? 'check' : 'close';
    ?>
    <div class="valign-wrapper">
        <i class="material-icons left {{ $color }}-text" style="margin-right: 5px">{{ $icon }}</i>
        {{-- <span class="icon-label">{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}</span> --}}
    </div>
</div>