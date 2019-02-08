<?php $isLarge = $field->data->large ?? false; ?>
<?php $isError = form_errors($form->{$field->name}) ?? false; ?>
<div class="{{ $isLarge ? 'col-md-12' : 'col-sm-6 col-xs-12' }} entity"
    data-field="{{ $field->name }}"
    data-modal-title="{{ uctrans($field->data->module ?? '', ucmodule($field->data->module)) }}"
    data-modal-icon="{{ ucmodule($field->data->module)->icon ?? 'extension' }}">
    <div class="form-group form-float">
        <div class="input-group">
            {{-- Add icon if defined --}}
            @if($field->icon ?? false)
            <i class="material-icons prefix">{{ $field->icon }}</i>
            @endif

            <div class="form-line {{ $isError ? 'focused error' : ''}}">
                {{-- Label - We don't use form_label() because it is not displayed for a hidden field --}}
                <label class="form-label @if ($field->required)required @endif">{{ uctrans($field->label, $module) }}</label>

                {{-- Module --}}
                <input id="{{ $field->name }}_module" type="hidden" value="{{ $field->data->module ?? '' }}" />

                {{-- Field --}}
                {!! form_widget($form->{$field->name}) !!}

                {{-- Displayed value --}}
                <input id="{{ $field->name }}_display" type="text" class="form-control" value="{{ $field->uitype->getFormattedValueToDisplay($field, $record) }}" readonly />
            </div>

            <span class="input-group-addon">
                <a href="javascript:void(0)">
                    <i class="material-icons">search</i>
                </a>
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

    {{-- Selection modal content --}}
    @include('uccello::modules.default.edit.entity-modal.content', [ 'relatedModule' => ucmodule($field->data->module) ])
</div>