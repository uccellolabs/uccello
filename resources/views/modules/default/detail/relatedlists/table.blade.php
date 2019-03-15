<?php $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule, null, 'related-list'); ?>
<div class="table-responsive">
    <table class="table table-striped table-hover dataTable"
        data-relatedlist="{{ $relatedlist->id }}"
        data-related-module="{{ $relatedlist->relatedModule->name }}"
        data-url="{{ ucroute('uccello.datatable', $domain, $relatedlist->relatedModule) }}"
        data-filter-type="related-list">
        <thead>
            <tr>
                <th class="select-column">&nbsp;</th>

                @foreach ($datatableColumns as $column)
                <th data-name="{{ $column['name'] }}" data-label="{{ uctrans('field.'.$column['name'], $relatedlist->relatedModule) }}">
                    {{ uctrans('field.'.$column['name'], $relatedlist->relatedModule) }}
                    <?php
                        $searchValue = null;

                        // If a special template exists, use it. Else use the generic template
                        $uitypeViewName = sprintf('uitypes.search.%s', $column[ 'uitype' ]);
                        $uitypeFallbackView = 'uccello::modules.default.uitypes.search.text';
                        $uitypeViewToInclude = uccello()->view($column[ 'package' ], $module, $uitypeViewName, $uitypeFallbackView);
                    ?>
                    {{-- TODO: Activate search --}}
                    {{-- @include($uitypeViewToInclude) --}}
                </th>
                @endforeach

                <th class="actions-column hidden-xs">
                    <a class="clear-search pull-left col-red" data-tooltip="{{ uctrans('button.clear_search', $module) }}" data-position="top"><i class="material-icons">close</i></a>
                </th>
            </tr>
        </thead>
    </table>
</div>