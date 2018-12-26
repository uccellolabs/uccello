<?php $homeModule = ucmodule('home'); ?>
@if (isset($domain) && $homeModule->isActiveOnDomain($domain) && Auth::user()->canRetrieve($domain, $homeModule))
{{-- Home module --}}
<li @if ('home' === $module->name)class="active"@endif>
    <a href="{{ ucroute('uccello.home', $domain) }}">
        @if(!$admin_env)
        <i class="material-icons">{{ $homeModule->icon }}</i>
        <span>{{ uctrans('uccello::home.home') }}</span>
        @else
        <i class="material-icons col-primary">arrow_back_ios</i>
        <span class="col-primary">{{ uctrans('menu.return', $module) }}</span>
        @endif
    </a>
</li>
@endif

<?php $settingsModule = ucmodule('settings'); ?>
@if ($admin_env && isset($domain) && $settingsModule->isActiveOnDomain($domain) && Auth::user()->canAccessToSettingsPanel($domain, $settingsModule))
{{-- Settings module --}}
<li @if ($module->name === 'settings' && request()->route()->getName() === 'uccello.settings.dashboard')class="active"@endif>
    <a href="{{ ucroute('uccello.settings.dashboard', $domain) }}">
        <i class="material-icons">{{ $settingsModule->icon }}</i>
        <span>{{ uctrans('dashboard', $settingsModule) }}</span>
    </a>
</li>
@endif

{{-- All menu links --}}
@if (isset($modules) && isset($domain) && isset($module))
    <?php $isCurrentModuleDisplayed = false; ?>
    @foreach ($modules as $_module)
        @continue (
            !$_module->isActiveOnDomain($domain)
            || !Auth::user()->canRetrieve($domain, $_module)
            || (!$admin_env && $_module->isAdminModule())
            || ($admin_env && !$_module->isAdminModule())
        )
        {{-- Display all menu links --}}
        @foreach ($_module->menuLinks as $menuLink)
        <?php
            // Don't display Home dans Settings dashboard twice
            if (
                ($_module->name === 'home' && $menuLink->route === 'uccello.index')
                || ($_module->name === 'settings' && $menuLink->route === 'uccello.settings.dashboard')
            ) {
                $isCurrentModuleDisplayed = true; // Check if the module is displayed in the menu
                continue; // Don't display the link twice
            // } elseif ($module->name === $_module->name && request()->route()->getName() === $menuLink->route) {
            } elseif ($module->name === $_module->name) {
                $isCurrentModuleDisplayed = true; // Check if the module is displayed in the menu
                $isActive = true;
            } else {
                $isActive = false;
            }
        ?>
        <li @if ($isActive)class="active"@endif>
            <a href="{{ ucroute($menuLink->route, $domain, $_module) }}">
                <i class="material-icons">{{ $menuLink->icon ?? 'extension' }}</i>
                <span>{{ uctrans($menuLink->label, $_module) }}</span>
            </a>
        </li>
        @endforeach
    @endforeach

    {{-- If we are on a module not displayed in the menu, add it without link --}}
    @if (!$isCurrentModuleDisplayed && $module->name !== 'settings')
        <li class="active">
            <a href="javascript:void(0)">
                @if ($module->icon)<i class="material-icons">{{ $module->icon ?? 'list' }}</i>@endif
                <span>{{ uctrans($module->name, $module) }}</span>
            </a>
        </li>
    @endif
@endif