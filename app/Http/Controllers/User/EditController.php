<?php

namespace Sardoj\Uccello\Http\Controllers\User;

use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use Sardoj\Uccello\Http\Controllers\Core\EditController as CoreEditController;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;
use Sardoj\Uccello\Models\Profile;

class EditController extends CoreEditController
{
    /**
     * {@inheritdoc}
     */
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Get default view
        $view = parent::process($domain, $module, $request);

        // Get record
        $record = $this->getRecordFromRequest();

        // Get roles already linked to the role
        $selectedRoleIds = [];

        if ($record) {
            foreach ($record->rolesOnDomain($domain) as $role) {
                $selectedRoleIds[] = $role->id;
            }
        }

        // Get all domain roles
        $roles = [];
        foreach ($domain->roles as $role) {
            $roles[$role->id] = $role->name;
        }

        // Add data to the view
        $view->roles = $roles;
        $view->selectedRoleIds = $selectedRoleIds;

        return $view;
    }
}
