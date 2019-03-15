{{-- Select button --}}
@if ($relatedlist->canSelect() && Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
    <button class="btn bg-orange btn-circle waves-effect waves-circle waves-float btn-relatedlist-select"
        data-tooltip="{{ uctrans('relatedlist.button.select', $module) }}"
        data-position="top"
        data-modal-title="{{ uctrans($relatedlist->relatedModule->name, $relatedlist->relatedModule) }}"
        data-modal-icon="{{ $relatedlist->icon ?? $relatedlist->relatedModule->icon }}"
        data-relatedlist="{{ $relatedlist->id }}">
        <i class="material-icons">playlist_add_check</i>
    </button>
@endif

{{-- Add button --}}
@if ($relatedlist->canAdd() && Auth::user()->canCreate($domain, $relatedlist->relatedModule))
    <a href="{{ $relatedlist->getAddLink($domain, $record->id) }}"
        class="btn bg-green btn-circle waves-effect waves-circle waves-float btn-relatedlist-add"
        data-tooltip="{{ uctrans('relatedlist.button.add', $module) }}"
        data-position="top">
        <i class="material-icons">playlist_add</i>
    </a>
@endif