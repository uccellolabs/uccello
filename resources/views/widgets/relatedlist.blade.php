@if (!empty($data->id))
    <?php
        $relatedlist = \Uccello\Core\Models\Relatedlist::find($data->id);
    ?>
    @if(!is_null($relatedlist) && Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
    <div class="row">
        <div class="col s12">
            <div id="relatedlist_{{ $relatedlist->relatedModule->name }}_{{ $relatedlist->id }}_widget" data-button-size="mini">
                {{-- Card --}}
                @include('uccello::modules.default.detail.relatedlists.card', [ 'datatableId' => 'widget-relatedlist-datatable'])
            </div>
        </div>
    </div>
    @endif
@endif