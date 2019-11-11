
<?php
$datatableId = $datatableId.'-'.$relatedlist->id;
$datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule, null, 'related-list');
$datatableFilter = Uccello::getDefaultFilter($relatedlist->relatedModule, 'related-list');
?>
<div class="card relatedlist">
    {{-- Loader top --}}
    <div class="progress transparent loader" data-table="{{ $datatableId }}" style="margin: 0">
        <div class="indeterminate green"></div>
    </div>

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
</div>