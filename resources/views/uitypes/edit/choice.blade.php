<div class="col-md-6">
    <div class="form-group">
        {{-- Label --}}
        {!! form_label($form->{$field->name}) !!}

        <div class="input-group">            
            {{-- Icon if defined --}}
            @if($field->icon ?? false)
            <span class="input-group-addon">
                <i class="material-icons">{{ $field->icon }}</i>
            </span>
            @endif

            <div style="padding-top: 10px;">
                {{-- Field --}}
                {!! form_widget($form->{$field->name}) !!}
            </div>
        </div>
    </div>
</div>