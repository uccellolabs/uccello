
<?php
    $datatableId = $datatableId.'-'.$relatedlist->id;
    $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule, null, 'related-list');
    $datatableFilter = Uccello::getDefaultFilter($relatedlist->relatedModule, 'related-list');
?>
<div class="card relatedlist">
    <div class="card-content">
        {{-- Title --}}
        <span class="card-title">
            {{-- Icon --}}
            <i class="material-icons left primary-text">{{ $relatedlist->icon ?? $relatedlist->relatedModule->icon }}</i>

            {{-- Label --}}
            {{ uctrans($relatedlist->label, $module) }}

            <div class="right-align right">
                @include('uccello::modules.default.detail.relatedlists.buttons')
            </div>
        </span>

        {{-- Table --}}
        @include('uccello::modules.default.detail.relatedlists.table', [ 'datatableId' => $datatableId, 'datatableColumns' => $datatableColumns, 'relatedModule' => $relatedlist->relatedModule, 'selectedFilter' => $datatableFilter ])
    </div>
</div>