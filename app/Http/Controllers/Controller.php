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
     * @param string $domain
     * @param string $module
     * 
     * @return \Illuminate\Http\Response
     */
    public function process(string $domain, string $module)
    {
        $this->preProcess($domain, $module);

        return view($this->viewName);
    }

    /**
     * Instantiance variables and check permissions
     * @param string $domain
     * @param string $module
     */
    protected function preProcess(string $domain, string $module)
    {
        // Get domain by slug
        $this->getDomainBySlug($domain);

        // Get module by name
        $this->getModuleByName($module);

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
        
    }

    /**
     * Retrieve domain by slug from the cache, else from the database
     * @param string $slug
     */
    protected function getDomainBySlug(string $slug)
    {
        // TODO: invalidate cache if necessary
        $this->domain = Cache::rememberForever('domain_'.$slug, function() use ($slug) {
            return Domain::where("slug", $slug)->firstOrFail();
        });
    }

    /**
     * Retrieve module by name from the cache, else from the database
     * @param string $name
     */
    protected function getModuleByName(string $name)
    {
        // TODO: invalidate cache if necessary
        $this->module = Cache::rememberForever('module_'.$name, function() use ($name) {
            return Module::where("name", $name)->firstOrFail();
        });
    }

    /**
     * Get all modules from database
     * @return Array<Module>
     */
    protected function getAllModules()
    {
        return Cache::rememberForever('modules', function() {
            return Module::all();
        });
    }
}
