<?php

namespace Uccello\Core\Http\Controllers\Module;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\Controller;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class ActivationController extends Controller
{
    protected $viewName = null;

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.permissions:admin');
    }

    /**
     * @inheritDoc
     */
    public function process(?Domain $domain, Module $module, Request $request)
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
        $route = ucroute('uccello.index', $domain, $module);

        return redirect($route);
    }
}