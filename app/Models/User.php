<?php

namespace Sardoj\Uccello\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    /**
     * Returns all user capabilities on a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return array
     */
    public function capabilitiesOnModule(Domain $domain, Module $module) : array
    {
        $capabilities = [];

        // Get user privileges on domain
        $privileges = $this->privileges->where('domain_id', $domain->id);

        foreach ($privileges as $privilege) {
            foreach ($privilege->role->profiles as $profile) {
                $capabilities = array_merge($capabilities, $profile->capabilitiesOnModule($module));
            }
        }

        return $capabilities;
    }

    /**
     * Checks if the user has a capability on a module in a domain.
     *
     * @param string $capability
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function hasCapabilityOnModule(string $capability, Domain $domain, Module $module) : bool
    {
        return in_array($capability, $this->capabilitiesOnModule($domain, $module));
    }

    /**
     * Checks if the user can admin a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canAdmin(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_ADMIN, $domain, $module);
    }

    /**
     * Checks if the user can create a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canCreate(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_CREATE, $domain, $module);
    }

    /**
     * Checks if the user can retrieve a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canRetrieve(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_RETRIEVE, $domain, $module);
    }

    /**
     * Checks if the user can update a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canUpdate(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_UPDATE, $domain, $module);
    }

    /**
     * Checks if the user can delete a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canDelete(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_DELETE, $domain, $module);
    }

    /**
     * Checks if the user can create by API a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canCreateByApi(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_CREATE_BY_API, $domain, $module);
    }

    /**
     * Checks if the user can retrieve by API a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canRetrieveByApi(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_RETRIEVE_BY_API, $domain, $module);
    }

    /**
     * Checks if the user can update by API a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canUpdateByApi(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_UPDATE_BY_API, $domain, $module);
    }

    /**
     * Checks if the user can delete by API a module in a domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function canDeleteByApi(Domain $domain, Module $module) : bool
    {
        return $this->is_admin || $this->hasCapabilityOnModule(Permission::CAPABILITY_DELETE_BY_API, $domain, $module);
    }
}
