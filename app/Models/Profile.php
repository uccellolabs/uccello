<?php

namespace Uccello\Core\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Uccello\Core\Database\Eloquent\Model;

class Profile extends Model implements Searchable
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'domain_id'
    ];

    public $searchableType = 'profile';

    public $searchableColumns = [
        'name'
    ];

    public function getSearchResult(): SearchResult
    {    
        return new SearchResult(
            $this,
            $this->recordLabel
        );
    }

    protected function initTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, $this->tablePrefix.'profiles_roles');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Returns record label
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return $this->name;
    }

    /**
     * Returns profile capabilities on a module
     *
     * @param Module $module
     * @return \Illuminate\Support\Collection
     */
    public function capabilitiesOnModule(Module $module) : Collection
    {
        $capabilities = new Collection();

        // Get profile permissions on module
        $permissions = $this->permissions->where('module_id', $module->id);

        foreach ($permissions as $permission) {
            $capabilities[ ] = $permission->capability;
        }

        return $capabilities->unique();
    }

    /**
     * Checks if the profil has a capability on a module
     *
     * @param Capability $capability
     * @param Module $module
     * @return boolean
     */
    public function hasCapabilityOnModule(Capability $capability, Module $module) : bool
    {
        $hasCapability = false;

        foreach ($this->capabilitiesOnModule($module) as $capabilityOnModule) {
            if ($capabilityOnModule->id === $capability->id) {
                $hasCapability = true;
                break;
            }
        }

        return $hasCapability;
    }
}
