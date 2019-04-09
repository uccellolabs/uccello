@foreach ($tab->relatedlists as $relatedlist)
    @continue(!Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
    <div id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}">
        {{-- Card --}}
        @include('uccello::modules.default.detail.relatedlists.card', [ 'datatableId' => 'block-relatedlist-datatable'])
    </div>

    {{-- Selection modal content --}}
    @include('uccello::modules.default.detail.relatedlists.selection-modal.content', [ 'datatableId' => 'modal-relatedlist-datatable'])
@endforeach