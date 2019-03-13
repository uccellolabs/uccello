<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field file-field">

    <div class="btn primary">
        <i class="material-icons">{{ $field->icon }}</i>
        {!! form_widget($form->{$field->name}) !!}
    </div>

    <div class="file-path-wrapper">
        <input class="file-path validate" type="text" placeholder="{{ uctrans($field->label, $module) }}">
    </div>

    @if ($isError)
        <span class="helper-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    @endif
</div>