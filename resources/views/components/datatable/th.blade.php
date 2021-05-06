<th class="text-left sticky top-0 py-4 bg-white z-10" wire:click="changeSortOrder('{{ $fieldName }}')">
    <div class="w-full whitespace-nowrap">
        <div class="flex flex-row items-center">
            <span class="flex flex-grow font-semibold">{{ $slot }}</span>
    
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
        <div class="relative flex flex-row items-center mt-2">
            <img class="absolute left-3" src="{{ ucasset('img/search_picto.svg') }}">
            <input type="text" class="pl-9 rounded-lg h-8 border border-gray-200 bg-white shadow-sm w-full">
        </div>
    </div>
</th>
