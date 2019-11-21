<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Models\Entity;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Filter;
use Illuminate\Support\Facades\Cache;
use Uccello\Core\Support\Scopes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Uccello\Core\Models\Domain;

trait UccelloModule
{
    use RelatedlistTrait;

    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    protected static function bootUccelloModule()
    {
        if (static::isFilteredByUser()) {
            static::addGlobalScope(new Scopes\AssignedUser);
        }

        // Create uuid after save
        static::created(function ($model) {
            $module = Module::where('model_class', get_class($model))->first();
            if ($module) {
                Entity::create([
                    'id' => (string) Str::uuid(),
                    'module_id' => $module->id,
                    'record_id' => $model->getKey(),
                    'creator_id' => auth()->id(),
                ]);
            }
        });

        // Delete uuid after forced delete
        static::deleted(function ($model) {
            if (!empty($model->uuid) && (!method_exists($model, 'isForceDeleting') || $model->isForceDeleting() === true)) {
                $entity = Entity::find($model->uuid);
                if ($entity) {
                    $entity->delete();
                }
            }
        });
    }

    public function initializeUccelloModule()
    {
        $this->appends = array_merge($this->appends, ['recordLabel','uuid']);
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

        $module = $this->module;

        if ($module) {
            $entity = Entity::where('module_id', $module->getKey())
                ->where('record_id', $this->getKey())
                ->first();

            if ($entity) {
                $uuid = $entity->getKey();
            }
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

    /**
     * Returns user who created the entity, if defined.
     *
     * @return \Uccello\Core\Models\User|null
     */
    public function getCreatorAttribute()
    {
        $creator = null;

        $module = $this->module;

        if ($module) {
            $entity = Entity::where('module_id', $module->getKey())
                ->where('record_id', $this->getKey())
                ->first();

                dd($this->getAttributes());

            if ($entity) {
                $creator = $entity->creator;
            }
        }

        return $creator;
    }

    public function scopeInDomain($query, ?Domain $domain, $withDescendants = false)
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

    /**
     * Add filter conditions to a query.
     * @param QueryBuilder $query
     * @param Filter|int|array $filter
     * @return QueryBuilder|null
     */
    public function scopeFilterBy($query, $filter)
    {
        $filterModel = null;

        if (!empty($filter)) {
            // $filter: int id
            if (is_numeric($filter)) {
                // TODO: Check permissions ?? (domain, user)
                $filterModel = Filter::where('id', $filter)
                                        ->where('module_id', $this->module->getKey())
                                        ->first();
            }
            // $filter: array data
            elseif (is_array($filter)) {
                $filterModel = Filter::newFromData($filter);
            }
            // $filter: Filter model
            elseif (substr(strrchr(get_class($filter), "\\"), 1) == 'Filter') {
                $filterModel = $filter;
            }

            if ($filterModel) {
                // Conditions
                if (!empty($filterModel->conditions)) {
                    // Search
                    if (!empty($filterModel->conditions->search)) {
                        foreach ($filterModel->conditions->search as $fieldName => $searchValue) {
                            // Get field by name and search by field column
                            $field = $this->module->getField($fieldName);
                            if (isset($searchValue) && !is_null($field)) {
                                $uitype = uitype($field->uitype_id);
                                $query = $uitype->addConditionToSearchQuery($query, $field, $searchValue);
                            }
                        }
                    }
                }

                // Order results
                if (!empty($filterModel->order)) {
                    foreach ($filterModel->order as $fieldName => $value) {
                        // Get field by name and search by field column
                        $field = $this->module->getField($fieldName);
                        if (!is_null($field)) {
                            $query = $query->orderBy($field->column, $value);
                        }
                    }
                }
            }
        }

        return $query;
    }
}
