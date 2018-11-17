<?php

namespace Uccello\Core\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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

    protected function setTablePrefix()
    {
        $this->tablePrefix = env('UCCELLO_TABLE_PREFIX', 'uccello_');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function lastDomain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function privileges()
    {
        return $this->hasMany(Privilege::class);
    }

    /**
     * Returns record label
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return trim($this->first_name . ' ' .$this->last_name) ?? $this->username;
    }

    // public function getAccessibleDomainsAttribute() : Collection
    // {
    //     $domains = new Collection();

    //     $rootDomains = Domain::whereNull('parent_id');

    //     foreach ($rootDomains as $domain) {

    //     }

    //     return $domains;
    // }

    /**
     * Returns user roles on a domain
     *
     * @param Domain $domain
     * @return \Illuminate\Support\Collection
     */
    public function rolesOnDomain(Domain $domain) : Collection
    {
        $roles = new Collection();

        foreach ($this->privileges->where('domain_id', $domain->id) as $privilege) {
            $roles[] = $privilege->role;
        }

        return $roles;
    }

    /**
     * Returns all user capabilities on a module in a domain.
     * If the user has a capability in one of the parents of a domain, he also has it in that domain.
     *
     * @param Domain $domain
     * @param Module $module
     * @return \Illuminate\Support\Collection
     */
    public function capabilitiesOnModule(Domain $domain, Module $module) : Collection
    {
        $capabilities = new Collection();

        // Get the domain and all its parents
        $domainParents = $domain->parents();

        // Get user privileges on each domain
        foreach ($domainParents as $_domain) {
            $privileges = $this->privileges->where('domain_id', $_domain->id);

            foreach ($privileges as $privilege) {

                foreach ($privilege->role->profiles as $profile) {
                    $capabilities = $capabilities->merge($profile->capabilitiesOnModule($module));
                }
            }
        }

        return $capabilities;
    }

    /**
     * Checks if the user has a capability on a module in a domain.
     *
     * @param string $capabilityName
     * @param Domain $domain
     * @param Module $module
     * @return boolean
     */
    public function hasCapabilityOnModule(string $capabilityName, Domain $domain, Module $module) : bool
    {
        $capability = capability($capabilityName);

        $userCapabilities = $this->capabilitiesOnModule($domain, $module);

        return $this->is_admin || $userCapabilities->contains($capability);
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
        return $this->hasCapabilityOnModule('admin', $domain, $module);
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
        return $this->hasCapabilityOnModule('create', $domain, $module);
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
        return $this->hasCapabilityOnModule('retrieve', $domain, $module);
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
        return $this->hasCapabilityOnModule('update', $domain, $module);
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
        return $this->hasCapabilityOnModule('delete', $domain, $module);
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
        return $this->hasCapabilityOnModule('api-create', $domain, $module);
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
        return $this->hasCapabilityOnModule('api-retrieve', $domain, $module);
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
        return $this->hasCapabilityOnModule('api-update', $domain, $module);
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
        return $this->hasCapabilityOnModule('api-delete', $domain, $module);
    }
}
