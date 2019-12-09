<?php

namespace Uccello\Core\Http\Controllers\User;

use Illuminate\Http\Request;
use Uccello\Core\Http\Controllers\Core\EditController as CoreEditController;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Module;
use Uccello\Core\Support\Traits\WithRoles;

class EditController extends CoreEditController
{
    use WithRoles;

    /**
     * {@inheritdoc}
     */
    public function process(?Domain $domain, Module $module, Request $request)
    {
        // Get default view
        $view = parent::process($domain, $module, $request);

        // Useful if multi domains is not used
        $domain = $this->domain;

        // Get record
        $record = $this->getRecordFromRequest();

        // Get roles already linked to the role
        $selectedRoleIds = [ ];

        if ($record) {
            foreach ($record->rolesOnDomain($domain, false) as $role) {
                $selectedRoleIds[ ] = $role->id;
            }
        }

        // Add data to the view
        $view->roles = $this->getAllRolesOnDomain($domain);
        $view->selectedRoleIds = $selectedRoleIds;

        return $view;
    }
}
