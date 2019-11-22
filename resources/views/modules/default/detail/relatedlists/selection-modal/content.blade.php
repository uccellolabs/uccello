<?php
    $datatableId = $datatableId.'-'.$relatedlist->id;
    $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule, null, 'list');
?>
@if ($relatedlist->canSelect() && $relatedlist->relatedModule->isActiveOnDomain($domain) && Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
    <div class="selection-modal-content hide" data-relatedlist="{{ $relatedlist->id }}">
        {{-- Loader top --}}
        <div class="progress transparent loader" data-table="{{ $datatableId }}" style="margin: 0">
            <div class="indeterminate green"></div>
        </div>

        <div class="row">
            <div class="col s12">
                {{-- Table --}}
                @include('uccello::modules.default.detail.relatedlists.table', [ 'datatableId' => $datatableId, 'datatableContentUrl' => ucroute('uccello.list.content', $domain, $relatedlist->relatedModule, ['action' => 'select']), 'relatedModule' => $relatedlist->relatedModule, 'searchable' => true ])
            </div>
        </div>

        {{-- Loader bottom --}}
        <div class="loader center-align" data-table="{{ $datatableId }}">
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
@endif