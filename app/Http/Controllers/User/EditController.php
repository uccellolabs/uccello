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

        // Useful if multi domains is not used
        $domain = $this->domain;

        // Get record
        $record = $this->getRecordFromRequest();

        // Get roles already linked to the role
        $selectedRoleIds = [ ];

        if ($record) {
            foreach ($record->rolesOnDomain($domain) as $role) {
                $selectedRoleIds[ ] = $role->id;
            }
        }

        // Display ancestors roles only if it is allowed
        if (config('uccello.roles.display_ancestors_roles')) {
            $treeDomainsIds = $domain->findAncestors()->pluck('id');
        } else {
            $treeDomainsIds = collect([ $domain->id ]);
        }

        // Get all roles
        $roles = [ ];
        foreach ($treeDomainsIds as $treeDomainId) {
            $_domain = Domain::find($treeDomainId);
            foreach ($_domain->roles as $role) {
                $roleName = $_domain->id === $domain->id ? $role->name : $_domain->name . ' > ' . $role->name;
                $roles[ $role->id ] = $roleName;
            }
        }

        // Add data to the view
        $view->roles = $roles;
        $view->selectedRoleIds = $selectedRoleIds;

        return $view;
    }
}
