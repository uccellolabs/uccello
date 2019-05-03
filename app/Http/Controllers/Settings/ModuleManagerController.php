<?php

namespace Uccello\Core\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Uccello\Core\Http\Controllers\Core\Controller;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

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

        $mainModules = [ ];
        $adminModules = [ ];
        foreach (Module::orderBy('id')->get() as $_module) {
            if ($_module->isAdminModule()) {
                $adminModules[ ] = $_module;
            } else {
                $mainModules[ ] = $_module;
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
     * @return array
     */
    public function activation(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        // Activate or deactivate a module on the current domain
        $success = false;
        $error = '';
        if (request('src_module')) {
            $sourceModule = ucmodule(request('src_module'));
            $isActive = request('active') == 1;

            if ($sourceModule) {
                // Activate the module on the current domain
                if ($isActive === true) {
                    $domain->modules()->attach($sourceModule);
                    $success = true;
                    $message = 'message.module_activated';
                }
                // Deactivate the module on the current domain only if it is not mandatory
                elseif (!$sourceModule->isMandatory()) {
                    $domain->modules()->detach($sourceModule);
                    $success = true;
                    $message = 'message.module_deactivated';
                }
                // Impossible to deactivate a mandatory module
                else {
                    $error = 'error.module_is_mandatory';
                }
            }
        }
        // Module name is not defined
        else {
            $error = 'error.module_not_defined';
        }

        $result = [
            'success' => $success
        ];

        // Add message if defined
        if (!empty($message)) {
            $result[ 'message' ] = uctrans($message, $module);
        }

        // Add error if defined
        if (!empty($error)) {
            $result[ 'error' ] = uctrans($error, $module);
        }

        if ($success) {
            Artisan::call('cache:clear');
        }

        return $result;
    }
}