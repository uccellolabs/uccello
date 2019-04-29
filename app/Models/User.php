<?php

namespace Uccello\Core\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Uccello\Core\Support\Traits\RelatedlistTrait;

class User extends Authenticatable implements Searchable
{
    use SoftDeletes;
    use Notifiable;
    use RelatedlistTrait;

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
        'username',
        'name',
        'email',
        'password',
        'is_admin',
        'domain_id'
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

    public $searchableType = 'user';

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
     * Returns record label
     *
     * @return string
     */
    public function getRecordLabelAttribute() : string
    {
        return trim($this->name) ?? $this->username;
    }

    /**
     * Returns user's roles on a domain
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return \Illuminate\Support\Collection
     */
    public function rolesOnDomain($domain) : Collection
    {
        return Cache::remember('domain_'.$domain->slug.'_roles', 600, function () use($domain) {
            $roles = collect();

            foreach ($this->privileges->where('domain_id', $domain->id) as $privilege) {
                $roles[ ] = $privilege->role;
            }

            return $roles;
        });

    }

    /**
     * Check if the user has at least a role on a domain
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return boolean
     */
    public function hasRoleOnDomain($domain) : bool {
        if ($this->is_admin) {
            return true;
        }

        return $this->rolesOnDomain($domain)->count() > 0;
    }

    /**
     * Check if the user has at least a role on a domain or its descendants
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return boolean
     */
    public function hasRoleOnDescendantDomain(Domain $domain) : bool {
        if ($this->is_admin) {
            return true;
        }

        $hasRole = false;

        $descendants = Cache::remember('domain_'.$domain->slug.'_descendants', 600, function () use($domain) {
            return $domain->findDescendants()->get();
        });

        foreach ($descendants as $descendant) {
            if ($this->hasRoleOnDomain($descendant)) {
                $hasRole = true;
                break;
            }
        }

        return $hasRole;
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
        $keyName = 'user_'.$this->id.'_'.$domain->slug.'_'.$module->name.'_capabilities';

        return Cache::remember($keyName, 600, function () use($domain, $module) {
            $capabilities = collect();

            // Get the domain and all its parents
            $domainParents = $domain->findAncestors()->get();

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
        });
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
        $keyName = 'user_'.$this->id.'_'.$domain->slug.'can_access_to_settings_panel';

        return Cache::remember($keyName, 600, function () use($domain) {

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
        });
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
