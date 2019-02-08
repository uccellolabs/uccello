<?php $datatableColumns = Uccello::getDatatableColumns($relatedModule); ?>
<div class="table-responsive">
    <table class="table table-striped table-hover dataTable"
        data-related-module="{{ $relatedModule->name }}"
        data-url="{{ ucroute('uccello.datatable', $domain, $relatedModule) }}"
        data-columns='{!! json_encode($datatableColumns) !!}'>
        <thead>
            <tr>
                <th class="select-column">&nbsp;</th>

                @foreach ($datatableColumns as $column)
                <th>
                    {{ uctrans('field.'.$column['name'], $relatedModule) }}
                    {{-- TODO: Add search fields --}}
                </th>
                @endforeach

                <th class="actions-column">&nbsp;</th>
            </tr>
        </thead>
    </table>
</div>