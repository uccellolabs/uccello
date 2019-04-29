<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-sm-6 col-xs-12' }}">
    <?php $value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record); ?>
    <div class="form-group form-fixed">
        {{-- Label --}}
        {!! form_label($form->{$field->name}) !!}

        <div class="input-field file-field @if($record->{$field->name}) hide @endif">
            {{-- Add icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            <div class="form-line {{ $isError ? 'focused error' : ''}}">
                {{-- Field --}}
                {!! form_widget($form->{$field->name}) !!}
                <input type="hidden" name="delete-{{$field->name}}" class="delete-file-field" value="0">
            </div>
        </div>

        @if ($value)
            <?php
                $valueParts = explode(';', $value);
                $fileName = $valueParts[0];
            ?>
            <div class="input-field current-file">
                <div class="file-container">
                    {{-- Link to delete current file --}}
                    <div class="delete-file">
                        <a href="javascript:void(0);" data-tooltip="{{ uctrans('button.delete', $module) }}" data-position="bottom"><i class="material-icons">delete</i></a>
                    </div>

                    {{-- Display file name --}}
                    {{ $fileName}}
                </div>
            </div>
        @endif

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