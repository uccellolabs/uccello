<div class="autoscroll-x">
    <?php $filterOrder = !empty($selectedFilter) ? (array) $selectedFilter->order : []; ?>
    <table
        id="{{ $datatableId }}"
        class="striped highlight"
        data-filter-type="related-list"
        data-filter-id="{{ $selectedFilter->id ?? '' }}"
        data-relatedlist="{{ $relatedlist->id ?? '' }}"
        data-content-url="{{ $datatableContentUrl ?? ucroute('uccello.list.content', $domain, $relatedModule) }}"
        data-add-relation-url="{{ ucroute('uccello.edit.relation.add', $domain, $module) }}"
        data-order="{{ !empty($selectedFilter->order) ? json_encode($selectedFilter->order) : '' }}"
        data-length="{{ $selectedFilter->data->length ?? 15 }}">
        <thead>
            <tr>
                <th class="select-column">&nbsp;</th>

                @foreach ($datatableColumns as $column)
                <th class="sortable" data-field="{{ $column['name'] }}" data-column="{{ $column['db_column'] }}" @if(!$column['visible'])style="display: none"@endif>
                    <a href="javascript:void(0)" class="column-label">
                        {{-- Label --}}
                        {{ uctrans('field.'.$column['name'], $relatedModule) }}

                        {{-- Sort icon --}}
                        @if (!empty($filterOrder[$column['name']]))
                        <i class="fa @if ($filterOrder[$column['name']] === 'desc')fa-sort-amount-down @else fa-sort-amount-up @endif"></i>
                        @else
                        <i class="fa fa-sort-amount-up" style="display: none"></i>
                        @endif
                    </a>
                    @if (isset($searchable) && $searchable === true)
                    <div class="search hide-on-small-only hide-on-med-only">
                        <?php
                            $searchValue = null;
                            if (!empty($selectedFilter) && !empty($selectedFilter->conditions->search->{$column['name']})) {
                                $searchValue = $selectedFilter->conditions->search->{$column['name']};
                            }

                            // If a special template exists, use it. Else use the generic template
                            $uitypeViewName = sprintf('uitypes.search.%s', $column[ 'uitype' ]);
                            $uitypeFallbackView = 'uccello::modules.default.uitypes.search.text';
                            $uitypeViewToInclude = uccello()->view($column[ 'package' ], $module, $uitypeViewName, $uitypeFallbackView);
                            $field = $module->fields()->where('name', $column['name'])->first();
                        ?>
                        @include($uitypeViewToInclude, [ 'field' => $field ])
                    </div>
                    @endif
                </th>
                @endforeach

                <th class="actions-column hide-on-small-only hide-on-med-only right-align">
                    <br>
                    <a href="javascript:void(0)" class="btn-floating btn-small waves-effect red clear-search" data-tooltip="{{ uctrans('button.clear_search', $module) }}" data-position="top" style="display: none">
                        <i class="material-icons">close</i>
                    </a>
                </th>
            </tr>
        </thead>

        <tbody>
            {{-- No result --}}
            <tr class="no-results" style="display: none">
                <td colspan="100%" class="center-align">{{ uctrans('datatable.no_results', $relatedModule) }}</td>
            </tr>

            {{-- Row template used by the query --}}
            <tr class="template hide" data-row-url="{{ ucroute('uccello.detail', $domain, $relatedModule, ['id' => 'RECORD_ID']) }}">
                <td class="select-column">&nbsp;</td>

                @foreach ($datatableColumns as $column)
                <td data-field="{{ $column['name'] }}">&nbsp;</td>
                @endforeach

                <td class="actions-column hide-on-small-only hide-on-med-only right-align">
                    @if (isset($relatedlist) && Auth::user()->canUpdate($domain, $relatedModule))
                    <a href="{{ $relatedlist->getEditLink($domain, $record->id) }}"
                        data-tooltip="{{ uctrans('button.edit', $relatedModule) }}"
                        data-position="top"
                        class="edit-btn primary-text">
                        <i class="material-icons">edit</i>
                    </a>
                    @endif

                    @if (isset($relatedlist) && Auth::user()->canDelete($domain, $relatedModule))

                    {{-- {{ dd($relatedlist->getDeleteLink($domain, $record->id)) }} --}}
                    <a href="{{ $relatedlist->getDeleteLink($domain, $record->id) }}"
                        data-tooltip="{{ uctrans('button.delete', $relatedModule) }}"
                        data-position="top"
                        class="delete-btn primary-text"
                        data-config='{"actionType":"link","confirm":true,"dialog":{"title":"{{ uctrans('confirm.button.delete_relation', $module) }}"}}'>
                        <i class="material-icons">delete</i>
                    </a>
                    @endif
                </td>
            </tr>

            {{-- Other rows are generated automaticaly --}}
        </tbody>
    </table>

    {{-- Loader --}}
    <div class="loader center-align hide" data-table="{{ $datatableId }}">
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

{{-- Pagination --}}
<div class="center-align">
    <ul class="pagination" data-table="{{ $datatableId }}">
    </ul>
</div>