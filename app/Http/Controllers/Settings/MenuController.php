<?php

namespace Uccello\Core\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\Controller;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Menu;

class MenuController extends Controller
{
    /**
     * Default view name
     *
     * @var string
     */
    protected $viewName = null;

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
    public function index(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        $this->viewName = 'menu-manager.main';

        $classicMenu = $domain->classicMenu;
        $adminMenu = $domain->adminMenu;

        return $this->autoView(compact('classicMenu', 'adminMenu'));
    }

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
}