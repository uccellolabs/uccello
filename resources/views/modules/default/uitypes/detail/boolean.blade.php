<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col m2 s5 field-label">
    <?php $label = uctrans($field->label, $module); ?>
    <b title="{{ $label }}">{{ $label }}</b>
</div>
<div class="col {{ $isLarge ? 's7 m10' : 's7 m4' }}">
    <?php
        $value = $record->{$field->column};
        $color = $value ? 'green' : 'red'
    ?>
    <div class="valign-wrapper">
        <i class="material-icons left {{ $color }}-text" style="font-size: 18px; margin-right: 5px">lens</i>
        {{-- <span class="icon-label">{{ uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record) }}</span> --}}
    </div>
</div>