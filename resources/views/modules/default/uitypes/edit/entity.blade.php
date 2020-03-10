<?php
    $isLarge = $field->data->large ?? false;
    $isError = form_errors($form->{$field->name}) ?? false;
    $entityModule = ucmodule($field->data->module ?? null);
    $relatedRecord = $record->{$field->name} ?? null;
    $displayFieldName = $field->name.'_display';
    $additional_search_rules = $field->data->search ?? null;
?>
<div class="col {{ $isLarge ? 's12' : 's12 m6' }} input-field @if($isError)invalid @endif">

        {{--  <i class="material-icons prefix">search</i>  --}}
        <a href="#entityModal_{{ $field->name }}" class="btn-floating primary waves-effect modal-trigger prefix entity-modal" style="margin-right: 10px" data-table="datatable_{{ $field->name }}">
            <i class="material-icons">search</i>
        </a>

        {!! form_label($form->{$field->name}) !!}
        <input @if($field->required)required="required"@endif
            name="{{ $displayFieldName }}"
            type="text"
            id="{{ $displayFieldName }}"
            value="{{ old($displayFieldName, $relatedRecord->recordLabel ?? $relatedRecord->id ?? request($displayFieldName) ?? '')  }}"
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

                <a href="javascript:void(0)" class="btn-flat waves-effect red-text right delete-related-record">
                    <i class="material-icons left">delete</i>
                    {{ uctrans('button.delete_related_record', $entityModule) }}
                </a>
                <a href="javascript:void(0)" class="btn-flat waves-effect primary-text right create-related-record">
                    <i class="material-icons left">add</i>
                    {{ uctrans('button.create_related_record', $entityModule) }}
                </a>
                <a href="javascript:void(0)" class="btn-flat waves-effect primary-text right search-related-record" style="display: none">
                    <i class="material-icons left">search</i>
                    {{ uctrans('button.search_related_record', $entityModule) }}
                </a>
            </h4>

            <div class="row">
                <div class="col s12 modal-body">
                    <div class="row search-related-record" style="margin-top : 10px;">
                        <div class="progress transparent loader" data-table="{{ 'datatable_'.$field->name }}" style="margin: 0">
                            <div class="indeterminate green"></div>
                        </div>
                        <div class="col s12">
                            {{-- Table --}}
                            <?php $datatableColumns = Uccello::getDatatableColumns($entityModule, null, 'related-list'); ?>
                            @include('uccello::modules.default.detail.relatedlists.table', [ 'datatableId' => 'datatable_'.$field->name, 'datatableContentUrl' => ucroute('uccello.list.content', $domain, $entityModule, ['action' => 'select']), 'relatedModule' => $entityModule, 'searchable' => true, 'additional_search_rules' => json_encode($additional_search_rules) ])
                        </div>
                        <div class="loader center-align" data-table="{{ 'datatable_'.$field->name }}">
                            <div class="preloader-wrapper big active">
                                <div class="spinner-layer spinner-primary-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div>
                                    <div class="gap-patch">
                                        <div class="circle"></div>
                                    </div>
                                    <div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                
                            <div>
                                {{ uctrans('datatable.loading', $module) }}
                            </div>
                        </div>
                    </div>
                    <div class="row create-related-record" style="display: none">
                        <div class="col s12 create-ajax">
                            {{-- Will be loaded dynamicly through AJAX --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@append

@section('extra-meta')
<meta name="popup_url_{{ $field->name }}" content="{{ ucroute('uccello.popup.edit', $domain, $entityModule) }}">
@append