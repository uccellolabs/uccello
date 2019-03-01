<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col-sm-2 col-xs-5">
    <strong>{{ uctrans($field->label, $module) }}</strong>
</div>
<div class="{{ $isLarge ? 'col-sm-10 col-xs-7' : 'col-sm-4 col-xs-7' }}">
    <?php
        $value = $field->uitype->getFormattedValueToDisplay($field, $record);
        $color = $value === trans('uccello::default.yes') ? 'green' : 'red'
    ?>

    <div style="position: relative">
        <i class="material-icons col-{{ $color }}" style="font-size: 18px" title="{{ $value }}">lens</i>
        <span class="icon-label" style="left: 2px; top: -3px">{{ $value }}</span>
    </div>
</div>