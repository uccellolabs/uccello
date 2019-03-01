<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<?php $color = $record->{$field->name} ?? 'transparent'; ?>
<div class="col-sm-2 col-xs-5">
    <strong>{{ uctrans($field->label, $module) }}</strong>
</div>
<div class="{{ $isLarge ? 'col-sm-10 col-xs-7' : 'col-sm-4 col-xs-7' }}" style="position: relative">
    <span class="p-l-30">{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}</span>
    <span style="position: absolute; top: -18px; font-size: 35px; color: {{ $color }}">&#9632;</span>
</div>