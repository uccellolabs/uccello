<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-sm-6 col-xs-12' }}">
    <div class="form-group form-fixed">
        {{-- Label --}}
        {!! form_label($form->{$field->name}) !!}

        <div class="input-group">
            {{-- Add icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            <div class="form-line {{ $isError ? 'focused error' : ''}}">
                {{-- Field --}}
                <div class="p-t-5">
                    {!! form_widget($form->{$field->name}) !!}
                </div>
            </div>

            <span class="input-group-addon">
                <?php $color = $record->{$field->name} ?? 'transparent'; ?>
                <i class="material-icons" style="font-size: 40px; color: {{ $color }}">stop</i>
            </span>
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