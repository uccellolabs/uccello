@foreach ($menuItems as $i => $item)
    <?php $_module = !empty($item->module) ? ucmodule($item->module) : null; ?>
    @continue (!empty($_module) && (!$_module->isDisplayedInMenu() || !$_module->isActiveOnDomain($domain) || !Auth::user()->canRetrieve($domain, $_module) ))
    @include('uccello::layouts.partials.sidebars.left.menu.item', ['item' => $item, 'depth' => 0])
@endforeach