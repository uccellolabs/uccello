<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col m2 s5">
    <b>{{ uctrans($field->label, $module) }}</b>
</div>
<div class="col {{ $isLarge ? 's7 m10' : 's7 m4' }}">
    <?php
        $value = $record->{$field->column};
        $color = $value ? 'green' : 'red'
    ?>
    <div class="valign-wrapper">
        <i class="material-icons left {{ $color }}-text" style="font-size: 18px; margin-right: 5px">lens</i>
        {{-- <span class="icon-label">{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}</span> --}}
    </div>
</div>