<th class="sticky top-0 z-10 py-4 text-left bg-white">
    <div class="w-full whitespace-nowrap">
        <div class="flex flex-row items-center cursor-pointer hover:text-primary-500" wire:click="changeSortOrder('{{ $fieldName }}')">
            <span class="flex flex-grow font-semibold">{{ $slot }}</span>

            {{-- Order --}}
            @if ($sortFieldName === $fieldName)
                @if ($sortOrder === 'asc')
                    {{-- Asc --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                @elseif ($sortOrder === 'desc')
                    {{-- Desc --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                @endif
            @endif
        </div>

        {{-- Search --}}
        <x-uc-datatable-search :workspace="$workspace" :module="$module" :field="$field"></x-uc-datatable-search>
    </div>
</th>
