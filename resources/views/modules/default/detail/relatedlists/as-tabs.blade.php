@foreach ($module->relatedlists as $relatedlist)
    @continue(!empty($relatedlist->tab_id) || !Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
    <div role="tabpanel" class="tab-pane fade in dataTable-container @if ($selectedRelatedlistId === $relatedlist->id)active @endif" id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}" data-button-size="mini">
        {{-- Card --}}
        @include('uccello::modules.default.detail.relatedlists.card')
    </div>

    {{-- Selection modal content --}}
    @include('uccello::modules.default.detail.relatedlists.selection-modal.content')
@endforeach