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
use Uccello\Core\Support\MenuGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var \Uccello\Core\Models\Domain
     */
    protected $domain;

    /**
     * @var \Uccello\Core\Models\Module
     */
    protected $module;

    /**
     * @var \Illuminate\Http\Request
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
     * @param Domain|null $domain
     * @param Module $module
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

        return $this->autoView();
    }

    /**
     * Instantiance variables and check permissions
     * @param Domain|null $domain
     * @param Module $module
     * @param boolean $forBlade
     */
    protected function preProcess(?Domain &$domain, Module $module, Request $request, $forBlade=true)
    {
        // If we don't use multi domains, find the first one
        if (!uccello()->useMultiDomains()) {
            $domain = Domain::firstOrFail();
        }

        // Get domain
        $this->domain = $domain;

        // Get module
        $this->module = $module;

        // Get request
        $this->request = $request;

        // Save last visited domain by user
        $this->saveUserLastVisitedDomain();

        // Share blade variables
        if ($forBlade) {
            $this->shareBladeVariables();
        }
    }

    /**
     * Save last visited domain by user
     *
     * @return void
     */
    protected function saveUserLastVisitedDomain()
    {
        $user = Auth::user();
        $user->last_domain_id = $this->domain->id;
        $user->save();
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
        View::share('modules', $this->getAllModules(true));

        // Admin environment
        View::share('admin_env', $this->module->isAdminModule());

        // Menu
        $menu = Cache::remember('menu_'.$this->domain->slug.'_'.auth()->id(), 600, function () {
            $menuGenerator = new MenuGenerator();
            $menuGenerator->makeMenu($this->domain, $this->module);
            return $menuGenerator->getMenu();
        });
        View::share('menu', $menu);

        // Domain tree
        $domainsTreeHtml = $this->getDomainsTreeHtml();
        View::share('domainsTreeHtml', $domainsTreeHtml);
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
     * @param boolean $getAdminModules
     * @return Module[]
     */
    protected function getAllModules($getAdminModules = false)
    {
        $modules = [ ];

        $allModules = Module::all();

        if ($getAdminModules) {
            $modules = $allModules;
        } else {
            foreach ($allModules as $module) {
                if (!$module->isAdminModule()) {
                    $modules[ ] = $module;
                }
            }
        }

        return $modules;
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
            $recordId = (int)$this->request->input('id');
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
     * Get HTML code for domains tree, according to user permissions.
     *
     * @return void
     */
    protected function getDomainsTreeHtml()
    {
        return Cache::remember('users', 600, function () { // 10 minutes
            $domainsTreeHtml = '<ul class="tree tree-level-0">';

            $rootDomains = app('uccello')->getRootDomains();
            foreach($rootDomains as $root) {

                $descendants = $root->findDescendants()->get();

                $tree = $root->buildTree($descendants);

                $html = $tree->render(
                    'ul',
                    function ($node) {
                        if (auth()->user()->hasRoleOnDomain($node)) {
                            $currentClass = '';
                            if ($node->id === $this->domain->id) {
                                $currentClass = 'class="green-text"';
                            }
                            return '<li><a href="'.ucroute('uccello.home', $node).'" '.$currentClass.'>'.$node->name.'</a>{sub-tree}</li>';
                        }
                        elseif (auth()->user()->hasRoleOnDescendantDomain($node)) {
                            return '<li>'.$node->name.'{sub-tree}</li>';
                        } else {
                            return '';
                        }
                    },
                    TRUE
                );

                $domainsTreeHtml .= preg_replace('`^<ul class="tree tree-level-0">(.+?)</ul>$`', '$1', $html);
            }

            $domainsTreeHtml .= '</ul>';

            return $domainsTreeHtml;
        });
    }

    /**
     * Detects which view it must use and returns the evaluated view contents.
     *
     * @param  array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function autoView($data = [ ], $mergeData = [ ])
    {
        $viewToUse = uccello()->view($this->module->package, $this->module, $this->viewName);

        return view($viewToUse, $data, $mergeData);
    }
}
