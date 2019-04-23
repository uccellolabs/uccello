<?php
    $isLarge = $field->data->large ?? false;
    $isError = form_errors($form->{$field->name}) ?? false;
    $entityModule = ucmodule($field->data->module ?? null);
    $relatedRecord = $record->{$field->name} ?? null;
    $displayFieldName = $field->name.'_display';
?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field @if($isError)invalid @endif">

        {{--  <i class="material-icons prefix">search</i>  --}}
        <a href="#entityModal_{{ $field->name }}" class="btn-floating primary waves-effect modal-trigger prefix" style="margin-right: 10px">
            <i class="material-icons">search</i>
        </a>

        {!! form_label($form->{$field->name}) !!}
        <input class="form-control"
            @if($field->required)required="required"@endif
            name="{{ $displayFieldName }}"
            type="text"
            id="{{ $displayFieldName }}"
            value="{{ old($displayFieldName, $relatedRecord->recordLabel ?? $relatedRecord->id ?? '') }}"
            readonly="readonly"
            style="margin-left: 3.5rem; color: inherit">

        <div class="hide">{!! form_widget($form->{$field->name}) !!}</div>

        @if ($isError)
            <span class="helper-text red-text">
                {!! form_errors($form->{$field->name}) !!}
            </span>
        @endif
</div>


@section('extra-content')
    @if (!empty($entityModule) && Auth::user()->canRetrieve($domain, $entityModule))
    <div id="entityModal_{{ $field->name }}" class="modal" data-field="{{ $field->name }}">
        <div class="modal-content">
            <h4>
                {{-- Icon --}}
                <i class="material-icons letf primary-text">{{ $entityModule->icon ?? 'extension' }}</i>

                {{-- Label --}}
                <span>{{ uctrans($entityModule->name, $entityModule ) }}</span>

                <a href="javascript:void(0)" class="btn-flat waves-effect red-text right delete-related-record">{{ uctrans('button.delete_related_record', $entityModule) }}</a>
            </h4>

            <div class="row">
                <div class="col s12 modal-body">
                    <div class="row">
                        <div class="col s12">
                            {{-- Table --}}
                            <?php $datatableColumns = Uccello::getDatatableColumns($entityModule, null, 'related-list'); ?>
                            @include('uccello::modules.default.detail.relatedlists.table', [ 'datatableId' => 'datatable_'.$field->name, 'datatableContentUrl' => ucroute('uccello.list.content', $domain, $entityModule, ['action' => 'select']), 'relatedModule' => $entityModule, 'searchable' => true  ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@append