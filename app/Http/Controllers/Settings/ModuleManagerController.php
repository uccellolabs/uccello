<?php

namespace Uccello\Core\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\Controller;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Menu;

class ModuleManagerController extends Controller
{
    /**
     * Default view name
     *
     * @var string
     */
    protected $viewName = 'module-manager.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:admin');
    }

    /**
     * Displays the list of modules
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

        $mainModules = [];
        $adminModules = [];
        foreach (Module::orderBy('id')->get() as $_module) {
            if ($_module->isAdminModule()) {
                $adminModules[] = $_module;
            } else {
                $mainModules[] = $_module;
            }
        }

        return $this->autoView(compact('mainModules', 'adminModules'));
    }

    /**
     * Activates or deactivates a module on the current domain
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function activation(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Activate or deactivate a module on the current domain
        if ($request->input('src_module')) {
            $sourceModule = ucmodule($request->input('src_module'));
            $isActive = $request->input('active') === '1';

            if ($sourceModule) {
                // Activate the module on the current domain
                if ($isActive === true) {
                    $domain->modules()->attach($sourceModule);
                }
                // Deactivate the module on the current domain
                else {
                    $domain->modules()->detach($sourceModule);
                }
            }
        }

        // Redirect on modules list
        $route = ucroute('uccello.settings.module.manager', $domain);

        return redirect($route);
    }
}