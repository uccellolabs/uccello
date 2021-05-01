<div>
    <table class="bg-white mb-4">
        <tr>
            @foreach ($fields as $field)
                @continue(!$field->isVisibleInListView())
                <x-uc-datatable-th :field="$field" :sortFieldName="$sortFieldName" :sortOrder="$sortOrder">{{ uctrans('field.'.$field->name, $module) }}</x-uc-datatable-th>
            @endforeach
        </tr>
        @foreach ($records as $record)
        <tr>
            @foreach ($fields as $field)
                @continue(!$field->isVisibleInListView())
                <x-uc-datatable-td :field="$field" :record="$record"/>
            @endforeach
        </tr>
        @endforeach
    </table>

    {{ $records->links() }}
</div>
