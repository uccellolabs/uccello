<?php $isLarge = $field->data->large ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
    <div class="form-group form-float">
        <div class="form-line">
            {{-- if it is a repeated field display only the first one --}}
            @if($field->data->repeated ?? false)
            {!! form_widget($form->{$field->name}->first) !!}
            {!! form_label($form->{$field->name}->first) !!}
            @else
            {{-- else display the field normally --}}
            {!! form_widget($form->{$field->name}) !!}
            {!! form_label($form->{$field->name}) !!}
            @endif
        </div>
    </div>
</div>

{{-- if it is a repeated field display only the second one --}}
@if($field->data->repeated ?? false)
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
    <div class="form-group form-float">
        <div class="form-line">
            {!! form_widget($form->{$field->name}->second) !!}
            {!! form_label($form->{$field->name}->second) !!}
        </div>
    </div>
</div>
@endif