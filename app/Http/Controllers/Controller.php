<?php

namespace Sardoj\Uccello\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Sardoj\Uccello\Domain
     */
    protected $domain;

    /**
     * @var Sardoj\Uccello\Module
     */
    protected $module;

    /**
     * @var string
     */
    protected $viewName;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Process and display asked page
     * @param Domain $domain
     * @param Module $module
     * 
     * @return \Illuminate\Http\Response
     */
    public function process(Domain $domain, Module $module)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        return view($this->viewName);
    }

    /**
     * Instantiance variables and check permissions
     * @param Domain $domain
     * @param Module $module
     */
    protected function preProcess(Domain $domain, Module $module)
    {
        // Get domain
        $this->domain = $domain;

        // Get module
        $this->module = $module;

        // Check user permissions
        $this->checkPermissions();

        // Share blade variables
        $this->shareBladeVariables();
    }

    /**
     * Share global variables with all Blade views
     */
    protected function shareBladeVariables()
    {
        // Selected domain
        View::share('domain', $this->domain);

        // Selected module
        View::share('module', $this->module);

        // All modules
        View::share('modules', $this->getAllModules());
    }

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        //TODO:To complete
    }

    /**
     * Get all modules from database
     * @return Module[]
     */
    protected function getAllModules()
    {
        return Module::all();
    }
}
