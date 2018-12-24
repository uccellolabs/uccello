<?php
$_module = !empty($item->module) ? ucmodule($item->module) : null;
?>
<li @if (!empty($item->module) && $item->module === $module->name)class="@if ($depth === 0)active @else active toggled @endif"@endif>
    {{-- Group --}}
    @if ($item->type === 'group')
        {{-- TODO: Set toggle to groups where a link is selected --}}
        <a href="javascript:void(0)" class="menu-toggle waves-effect waves-block">
            <i class="material-icons">{{ $item->icon ?? 'folder'}}</i>
            <span>{{ $item->label }}</span>
        </a>

        {{-- Children --}}
        @if (!empty($item->children))
            <ul class="ml-menu">
                @foreach ($item->children as $childItem)
                    <?php $_childModule = !empty($childItem->module) ? ucmodule($childItem->module) : null; ?>
                    @continue (!empty($_childModule) && (!$_childModule->isDisplayedInMenu() || !$_childModule->isActiveOnDomain($domain) || !Auth::user()->canRetrieve($domain, $_childModule) ))
                    @include('uccello::layouts.partials.sidebars.left.menu.item', ['item' => $childItem, 'depth' => $depth+1])
                @endforeach
            </ul>
        @endif
    @else
    {{-- Link --}}
    <?php
        // Get route if defined
        if (!empty($item->route) && !empty($item->module)) {
            $linkUrl = ucroute($item->route, $domain, $item->module);
        }
        // Get url if defined
        elseif (!empty($item->url)) {
            $linkUrl = $item->url;
        }
        else {
            $default = $_module->data->route ?? 'uccello.list';
            $linkUrl = ucroute($default, $domain, $_module);
        }
    ?>
    <a href="{{ $linkUrl }}" class="waves-effect waves-block">
        <i class="material-icons">{{ $item->icon ?? 'extension'}}</i>
        <span>{{ $item->label }}</span>
    </a>
    @endif
</li>
{{-- TODO: Add 'other' group --}}