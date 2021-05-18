<div>
    <table cellpadding="15" cellspacing="10" class="text-xs table-auto w-auto p-8">
        <tr class="w-full">
            <th class="sticky top-0 bg-white">
                <input type="checkbox" class="ml-3 checked:bg-primary-900">
            </th>

            @foreach ($fields as $field)
                @continue(!$field->isVisibleInListView())
                <x-uc-datatable-th :field="$field" :sortFieldName="$sortFieldName" :sortOrder="$sortOrder">{{ uctrans('field.'.$field->name, $module) }}</x-uc-datatable-th>
            @endforeach

            <th class="flex flex-row items-start sticky top-0 pr-5 bg-white py-4 font-semibold">
                {{ uctrans('label.action', $module) }}
            </th>
        </tr>
        
        @foreach ($records as $i => $record)
        <tr class="@if($i % 2 === 0)bg-gray-50 @endif cursor-pointer w-full" x-on:mouseover="hovered=true" x-on:mouseout="hovered=false" x-data="{hovered:false}">
            <td class="border-t border-b border-gray-200">
                <input type="checkbox" class="ml-3 checked:bg-primary-900">
            </td>

            @foreach ($fields as $field)
                @continue(!$field->isVisibleInListView())
                <x-uc-datatable-td :workspace="$workspace" :module="$module" :field="$field" :record="$record"/>
            @endforeach

            <td class="border-t border-b border-gray-200 pr-5">
                {{-- ToolBox --}}
                <div class="flex flex-row items-center justify-between" x-show="hovered">
                    <div class="">
                        <img width=80% src="{{ ucasset('img/table-pen-icon_picto.svg') }}">
                    </div>
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </table>

    {{ $records->links() }}
</div>