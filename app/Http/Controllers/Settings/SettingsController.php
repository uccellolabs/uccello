<?php

namespace Uccello\Core\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\Controller;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class SettingsController extends Controller
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
        $this->middleware('uccello.settings');
    }

    /**
     * Display a settings dashboard
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function dashboard(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        $this->viewName = 'dashboard.main';

        return $this->autoView([
            'count' => [
                'domains' => Domain::count(),
                'users' => $domain->users()->count(),
                'modules' => $domain->modules()->count(),
                'roles' => $domain->roles()->count(),
            ]
        ]);
    }

    /**
     * Displays the list of modules
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function moduleManager(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        $this->viewName = 'module-manager.main';

        return $this->autoView();
    }

    /**
     * Activates or deactivates a module on the current domain
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function moduleActivation(?Domain $domain, Module $module, Request $request)
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