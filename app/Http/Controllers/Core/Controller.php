<?php

namespace Uccello\Core\Http\Controllers\Core;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Uccello\Core\Models\Domain
     */
    protected $domain;

    /**
     * @var Uccello\Core\Models\Module
     */
    protected $module;

    /**
     * @var Illuminate\Http\Request
     */
    protected $request;

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

        // Check user permissions
        $this->checkPermissions();
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
        $this->preProcess($domain, $module, $request);

        return $this->autoView();
    }

    /**
     * Instantiance variables and check permissions
     * @param Domain $domain
     * @param Module $module
     */
    protected function preProcess(Domain $domain, Module $module, Request $request)
    {
        // Get domain
        $this->domain = $domain;

        // Get module
        $this->module = $module;

        // Get request
        $this->request = $request;

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
        //
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
     * @return mixed|null
     */
    protected function getRecordFromRequest()
    {
        if (empty($this->module->model_class)) {
            return null;
        }

        // Retrieve model class
        $modelClass = $this->module->model_class;

        // An id is defined, retrieve the record from the database fail (404)
        if ($this->request->has('id')) {
            $recordId = (int) $this->request->input('id');
            $record = $modelClass::findOrFail($recordId);
        }
        // Make a new empty instance
        else {
            $record = new $modelClass();

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
        $viewToUse = uccello()->view($this->module, $this->viewName);

        return view($viewToUse, $data, $mergeData);
    }
}
