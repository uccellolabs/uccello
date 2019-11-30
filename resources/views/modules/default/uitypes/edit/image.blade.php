<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field file-field @if($isError)invalid @endif">

    <div class="btn primary @if($record->{$field->name}) hide @endif">
        <i class="material-icons">{{ $field->icon }}</i>
        {!! form_widget($form->{$field->name}) !!}
        <input type="hidden" name="delete-{{$field->name}}" class="delete-file-field" value="0">
    </div>

    <div class="file-path-wrapper @if($record->{$field->name}) hide @endif">
        <input class="file-path" type="text" placeholder="{{ uctrans($field->label, $module) }}">
    </div>

    @if($record->{$field->name})
    <div class="input-field current-file">
        <div class="img-container">
            {{-- Link to delete current file --}}
            <div class="delete-file">
                <a href="javascript:void(0);" class="primary-text" data-tooltip="{{ uctrans('button.delete', $module) }}" data-position="bottom"><i class="material-icons">delete</i></a>
            </div>

            {{-- Display image --}}
            <img src="{{ uitype($field->uitype_id)->getFormattedValueToDisplay($field, $record) }}" class="responsive-img">
        </div>
    </div>
    @endif

    @if ($isError)
        <span class="helper-text red-text">
            {!! form_errors($form->{$field->name}) !!}
        </span>
    @endif
</div>