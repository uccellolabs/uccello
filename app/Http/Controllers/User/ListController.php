<?php

namespace Uccello\Core\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\ListController as CoreListController;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Support\Traits\WithPrivileges;
use Uccello\Core\Support\Traits\WithRoles;

class ListController extends CoreListController
{
    use WithRoles;
    use WithPrivileges;

    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Get default view
        $view = parent::process($domain, $module, $request);

        // Useful if multi domains is not used
        $domain = $this->domain;

        // Add data to the view
        $view->roles = $this->getAllRolesOnDomain($domain);

        return $view;
    }

    /**
     * Import user from another domain
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @param \Uccello\Core\Models\Module $module
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function import(?Domain $domain, Module $module, Request $request)
    {
        $this->preProcess($domain, $module, $request);

        // Useful if multi domains is not used
        $domain = $this->domain;

        $roleIds = (array)$request->roles;

        $user = User::find($request->user_id);
        if ($user) {
            $this->createPrivilegesForUser($domain, $user, $roleIds);
        }

        $route = ucroute('uccello.list', $domain, $module);

        return redirect($route);
    }

    /**
     * Build query for retrieving content
     *
     * @return \Illuminate\Database\Eloquent\Builder;
     */
    protected function buildContentQuery()
    {
        $filter = [
            'order' => $this->request->get('order'),
            'columns' => $this->request->get('columns'),
        ];

        // Get model model class
        $modelClass = $this->module->model_class;

        // Check if the class exists
        if (!class_exists($modelClass) || !method_exists($modelClass, 'scopeInDomain')) {
            return false;
        }

        // Filter on domain if column exists
        $query = $modelClass::withRoleInDomain($this->domain, $this->request->session()->get('descendants'))
                            ->filterBy($filter);

        // Display trash if filter is selected
        if ($this->isDisplayingTrash()) {
            $query = $query->onlyTrashed();
        }

        return $query;
    }
}
