<li class="dd-item dd3-item dd-nochildren" @if(!empty($link->id))data-id="{{ $link->id }}"@endif data-module="{{ $_module->name }}" data-type="module" data-label="{{ $link->label }}" data-translation="{{ uctrans($link->label, $_module) }}" data-icon="{{ $link->icon ?? 'extension' }}" data-route="{{ $link->route }}" data-module="{{ $_module->name }}" data-color="grey">

    <div class="dd-handle dd3-content">
        <i class="material-icons left">{{ $link->icon ?? 'extension' }}</i>
        {{ uctrans($link->label, $_module) }}
        <span class="right grey-text">{{ uctrans('menu_manager.menu.link_type.module', $module) }}</span>
    </div>

    {{-- For the moment it cannot be displayed because this view is called only in the menu does not exist. So the type is always 'module' --}}
    @if (!empty($link->type) && $link->type !== 'module')
    <div class="dd3-actions">
        <i class="material-icons btn-edit">edit</i>
        <i class="material-icons btn-remove">delete</i>
    </div>
    @endif

</li>