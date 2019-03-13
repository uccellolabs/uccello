<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field">

    {{-- Add icon if defined --}}
    @if($field->icon ?? false)
    <i class="material-icons prefix">{{ $field->icon }}</i>
    @endif

    <p>
        <label>
            {!! form_widget($form->{$field->name}) !!}
            <span>{{ uctrans($field->label, $module) }}</span>
        </label>
    </p>

    @if ($isError)
        <span class="helper-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    @endif
</div>