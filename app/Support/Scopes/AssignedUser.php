<?php

namespace Uccello\Core\Support\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AssignedUser implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereIn('assigned_user_id', $this->getAllowedUsersUids());
    }

    protected function getAllowedUsersUids()
    {
        // Use cache
        $allowedUserUids = Cache::rememberForever('allowed_users_for_' . Auth::user()->getKey(), function()
        {
            $user = Auth::user();    

            $allowedUserUids = [$user->uid];

            $groups = [];

            foreach ($user->groups as $group) {
                $groups[$group->uid] = $group;
            };

            $this->addRecursiveChildrenGroups($groups, $groups);

            foreach ($groups as $uid => $group) {
                $allowedUserUids[] = $uid;
            }

            return $allowedUserUids;
        });

        return $allowedUserUids;
    }

    protected function addRecursiveChildrenGroups(&$groups, $searchGroups)
    {
        foreach ($searchGroups as $uid => $searchGroup) 
        {
            $searchChildrenGroups = [];

            foreach ($searchGroup->childrenGroups as $childrenGroup) 
            {
                if(empty($groups[$childrenGroup->uid]))
                {
                    $groups[$childrenGroup->uid] = $childrenGroup;
                    $searchChildrenGroups[$childrenGroup->uid] = $childrenGroup;
                }
            }

            $this->addRecursiveChildrenGroups($groups, $searchChildrenGroups);
        }
    }
}
