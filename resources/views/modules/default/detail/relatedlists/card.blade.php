<div class="card relatedlist">
    <div class="card-content">
        {{-- Title --}}
        <span class="card-title">
            {{-- Icon --}}
            <i class="material-icons primary-text">{{ $relatedlist->icon ?? $relatedlist->relatedModule->icon }}</i>

            {{-- Label --}}
            {{ uctrans($relatedlist->label, $module) }}
        </span>

        <div class="row">
            <div class="col s12">
                {{-- Table --}}
                @include('uccello::modules.default.detail.relatedlists.table')
            </div>
        </div>
    </div>
</div>

{{-- Paginator --}}
<div class="paginator"></div>

{{-- Template to use in the table --}}
<div class="template hide">
    @if (Auth::user()->canUpdate($domain, $relatedlist->relatedModule))
    <a href="{{ $relatedlist->getEditLink($domain, $record->id) }}" title="{{ uctrans('button.edit', $relatedlist->relatedModule) }}" class="edit-btn"><i class="material-icons">edit</i></a>
    @endif

    @if (Auth::user()->canDelete($domain, $relatedlist->relatedModule))
    <?php $confirmMessage = $relatedlist->type === 'n-1' ? 'button.delete.confirm' : 'button.delete.relation.confirm'; ?>
    <a href="{{ $relatedlist->getDeleteLink($domain, $record->id) }}" title="{{ uctrans('button.delete', $relatedlist->relatedModule) }}" class="delete-btn" data-config='{"actionType":"link","confirm":true,"dialog":{"title":"{{ uctrans($confirmMessage, $module) }}"}}'><i class="material-icons">delete</i></a>
    @endif
</div>