<th class="p-1 border border-gray-400 cursor-pointer" wire:click="changeSortOrder('{{ $fieldName }}')">
    <div class="flex flex-row items-center">
        <span class="mr-2 flex flex-grow">{{ $slot }}</span>

        @if ($sortFieldName === $fieldName)
            @if ($sortOrder === 'asc')
                {{-- Asc --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            @elseif ($sortOrder === 'desc')
                {{-- Desc --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            @endif
        @endif
    </div>
</th>
