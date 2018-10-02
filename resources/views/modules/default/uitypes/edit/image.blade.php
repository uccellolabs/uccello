<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-md-6' }}">
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

        @if($record->{$field->name})
        <div class="input-field current-file">
            <div class="img-container">
                {{-- Link to delete current file --}}
                <div class="delete-file">
                    <a href="" title="{{ uctrans('button.delete', $module) }}" data-toggle="tooltip" data-placement="bottom"><i class="material-icons">delete</i></a>
                </div>

                {{-- Display image if it is public else display a link to download it --}}
                @if($record->{$field->column} && isset($field->data->public) && $field->data->public === true)
                    <img src="{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}" class="img-responsive">
                @else
                    {{ $field->uitype->getFormattedValueToDisplay($field, $record) }}
                @endif
            </div>
        </div>
        @endif

        @if($isError)
        <div class="help-info">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif
    </div>
</div>