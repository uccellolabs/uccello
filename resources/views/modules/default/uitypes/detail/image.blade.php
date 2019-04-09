<?php $isLarge = $forceLarge ?? $field->data->large ?? false; ?>
<div class="col m2 s5">
    <b>{{ uctrans($field->label, $module) }}</b>
</div>
<div class="col {{ $isLarge ? 's7 m10' : 's7 m4' }}">
    <?php $value = $field->uitype->getFormattedValueToDisplay($field, $record); ?>
    @if($value)
        <div class="img-container">
            <img src="{{ $value }}" class="img-responsive">
        </div>
    @else
        &nbsp;
    @endif
</div>