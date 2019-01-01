<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-sm-6 col-xs-12' }}">
    <div class="form-group form-float">
        <div class="input-group spinner" data-trigger="spinner">
            {{-- Add icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            <div class="form-line {{ $isError ? 'focused error' : ''}}">
                {{-- Label --}}
                {!! form_label($form->{$field->name}) !!}

                {{-- Field --}}
                {!! form_widget($form->{$field->name}) !!}
            </div>

            <span class="input-group-addon">
                <a href="javascript:;" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
                <a href="javascript:;" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
            </span>
        </div>

        @if($isError)
        <div class="help-info m-l-5">
            {!! form_errors($form->{$field->name}) !!}
        </div>
        @endif

        {{-- Add help info if defined --}}
        @if($field->data->info ?? false)
        <div class="help-info m-l-5">
            {{ uctrans($field->data->info, $module) }}
        </div>
        @endif
    </div>
</div>