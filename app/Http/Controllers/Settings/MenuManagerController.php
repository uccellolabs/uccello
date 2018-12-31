<?php

namespace Uccello\Core\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\Controller;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Menu;

class MenuManagerController extends Controller
{
    /**
     * Default view name
     *
     * @var string
     */
    protected $viewName = 'menu-manager.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:admin');
    }

    /**
     * Display a settings dashboard
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Get main menu
        $mainMenu = $domain->mainMenu->data ?? null;
        $mainMenuJson = $this->addLinksAddedAfterMenuCreation($mainMenu, $this->domain->notAdminModules);

        // Get admin menu
        $adminMenu = $domain->adminMenu->data ?? null;
        $adminMenuJson = $this->addLinksAddedAfterMenuCreation($adminMenu, $this->domain->adminModules);

        return $this->autoView(compact('mainMenuJson', 'adminMenuJson'));
    }

    /**
     * Store menu into database
     *
     * @param Domain|null $domain
     * @return \Uccello\Core\Models\Menu
     */
    public function store(?Domain $domain)
    {
        $menu = Menu::firstOrNew([
            'domain_id' => $domain->id,
            'user_id' => null,
            'type' => request('type'),
            ]);
        $menu->data = json_decode(request('structure'));
        $menu->save();

        return $menu;
    }

    /**
     * Reset domain menu into database
     *
     * @param Domain|null $domain
     * @return \Uccello\Core\Models\Menu
     */
    public function reset(?Domain $domain)
    {
        Menu::where('domain_id', $domain->id)->delete();
    }

    /**
     * Add to menu links, the links added after the creation of the menu
     * (e.g. new modules or modules activated after the creation)
     *
     * @param array $links
     * @param array $domainModules
     * @return array
     */
    protected function addLinksAddedAfterMenuCreation($links, $domainModules)
    {
        if (!empty($links)) {
            $addedModules = [];
            $this->getAddedModules($links, $addedModules);

            foreach ($domainModules as $module) {
                //TODO: Check needed capacity
                if (!auth()->user()->canRetrieve($this->domain, $module) || in_array($module->name, $addedModules)) {
                    continue;
                }

                foreach ($module->menuLinks as $link) {
                    $link->type = 'module';
                    $link->module = $module->name;
                    $link->color = 'grey';
                    $link->translation = uctrans($module->name, $module);
                    $links[] = $link;
                }
            }
        }

        return $links;
    }

    /**
     * Recursive function to get all modules present in the menu
     *
     * @param array $links
     * @param array $addedModules
     * @return void
     */
    protected function getAddedModules($links, &$addedModules)
    {
        foreach ($links as $link) {
            if (!empty($link->module) && !in_array($link->module, $addedModules)) {
                $addedModules[] = $link->module;
            }

            if (isset($link->children) && is_array($link->children)) {
                foreach ($link->children as $subLink) {
                    $this->getAddedModules($link->children, $addedModules);
                }
            }
        }
    }
}