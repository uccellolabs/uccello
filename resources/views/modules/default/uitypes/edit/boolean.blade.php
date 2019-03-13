<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field">

    {{-- <label>{{ uctrans($field->label, $module) }}</label> --}}

    <div class="switch">
        <label>
            {{ uctrans('no', $module) }}
            {!! form_widget($form->{$field->name}) !!}
            <span class="lever"></span>
            {{ uctrans('yes', $module) }}
        </label>
    </div>

    @if ($isError)
        <span class="helper-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    @endif
</div>