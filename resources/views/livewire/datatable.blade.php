<div>
    <table class="bg-white mb-4">
        <tr>
            @foreach ($fields as $field)
                @continue(!$field->isVisibleInListView())
                <th class="p-1 border border-gray-400 cursor-pointer" wire:click="changeSortOrder('{{ $field->name }}')">
                    <div class="flex flex-row items-center">
                        <span class="mr-2 flex flex-grow">{{ $field->name }}</span>

                        @if ($sortFieldName === $field->name)
                            @if ($sortOrder === 'asc')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            @endif
                        @endif
                    </div>
                </th>
            @endforeach
        </tr>
        @foreach ($records as $record)
        <tr>
            @foreach ($fields as $field)
                @continue(!$field->isVisibleInListView())
                <td class="p-1 border border-gray-400">{{ $record->{$field->column} }}</td>
            @endforeach
        </tr>
        @endforeach
    </table>

    {{ $records->links() }}
</div>
