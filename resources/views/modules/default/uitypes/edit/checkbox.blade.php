<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field">

    <label class="active">{{ uctrans($field->label, $module) }}</label>

    <p style="margin-top: 10px">
        <label>
            {!! form_widget($form->{$field->name}) !!}
            <span>{{ uctrans($field->label, $module) }}</span>
        </label>
    </p>

    @if ($isError)
        <span class="helper-text red-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    @endif
</div>