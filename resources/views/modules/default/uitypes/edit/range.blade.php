<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }}">

    {!! form_label($form->{$field->name}) !!}

    <p class="range-field">
        {!! form_widget($form->{$field->name}) !!}
    </p>

    @if ($isError)
        <span class="helper-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    @endif
</div>