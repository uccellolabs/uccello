@foreach ($module->relatedlists as $relatedlist)
    @continue(!empty($relatedlist->tab_id) || !Auth::user()->canRetrieve($domain, $relatedlist->relatedModule) || !$relatedlist->isVisibleAsTab)
    <div id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}" @if ($selectedRelatedlistId === $relatedlist->id)class="active"@endif>
        {{-- Card --}}
        @include('uccello::modules.default.detail.relatedlists.card', [ 'datatableId' => 'tab-relatedlist-datatable'])
    </div>

    {{-- Selection modal content --}}
    @include('uccello::modules.default.detail.relatedlists.selection-modal.content', [ 'datatableId' => 'modal-relatedlist-datatable'])
@endforeach