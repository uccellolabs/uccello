@foreach ($tab->relatedlists as $relatedlist)
    @continue(!Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
    <div class="dataTable-container" id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}" data-button-size="mini">
        {{-- Card --}}
        @include('uccello::modules.default.detail.relatedlists.card', [ 'datatableId' => 'block-relatedlist-datatable'])
    </div>

    {{-- Selection modal content --}}
    @include('uccello::modules.default.detail.relatedlists.selection-modal.content')
@endforeach