<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Models\Domain;

trait WithRoles
{
    /**
     * Returns all roles available in a domain
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return array
     */
    protected function getAllRolesOnDomain(Domain $domain)
    {
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

        return $roles;
    }
}