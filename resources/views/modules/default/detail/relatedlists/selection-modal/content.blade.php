@if ($relatedlist->canSelect() && Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
    <div class="selection-modal-content" data-relatedlist="{{ $relatedlist->id }}">
        <div class="row">
            <div class="col-md-12">
                {{-- Table --}}
                @include('uccello::modules.default.detail.relatedlists.table')
            </div>
        </div>

        {{-- Paginator --}}
        <div class="paginator"></div>
    </div>
@endif