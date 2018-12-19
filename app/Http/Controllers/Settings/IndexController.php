<?php

namespace Uccello\Core\Http\Controllers\Settings;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\IndexController as CoreIndexController;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class IndexController extends CoreIndexController
{
    protected $viewName = 'index.main';

    /**
     * Check user permissions
     */
    protected function checkPermissions()
    {
        $this->middleware('uccello.settings');
    }

    /**
     * @inheritDoc
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Pre-process
        $this->preProcess($domain, $module, $request);

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
