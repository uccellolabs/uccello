<?php $datatableColumns = Uccello::getDatatableColumns($relatedlist->relatedModule); ?>
<div class="table-responsive">
    <table class="table table-striped table-hover dataTable"
        data-relatedlist="{{ $relatedlist->id }}"
        data-related-module="{{ $relatedlist->relatedModule->name }}"
        data-url="{{ ucroute('uccello.datatable', $domain, $relatedlist->relatedModule) }}"
        data-columns='{!! json_encode($datatableColumns) !!}'>
        <thead>
            <tr>
                <th class="select-column">&nbsp;</th>

                @foreach ($datatableColumns as $column)
                <th>
                    {{ uctrans('field.'.$column['name'], $relatedlist->relatedModule) }}
                    {{-- TODO: Add search fields --}}
                </th>
                @endforeach

                <th class="actions-column">&nbsp;</th>
            </tr>
        </thead>
    </table>
</div>