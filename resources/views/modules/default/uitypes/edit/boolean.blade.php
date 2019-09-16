<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field @if($isError)invalid @endif">

    <label class="active">{{ uctrans($field->label, $module) }}</label>

    <div class="switch" style="margin-top: 10px">
        <label>
            {{ uctrans('no', $module) }}
            {!! form_widget($form->{$field->name}) !!}
            <span class="lever"></span>
            {{ uctrans('yes', $module) }}
        </label>
    </div>

    @if ($isError)
        <span class="helper-text red-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    {{-- Add help info if defined --}}
    @elseif ($field->data->info ?? false)
        <span class="helper-text">
            {{ uctrans($field->data->info, $module) }}
        </span>
    @endif
</div>