<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
    <div class="form-group">
        {{-- if it is a repeated field display only the first one --}}
        @if($field->data->repeated ?? false)
        {!! form_label($form->{$field->name}->first) !!}
        @else
        {{-- else display the field normally --}}
        {!! form_label($form->{$field->name}) !!}
        @endif

        <div class="input-group">
            {{-- Add icon if defined --}}
            @if($field->icon ?? false)
            <span class="input-group-addon">
                <i class="material-icons">{{ $field->icon }}</i>
            </span>
            @endif

            <div class="form-line {{ $isError ? 'focused error' : ''}}">
                {{-- if it is a repeated field display only the first one --}}
                @if($field->data->repeated ?? false)
                {!! form_widget($form->{$field->name}->first) !!}
                @else
                {{-- else display the field normally --}}
                {!! form_widget($form->{$field->name}) !!}
                @endif
            </div>

            @if($isError)
            <div class="help-info">
                {{-- if it is a repeated field display only the first error --}}
                @if($field->data->repeated ?? false)
                {!! form_errors($form->{$field->name}->first) !!}
                @else
                {{-- else display the error normally --}}
                {!! form_errors($form->{$field->name}) !!}
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

{{-- if it is a repeated field display only the second one --}}
@if($field->data->repeated ?? false)
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
    <div class="form-group">
        {!! form_label($form->{$field->name}->second) !!}

        <div class="input-group">
            {{-- Add icon if defined --}}
            @if($field->icon ?? false)
            <span class="input-group-addon">
                <i class="material-icons">{{ $field->icon }}</i>
            </span>
            @endif

            <div class="form-line {{ $isError ? 'focused error' : ''}}">
                {!! form_widget($form->{$field->name}->second) !!}
            </div>

            @if($isError)
            <div class="help-info">
                {!! form_errors($form->{$field->name}->second) !!}
            </div>
            @endif
        </div>
    </div>
</div>
@endif