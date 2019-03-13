<?php

namespace Uccello\Core\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Nicolaslopezj\Searchable\SearchableTrait;
use Uccello\Core\Support\Traits\RelatedlistTrait;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    use Notifiable;
    use RelatedlistTrait;
    use SearchableTrait;

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
    protected $dates = [ 'deleted_at' ];

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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'recordLabel'
    ];

    /**
     * Searchable rules.
     * See https://github.com/nicolaslopezj/searchable
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'username' => 20,
            'first_name' => 10,
            'last_name' => 10,
            'email' => 5
        ]
    ];

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

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [ ];
    }

    /**
     * Returns record label
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return trim($this->first_name.' '.$this->last_name) ?? $this->username;
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
     * @param \Uccello\Core\Models\Domain $domain
     * @return \Illuminate\Support\Collection
     */
    public function rolesOnDomain(Domain $domain) : Collection
    {
        $roles = new Collection();

        foreach ($this->privileges->where('domain_id', $domain->id) as $privilege) {
            $roles[ ] = $privilege->role;
        }

        return $roles;
    }

    /**
     * Returns all user capabilities on a module in a domain.
     * If the user has a capability in one of the parents of a domain, he also has it in that domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
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
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function hasCapabilityOnModule(string $capabilityName, Domain $domain, Module $module) : bool
    {
        $capability = capability($capabilityName);

        $userCapabilities = $this->capabilitiesOnModule($domain, $module);

        return $this->is_admin || $userCapabilities->contains($capability);
    }

    /**
     * Checks if the user can access to settings panel.
     * Checks if the user has at least one admin capability on admin modules in a domain.
     *
     * @param \Uccello\Core\Models\Domain|null $domain
     * @return boolean
     */
    public function canAccessToSettingsPanel(?Domain $domain) : bool
    {
        $hasCapability = false;

        if (empty($domain)) {
            $domain = Domain::first();
        }

        foreach (Module::all() as $module) {
            if ($module->isAdminModule() === true && $this->canAdmin($domain, $module)) {
                $hasCapability = true;
                break;
            }
        }

        return $hasCapability;
    }

    /**
     * Checks if the user can admin a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canAdmin(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('admin', $domain, $module);
    }

    /**
     * Checks if the user can create a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canCreate(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('create', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }

    /**
     * Checks if the user can retrieve a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canRetrieve(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('retrieve', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }

    /**
     * Checks if the user can update a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canUpdate(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('update', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }

    /**
     * Checks if the user can delete a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canDelete(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('delete', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }

    /**
     * Checks if the user can create by API a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canCreateByApi(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('api-create', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }

    /**
     * Checks if the user can retrieve by API a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canRetrieveByApi(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('api-retrieve', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }

    /**
     * Checks if the user can update by API a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canUpdateByApi(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('api-update', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }

    /**
     * Checks if the user can delete by API a module in a domain.
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param \Uccello\Core\Models\Module $module
     * @return boolean
     */
    public function canDeleteByApi(Domain $domain, Module $module) : bool
    {
        return $this->hasCapabilityOnModule('api-delete', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }
}
