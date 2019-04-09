<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field">

    {{-- Add icon if defined --}}
    @if($field->icon ?? false)
    <i class="material-icons prefix">{{ $field->icon }}</i>
    @endif

    {{-- if it is a repeated field display only the first one --}}
    @if($field->data->repeated ?? false)
    {!! form_label($form->{$field->name}->first) !!}
    @else
    {{-- else display the field normally --}}
    {!! form_label($form->{$field->name}) !!}
    @endif

    {{-- if it is a repeated field display only the first one --}}
    @if($field->data->repeated ?? false)
    {!! form_widget($form->{$field->name}->first) !!}
    @else
    {{-- else display the field normally --}}
    {!! form_widget($form->{$field->name}) !!}
    @endif

    @if ($isError)
        <span class="helper-text red-text">
            {{-- if it is a repeated field display only the first error --}}
            @if($field->data->repeated ?? false)
            {!! form_errors($form->{$field->name}->first) !!}
            @else
            {{-- else display the error normally --}}
            {!! form_errors($form->{$field->name}) !!}
            @endif
        </span>
    {{-- Add help info if defined --}}
    @elseif ($field->data->info ?? false)
        <span class="helper-text">
            {{ uctrans($field->data->info, $module) }}
        </span>
    @endif
</div>

{{-- if it is a repeated field display only the second one --}}
@if($field->data->repeated ?? false)
    <div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field">

        {{-- Add icon if defined --}}
        @if($field->icon ?? false)
        <i class="material-icons prefix">{{ $field->icon }}</i>
        @endif

        {!! form_label($form->{$field->name}->second) !!}
        {!! form_widget($form->{$field->name}->second) !!}

        @if ($isError)
            <span class="helper-text red-text">
                {!! form_errors($form->{$field->name}->second) !!}
            </span>
        @endif
    </div>
@endif