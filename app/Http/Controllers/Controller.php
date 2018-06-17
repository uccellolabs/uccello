<?php

namespace Sardoj\Uccello\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Sardoj\Uccello\Models\Domain
     */
    protected $domain;

    /**
     * @var Sardoj\Uccello\Models\Module
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
     * @param Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module);

        return $this->autoView();
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

    /**
     * Retrieve record instance if "id" param is defined or return a new empty instance.
     *
     * @param Request $request
     * @return mixed|null
     */
    protected function getRecordFromRequest(Request $request)
    {
        if (empty($this->module->entity_class)) {
            return null;
        }

        // Retrieve entity class
        $entityClass = $this->module->entity_class;
        
        // An id is defined, retrieve the record from the database fail (404) 
        if ($request->has('id')) {
            $recordId = (int) $request->input('id');
            $record = $entityClass::findOrFail($recordId);
        }
        // Make a new empty instance
        else {
            $record = new $entityClass();

            // Set domain if column exists
            if (Schema::hasColumn($record->getTable(), 'domain_id')) {
                $record->domain_id = $this->domain->id;
            }
        }

        return $record;
    }

    /**
     * Detects which view it must use and returns the evaluated view contents.
     *
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function autoView($data = [], $mergeData = [])
    {
        $viewToUse = ucview($this->module, $this->viewName);

        return view($viewToUse, $data, $mergeData);
    }
}
