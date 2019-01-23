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
}