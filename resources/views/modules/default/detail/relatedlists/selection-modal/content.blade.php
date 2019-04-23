<?php
    $datatableId = $datatableId.'-'.$relatedlist->id;
    $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule, null, 'list');
?>
@if ($relatedlist->canSelect() && Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
    <div class="selection-modal-content hide" data-relatedlist="{{ $relatedlist->id }}">
        <div class="row">
            <div class="col s12">
                {{-- Table --}}
                @include('uccello::modules.default.detail.relatedlists.table', [ 'datatableId' => $datatableId, 'datatableContentUrl' => ucroute('uccello.list.content', $domain, $relatedlist->relatedModule, ['action' => 'select']), 'relatedModule' => $relatedlist->relatedModule ])
            </div>
        </div>
    </div>
@endif