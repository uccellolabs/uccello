<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col-sm-2 col-xs-5">
    <strong>{{ uctrans($field->label, $module) }}</strong>
</div>
<div class="{{ $isLarge ? 'col-sm-10 col-xs-7' : 'col-sm-4 col-xs-7' }}">
    <?php
        $value = $field->uitype->getFormattedValueToDisplay($field, $record);
        $valueParts = explode(';', $value);
        $fileName = $valueParts[0];
    ?>
    @if (count($valueParts) === 2)
        <div class="ellipsis">
            <a href="{{ ucroute('uccello.download', $domain, $module, [ 'id' => $record->getKey(), 'field' => $field->column ]) }}"
                title="{{ uctrans('button.download_file', $module) }}">
                {{ $fileName }}
            </a>
        </div>
    @else
        &nbsp;
    @endif
</div>