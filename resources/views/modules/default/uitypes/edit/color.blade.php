<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field @if($isError)invalid @endif">

    <div class="prefix" style="font-size: 15px">
        <div class="colorpicker"></div>
    </div>

    {!! form_label($form->{$field->name}) !!}
    {!! form_widget($form->{$field->name}) !!}

    @if ($isError)
        <span class="helper-text red-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    @endif
</div>