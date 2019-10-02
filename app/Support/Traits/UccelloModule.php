<?php

namespace Uccello\Core\Support\Traits;

use Uccello\Core\Models\Entity;
use Uccello\Core\Models\Module;
use Illuminate\Support\Facades\Cache;
use Uccello\Core\Support\Scopes\AssignedUser;
use Illuminate\Support\Facades\Auth;

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

        if(static::isFilteredByUser())
        {
            static::addGlobalScope(new AssignedUser);
        }
    }

    protected static function isFilteredByUser()
    {
        $user = Auth::user();

        if($user && !$user->is_admin)
        {
            $module = static::getModuleFromClass(static::class);
            
            if($module && $module->data && property_exists($module->data, 'private') &&  $module->data->private)
            {
                return true;
            }
        }

        return false;
    }

    public function gettableAttribute()
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

    public function getUidAttribute()
    {
        $uid = null;

        $entity = Entity::where('module_id', $this->module->getKey())
                        ->where('record_id', $this->getKey())
                        ->first();

        if($entity)
        {
            $uid = $entity->getKey();
        }

        return $uid;
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
}