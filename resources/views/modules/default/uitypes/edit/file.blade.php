<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<?php $value = uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record); ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field file-field @if($isError)invalid @endif">

    <div class="btn primary @if($record->{$field->name}) hide @endif">
        <i class="material-icons">{{ $field->icon }}</i>
        {!! form_widget($form->{$field->name}) !!}
        <input type="hidden" name="delete-{{$field->name}}" class="delete-file-field" value="0">
    </div>

    <div class="file-path-wrapper @if($record->{$field->name}) hide @endif">
        <input class="file-path validate" type="text" placeholder="{{ uctrans($field->label, $module) }}">
    </div>

    @if ($value)
        <?php
            $valueParts = explode(';', $value);
            $fileName = $valueParts[0];
        ?>
        <div class="input-field current-file">
            <div class="file-container">
                {{-- Link to delete current file --}}
                <div class="delete-file left">
                    <a href="javascript:void(0);" class="primary-text" data-tooltip="{{ uctrans('button.delete', $module) }}" data-position="bottom"><i class="material-icons">delete</i></a>
                </div>

                {{-- Display file name --}}
                <span style="margin-left: 15px">{{ $fileName}}</span>
            </div>
        </div>
    @endif

    @if ($isError)
        <span class="helper-text red-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    @endif
</div>