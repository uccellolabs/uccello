<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-sm-6 col-xs-12' }}">
    <div class="form-group form-switch">
        {{-- Label --}}
        <label for="{{ $field->name }}" class="form-label">{{ uctrans($field->label, $module) }}</label>

        <div class="input-field">
            {{-- Icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            {{-- Field --}}
            <div class="switch p-t-10 p-b-5">
                <label class="switch-label">
                    {{ uctrans('no', $module) }}
                    {!! form_widget($form->{$field->name}) !!}
                    <span class="lever switch-col-primary"></span>
                    {{ uctrans('yes', $module) }}
                </label>
            </div>
        </div>

        @if($isError)
        <div class="help-info m-l-5">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif

        {{-- Add help info if defined --}}
        @if($field->data->info ?? false)
        <div class="help-info">
            {{ uctrans($field->data->info, $module) }}
        </div>
        @endif
    </div>
</div>