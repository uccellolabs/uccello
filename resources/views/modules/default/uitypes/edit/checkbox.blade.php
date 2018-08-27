<div class="col-md-6">
    <div class="form-group">
        <div class="input-field">
            {{-- Icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
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