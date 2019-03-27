@foreach ($module->relatedlists as $relatedlist)
    @continue(!empty($relatedlist->tab_id) || !Auth::user()->canRetrieve($domain, $relatedlist->relatedModule) || !$relatedlist->isVisibleAsTab)
    <div role="tabpanel" class="tabpanel fade in @if ($selectedRelatedlistId === $relatedlist->id)active @endif" id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}">
        {{-- Card --}}
        @include('uccello::modules.default.detail.relatedlists.card', [ 'datatableId' => 'tab-relatedlist-datatable'])
    </div>

    {{-- Selection modal content --}}
    @include('uccello::modules.default.detail.relatedlists.selection-modal.content')
@endforeach