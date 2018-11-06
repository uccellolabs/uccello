<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-sm-6 col-xs-12' }}">
    <div class="form-group form-float">

            <div class="input-field">
                {{-- Add icon if defined --}}
                @if($field->icon ?? false)
                <i class="material-icons prefix">{{ $field->icon }}</i>
                @endif

                <div class="form-line {{ $isError ? 'focused error' : ''}}">
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
                </div>
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

{{-- if it is a repeated field display only the second one --}}
@if($field->data->repeated ?? false)
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
    <div class="form-group">

        <div class="input-field">
            {{-- Add icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            <div class="form-line {{ $isError ? 'focused error' : ''}}">
                {!! form_label($form->{$field->name}->second) !!}
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