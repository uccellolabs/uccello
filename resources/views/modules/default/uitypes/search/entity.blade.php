<?php
    if ($field) {
        $entityModule = ucmodule($field->data->module ?? null);
        $displayFieldName = $field->name.'_display' ?? '';
    }
?>
<div class="form-group">
    <div class="form-line input-field">
        @if ($field)
            {{--  <i class="material-icons prefix">search</i>  --}}
            <a href="#entityModal_{{ $field->name }}" class="btn-floating primary waves-effect modal-trigger prefix entity-modal" style="margin-right: 10px" data-table="datatable_{{ $field->name }}">
                <i class="material-icons">search</i>
            </a>

            <input class="field-search"
                @if($field->required)required="required"@endif
                name="{{ $displayFieldName }}"
                type="text"
                id="{{ $displayFieldName }}"
                readonly="readonly"
                style="margin-left: 3.5rem; color: inherit">

            <input type="hidden" name="{{ $field->name }}" id="{{ $field->name }}">
        @endif
    </div>
</div>

@section('extra-content')
    @if ($field && !empty($entityModule) && Auth::user()->canRetrieve($domain, $entityModule))
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