<?php

namespace Uccello\Core\Http\Controllers\User;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\EditController as CoreEditController;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;

class EditController extends CoreEditController
{
    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Get default view
        $view = parent::process($domain, $module, $request);

        // Get record
        $record = $this->getRecordFromRequest();

        // Get roles already linked to the role
        $selectedRoleIds = [ ];

        if ($record) {
            foreach ($record->rolesOnDomain($domain) as $role) {
                $selectedRoleIds[ ] = $role->id;
            }
        }

        // Get all domain roles
        $roles = [ ];
        foreach ($domain->roles as $role) {
            $roles[ $role->id ] = $role->name;
        }

        // Add data to the view
        $view->roles = $roles;
        $view->selectedRoleIds = $selectedRoleIds;

        return $view;
    }
}
