<?php

namespace Uccello\Core\Support\Traits;

use App\User;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Privilege;
use Uccello\Core\Models\Role;

trait WithPrivileges
{
    /**
     * Creates privileges for user in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\User $user
     * @param array $roleIds
     * @return array
     */
    protected function createPrivilegesForUser(Domain $domain, User $user, array $roleIds)
    {
        $privileges = [ ];

        foreach ($roleIds as $roleId) {
            $role = Role::find($roleId);

            // Get ancestors domains only if it is allowed
            if (config('uccello.roles.display_ancestors_roles')) {
                $treeDomainsIds = $domain->findAncestors()->pluck('id');
            } else {
                $treeDomainsIds = collect([ $domain->id ]);
            }

            if (is_null($role) || !$treeDomainsIds->contains($role->domain->id)) {
                continue;
            }

            // Create a new privilege and ignore duplicates
            $privileges[ ] = Privilege::firstOrCreate([
                'domain_id' => $domain->id,
                'role_id' => $role->id,
                'user_id' => $user->id
            ]);
        }

        return $privileges;
    }
}