<?php

namespace Uccello\Core\Support;

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Html;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class MenuGenerator
{
    /**
     * Current domain
     *
     * @var \Uccello\Core\Models\Domain
     */
    protected $domain;

    /**
     * Current module
     *
     * @var \Uccello\Core\Models\Module
     */
    protected $module;

    /**
     * Main menu
     *
     * @var \Spatie\Menu\Laravel\Menu
     */
    protected $menu;

    /**
     * All names of modules added in the menu
     *
     * @var array
     */
    protected $menuAddedModules;

    /**
     * Get menu generated
     *
     * @return \Spatie\Menu\Laravel\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Make the menu according to the environment (main or admin)
     *
     * @param Domain $domain
     * @param Module $module
     * @return \Uccello\Core\Support\MenuGenerator
     */
    public function makeMenu(Domain $domain, Module $module)
    {
        $this->domain = $domain;

        $this->module = $module;

        // Create menu
        $this->menu = Menu::new()
            ->withoutWrapperTag(); // Do not add <ul></ul>

        // Add links to menu
        $this->addLinksToMenu();

        return $this;
    }

    /**
     * Add all links to the menu
     *
     * @return void
     */
    protected function addLinksToMenu()
    {
        // Get the menu to display according to the environment (main or admin)
        $domainMenu = $this->getDomainMenuToDisplay();

        $this->menuAddedModules = [ ];

        // If a menu was created, use it
        if (!is_null($domainMenu)) {
            $this->addLinksToMenuFromDomainMenu($domainMenu);
        }
        // Else add links from the modules list
        else {
            $this->addLinksToMenuFromModulesList();
        }

        // If we are on a module not displayed in the menu, add it to the menu
        $this->addActiveModuleIfNotInMenu();
    }

    /**
     * If a menu was created, use it and add its links in the menu
     *
     * @param \Uccello\Core\Models\Menu $domainMenu
     * @return void
     */
    protected function addLinksToMenuFromDomainMenu($domainMenu)
    {
        if (empty($domainMenu->data)) {
            return;
        }

        foreach ($domainMenu->data as $menuLink) {
            $this->addLink($this->menu, $menuLink);
        }

        // Add links added after the creation of the menu
        $this->addLinksAddedAfterMenuCreation();
    }

    /**
     * If no menu was created we add all links available in the activated modules
     *
     * @return void
     */
    protected function addLinksToMenuFromModulesList()
    {
        $modules = $this->getModulesVisibleInMenu();

        foreach ($modules as $module) {
            foreach ($module->menuLinks as $menuLink) {
                $menuLink->type = 'module';
                $menuLink->module = $module->name;
                $this->addLink($this->menu, $menuLink);
            }
        }
    }

    /**
     * If we are on a module not displayed in the menu, add it to the menu
     *
     * @return void
     */
    protected function addActiveModuleIfNotInMenu()
    {
        if (!in_array($this->module->name, $this->menuAddedModules)) {
            $menuLink = new \StdClass;
            $menuLink->label = $this->module->name;
            $menuLink->icon = $this->module->icon ?? 'extension';
            $menuLink->type = 'module';
            $menuLink->module = $this->module->name; // Current module name
            $menuLink->route = request()->route()->getName(); // Current route
            $menuLink->url = 'javascript:void(0)'; // No link
            $this->addLink($this->menu, $menuLink, false, false);
        }
    }

    /**
     * Add to the menu, the links added after the creation of the menu
     * (e.g. new modules or modules activated after the creation)
     */
    protected function addLinksAddedAfterMenuCreation()
    {
        $modules = $this->getModulesVisibleInMenu();

        foreach ($modules as $module) {
            if (!in_array($module->name, $this->menuAddedModules)) {
                foreach ($module->menuLinks as $menuLink) {
                    $menuLink->type = 'module';
                    $menuLink->module = $module->name;
                    $this->addLink($this->menu, $menuLink);
                }
            }
        }
    }

    /**
     * Recursive function to add a link to the menu with all its children
     *
     * @param \Spatie\Menu\Laravel\Menu $menu
     * @param \StdClass $menuLink
     * @param boolean $isInSubMenu
     * @param boolean $checkCapacity
     * @return void
     */
    protected function addLink($menu, $menuLink, $isInSubMenu = false, $checkCapacity = true)
    {
        // Retrieve module if defined
        $module = isset($menuLink->module) ? ucmodule($menuLink->module) : null;

        //TODO: Check needed capacity
        if ($menuLink->type === 'module' && !is_null($module)) {
            // Don't display domain module if multi domains is not activated
            if ($module->name === 'domain' && !env('UCCELLO_MULTI_DOMAINS', true)) {
                return;
            }

            if (!$module->isActiveOnDomain($this->domain)) {
                return;
            }
            if ($checkCapacity && !auth()->user()->canRetrieve($this->domain, $module)) {
                return;
            }
        }

        if (!empty($menuLink->module)) {
            if (!in_array($menuLink->module, $this->menuAddedModules)) {
                $this->menuAddedModules[ ] = $menuLink->module;
            }
        }

        // Url
        if (!empty($menuLink->url)) { // Prioritary to be compatible with addActiveModuleIfNotInMenu()
            $url = $menuLink->url;
        } elseif (!empty($menuLink->route) && !is_null($module)) {
            $url = ucroute($menuLink->route, $this->domain, $module);
        } else {
            $url = 'javascript:void(0)';
        }

        // Label
        $label = $menuLink->type === 'module' ? uctrans($menuLink->label, $module) : uctrans($menuLink->label, $this->module);;

        // Icon
        if ($menuLink->type === 'folder') {
            $fallbackIcon = 'folder';
        } elseif ($menuLink->type === 'link') {
            $fallbackIcon = 'link';
        } else {
            $fallbackIcon = 'extension';
        }

        $icon = $menuLink->icon ?? $fallbackIcon;

        // Is active. If the current route is in the menu, compare also the routes
        if ($menuLink->type === 'module' && !is_null($module)) {
            if ($this->isCurrentRouteInMenu($module)) {
                $isActive = $this->module->id === $module->id && request()->route()->getName() === $menuLink->route;
            } else {
                $isActive = $this->module->id === $module->id;
            }
        } else {
            $isActive = false;
        }

        // Add children
        if (!empty($menuLink->children)) {

            // Make a sub menu
            $subMenu = Menu::new();

            // Add all links in the sub menu
            foreach ($menuLink->children as $subMenuLink) {
                $this->addLink($subMenu, $subMenuLink, true); // Recursive
            }

            $link = Html::raw(
                '<ul class="collapsible collapsible-accordion">'.
                    '<li class="submenu">'.
                        '<a href="'.$url.'" class="collapsible-header waves-effect" tabindex="0">'.
                            '<i class="material-icons">'.$icon.'</i>'.
                            '<span>'.$label.'</span>'.
                        '</a>'.
                        '<div class="collapsible-body">'.
                            $subMenu->toHtml().
                        '</div>'.
                    '</li>'.
                '</ul>'
            );

            $menu->add($link);

        } else {
            $link = Html::raw(
                '<a href="'.$url.'">'.
                    '<i class="material-icons">'.$icon.'</i>'.
                    '<span>'.$label.'</span>'.
                '</a>'
            )->setActive($isActive);

            // Add link to menu
            $menu->add($link);
        }
    }

    /**
     * Return the menu to display according to the environment (main or admin)
     *
     * @return \Uccello\Core\Models\Menu
     */
    protected function getDomainMenuToDisplay()
    {
        if ($this->isAdminEnv()) {
            $menuToDisplay = $this->domain->adminMenu;
        } else {
            $menuToDisplay = $this->domain->mainMenu;
        }

        return $menuToDisplay;
    }

    /**
     * Return all modules visible in the menu according to the environment (main or admin)
     *
     * @return void
     */
    protected function getModulesVisibleInMenu()
    {
        // Detect what sort of link to add in the menu (admin or not admin) and load the related modules
        if ($this->isAdminEnv()) {
            $modules = $this->domain->adminModules;
        } else {
            $modules = $this->domain->notAdminModules;
        }

        return $modules;
    }

    /**
     * Check if we are in the admin environment
     *
     * @return boolean
     */
    protected function isAdminEnv()
    {
        return $this->module->isAdminModule();
    }

    /**
     * Check if the current route is present in the menu
     *
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    protected function isCurrentRouteInMenu($module)
    {
        $currentRoute = request()->route()->getName();

        return $this->isRouteInMenu($module, $currentRoute);
    }

    /**
     * Check if a route is present in the menu
     *
     * @param \Uccello\Core\Models\Module $module
     * @param string $route
     * @return boolean
     */
    protected function isRouteInMenu($module, $route)
    {
        $found = false;

        $modules = $this->getModulesVisibleInMenu();

        foreach ($module->menuLinks as $link) {
            if ($link->route === $route) {
                $found = true;
                break;
            }
        }

        return $found;
    }
}