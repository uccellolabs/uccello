<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col m2 s5">
    <b>{{ uctrans($field->label, $module) }}</b>
</div>
<div class="col {{ $isLarge ? 's7 m10' : 's7 m4' }}">
    <?php $value = $field->uitype->getFormattedValueToDisplay($field, $record); ?>
    @if ($value)
        <div class="ellipsis">
            <a href="{{ ucroute('uccello.detail', $domain, $field->data->module, ['id' => $record->{$field->column}]) }}">{{ $value }}</a>
        </div>
    @else
        &nbsp;
    @endif
</div>