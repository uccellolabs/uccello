<li class="dd-item dd-nochildren" data-module="{{ $_module->name }}" data-type="module" data-label="{{ $link->label }}" data-translation="{{ uctrans($link->label, $_module) }}" data-icon="{{ $link->icon ?? 'extension' }}" data-route="{{ $link->route }}" data-module="{{ $_module->name }}" data-color="grey">
    <div class="dd-handle">
        <i class="material-icons">{{ $link->icon ?? 'extension' }}</i>
        <span class="icon-label">{{ uctrans($link->label, $_module) }}</span>
        <span class="pull-right col-grey">{{ uctrans('menu.link.type.module', $module) }}</span>
    </div>
</li>