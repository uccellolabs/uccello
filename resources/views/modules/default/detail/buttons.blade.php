<div id="page-action-buttons">
    @if (Auth::user()->canUpdate($domain, $module))
    <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => $record->getKey()]) }}" class="btn-floating btn-large waves-effect green" data-tooltip="{{ uctrans('button.edit', $module) }}" data-position="top">
        <i class="material-icons">edit</i>
    </a>
    @endif

    @if (Auth::user()->canDelete($domain, $module))
    <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => $record->getKey()]) }}" class="btn-floating btn-large waves-effect red" data-tooltip="{{ uctrans('button.delete', $module) }}" data-position="top" data-config='{"actionType":"link","confirm":true,"dialog":{"title":"{{ uctrans('button.delete.confirm', $module) }}"}}'>
        <i class="material-icons">delete</i>
    </a>
    @endif
</div>