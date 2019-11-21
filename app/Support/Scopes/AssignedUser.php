<?php

namespace Uccello\Core\Support\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Uccello\Core\Models\Entity;

class AssignedUser implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();

        // Records assigned to a group to which the user belongs
        if (!$user->is_admin) {
            $builder->whereIn('assigned_user_id', $user->getAllowedGroupUuids());

            // Records assigned to an user with roles subordonate to the roles of the user
            $builder->orWhereIn('assigned_user_id', function ($query) use ($user) {
                $entityTable = with(new Entity)->getTable();
                $privilegesTable = env('UCCELLO_TABLE_PREFIX', 'uccello_').'privileges';
                $subordonateRolesIds = $user->subordonateRolesIdsOnDomain(request('domain'));

                $query->select($entityTable.'.id')
                    ->from($entityTable)
                    ->join($privilegesTable, function ($join) use($entityTable, $privilegesTable, $subordonateRolesIds) {
                        $join->on("$privilegesTable.user_id", '=', $entityTable.'.record_id')
                        ->whereIn("$privilegesTable.role_id", $subordonateRolesIds);
                    })
                    ->where("$entityTable.module_id", ucmodule('user')->id ?? null);
            });

            // Records created by the user
            if (!empty($model->module)) {
                $builder->orWhereIn($model->getTable().'.'.$model->getKeyName(), function ($query) use($model) {
                    $query->select('record_id')
                    ->from(with(new Entity)->getTable())
                    ->where('module_id', $model->module->id ?? null)
                    ->where('creator_id', auth()->id());
                });
            }
        }
    }
}
