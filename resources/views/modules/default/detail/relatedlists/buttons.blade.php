{{-- Select button --}}
@if ($relatedlist->canSelect() && Auth::user()->canRetrieve($domain, $relatedlist->relatedModule))
<button class="btn-floating btn-small waves-effect orange btn-relatedlist-select"
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
class="btn-floating btn-small waves-effect green btn-relatedlist-add"
data-tooltip="{{ uctrans('relatedlist.button.add', $module) }}"
data-position="top">
<i class="material-icons">playlist_add</i>
</a>
@endif

{{-- Columns visibility --}}
<a href="#"
    class="btn-floating btn-small waves-effect primary dropdown-trigger"
    data-target="dropdown-columns-{{ $datatableId }}"
    data-close-on-click="false"
    data-constrain-width="false"
    data-alignment="right"
    data-tooltip="{{ uctrans('relatedlist.button.columns', $module) }}"
    data-position="top">
    <i class="material-icons">view_column</i>
</a>
<ul id="dropdown-columns-{{ $datatableId }}" class="dropdown-content columns" data-table="{{ $datatableId }}">
    @foreach ($datatableColumns as $column)
    <li @if($column['visible'])class="active"@endif><a href="javascript:void(0);" class="waves-effect waves-block column-visibility" data-field="{{ $column['name'] }}">{{ uctrans('field.'.$column['name'], $relatedlist->relatedModule) }}</a></li>
    @endforeach
</ul>

{{-- Records number --}}
<a href="#"
    class="btn-floating btn-small waves-effect primary dropdown-trigger"
    data-target="dropdown-records-number-{{ $datatableId }}"
    data-alignment="right"
    data-tooltip="{{ uctrans('relatedlist.button.lines', $module) }}"
    data-position="top">
    <strong class="records-number">{{ $selectedFilter->data->length ?? 15 }}</strong>
</a>
<ul id="dropdown-records-number-{{ $datatableId }}" class="dropdown-content records-number" data-table="{{ $datatableId }}">
    <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="15">15</a></li>
    <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="30">30</a></li>
    <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="50">50</a></li>
    <li><a href="javascript:void(0);" class="waves-effect waves-block" data-number="100">100</a></li>
</ul>