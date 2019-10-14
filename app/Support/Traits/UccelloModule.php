<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Models\Entity;
use Uccello\Core\Models\Module;
use Illuminate\Support\Facades\Cache;
use Uccello\Core\Support\Scopes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Uccello\Core\Models\Domain;

trait UccelloModule
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        if (static::isFilteredByUser()) {
            static::addGlobalScope(new Scopes\AssignedUser);
        }
    }

    protected static function isFilteredByUser()
    {
        $isFilteredByUser = false;

        $user = Auth::user();

        if ($user && !$user->is_admin) {
            $module = static::getModuleFromClass(static::class);

            if ($module && $module->data && property_exists($module->data, 'private') && $module->data->private) {
                $isFilteredByUser = true;
            }
        }

        return $isFilteredByUser;
    }

    public function scopeInDomain($query, ?Domain $domain, $withDescendants=false)
    {
        if (!empty($domain) && Schema::hasColumn($this->table, 'domain_id')) {
            // Activate descendant view if the user is allowed
            if (Auth::user()->canSeeDescendantsRecords($domain) && $withDescendants) {
                $domainsIds = $domain->findDescendants()->pluck('id');
                $query = $query->whereIn('domain_id', $domainsIds);
            } else {
                $query = $query->where('domain_id', $domain->id);
            }
        }

        return $query;
    }

    public function getTableAttribute()
    {
        return $this->table;
    }

    public function getModuleAttribute()
    {
        return static::getModuleFromClass(get_class($this));
    }

    protected static function getModuleFromClass($className)
    {
        $modules = Cache::rememberForever('modules_by_model_class', function () {
            $modulesGroupedByModelClass = collect();
            Module::all()->map(function ($item) use ($modulesGroupedByModelClass) {
                $modulesGroupedByModelClass[$item->model_class] = $item;
                return $modulesGroupedByModelClass;
            });
            return $modulesGroupedByModelClass;
        });
        return $modules[(string) $className] ?? null;
    }

    public function getUuidAttribute()
    {
        $uuid = null;

        $entity = Entity::where('module_id', $this->module->getKey())
                        ->where('record_id', $this->getKey())
                        ->first();

        if ($entity) {
            $uuid = $entity->getKey();
        }

        return $uuid;
    }

    /**
     * Returns Assigned User
     *
     * @return string|null
     */
    public function getAssignedUserAttribute(): ?string
    {
        return $this->assigned_user_id;
    }

    public function scopeFilterBy($query, $filter)
    {        
        if (!empty($filter)) {
            // Search by column
            foreach ($filter['columns'] as $fieldName => $column) {
                if (!empty($column["search"])) {
                    $searchValue = $column["search"];
                } else {
                    $searchValue = null;
                }

                // Get field by name and search by field column
                $field = $this->module->getField($fieldName);
                if (isset($searchValue) && !is_null($field)) {
                    $uitype = uitype($field->uitype_id);
                    $query = $uitype->addConditionToSearchQuery($query, $field, $searchValue);
                }
            }

            // Order results
            if (!empty($filter['order'])) {
                foreach ($filter['order'] as $fieldColumn => $value) {
                    if (!is_null($field)) {
                        $query = $query->orderBy($fieldColumn, $value);
                    }
                }
            }
        }

        return $query;
    }
}