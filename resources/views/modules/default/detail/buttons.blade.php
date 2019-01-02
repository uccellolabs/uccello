<div id="page-action-buttons">
    @if (Auth::user()->canUpdate($domain, $module))
    <a href="{{ ucroute('uccello.edit', $domain, $module, ['id' => $record->getKey()]) }}" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.edit', $module) }}" data-toggle="tooltip" data-placement="top">
        <i class="material-icons">edit</i>
    </a>
    @endif

    @if (Auth::user()->canDelete($domain, $module))
    <a href="{{ ucroute('uccello.delete', $domain, $module, ['id' => $record->getKey()]) }}" class="btn bg-red btn-circle-lg waves-effect waves-circle waves-float" title="{{ uctrans('button.delete', $module) }}" data-toggle="tooltip" data-placement="top" data-config='{"actionType":"link","confirm":true,"dialog":{"title":"{{ uctrans('button.delete.confirm', $module) }}"}}'>
        <i class="material-icons">delete</i>
    </a>
    @endif
</div>