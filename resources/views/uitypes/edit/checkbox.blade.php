<div class="col-md-6">
    <div class="form-group">
        <div class="input-group">            
            {{-- Icon if defined --}}
            @if($field->icon ?? false)
            <span class="input-group-addon">
                <i class="material-icons">{{ $field->icon }}</i>
            </span>
            @endif

            <div style="padding-top: 20px; padding-bottom: 15px;">
                {{-- Field --}}
                {!! form_widget($form->{$field->name}) !!}
                {{-- Label --}}
                {!! form_label($form->{$field->name}) !!}
            </div>
        </div>

        @if($isError)
        <div class="help-info">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif
    </div>
</div>