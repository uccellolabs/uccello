<?php

namespace Uccello\Core\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Uccello\Core\Support\Traits\UccelloModule;
use Uccello\Core\Models\Group;

class User extends Authenticatable implements Searchable
{
    use SoftDeletes;
    use Notifiable;
    use UccelloModule;

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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'avatar' => 'object',
    ];

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
        'recordLabel',
        'uuid',
    ];

    public $searchableType = 'user';

    public $searchableColumns = [
        'username',
        'name',
        'email'
    ];

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->recordLabel
        );
    }

    /**
     * Scope a query to only include users with role in domain.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Uccello\Core\Models\Domain|null $domain
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRoleInDomain($builder, ?Domain $domain, $withDescendants = false)
    {
        if (!$domain) {
            $domain = Domain::first();
        }

        // Check if user is admin or if he has at least a role on the domain
        // or on descendants domains if withDescendants option is on
        $builder->where('domain_id', $domain->id)
            ->orWhereIn($this->getKeyName(), function ($query) use ($domain, $withDescendants) {
                $privilegesTable = env('UCCELLO_TABLE_PREFIX', 'uccello_') . 'privileges';

                $query->select('user_id')
                    ->from($privilegesTable);

                // If necessary display all users with role defined in an ancestor domain
                $domainParentIds = $domain->findAncestors()->pluck('id');
                if (config('uccello.users.display_all_users_with_role')) {
                    $query->whereIn('domain_id', $domainParentIds);
                } else {
                    $query->where('domain_id', $domain->id);
                }

                if (Auth::user() && Auth::user()->canSeeDescendantsRecords($domain) && $withDescendants) {
                    $query->orWhereIn('domain_id', function ($query) use ($domain) {
                        $query->select('id')
                            ->from((new Domain)->getTable())
                            ->where('path', 'like', $domain->id.'/%')
                            ->orWhere('path', 'like', '%/'.$domain->id.'/%')
                            ->get();
                    });
                }
            });

        // If necessary display also all admin users
        if (config('uccello.users.display_all_admin_users')) {
            $builder->orWhere('is_admin', true);
        }

        return $builder;
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

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'uccello_rl_groups_users');
    }

    public function userSettings()
    {
        return $this->hasOne(UserSettings::class, 'user_id');
    }

    /**
     * Returns record label
     *
     * @return string
     */
    public function getRecordLabelAttribute(): string
    {
        return trim($this->name) ?? $this->username;
    }

    /**
     * Get avatar type
     *
     * @return string
     */
    public function getAvatarTypeAttribute(): string
    {
        return $this->avatar->type ?? 'initials';
    }

    /**
     * Returns initals generated from the user name
     *
     * @return string
     */
    public function getInitialsAttribute(): string
    {
        $initials = "";

        $words = explode(" ", strtoupper($this->name));

        $i = 0;
        foreach ($words as $w) {
            $initials .= $w[0];
            $i++;

            if ($i === 3) { // Maximum: 3 letters
                break;
            }
        }

        return $initials;
    }

    /**
     * Returns the image to use as the user avatar
     *
     * @return string
     */
    public function getImageAttribute(): string
    {
        $image = 'vendor/uccello/uccello/images/user-no-image.png';

        if ($this->avatarType === 'gravatar') {
            $image = 'https://www.gravatar.com/avatar/' . md5($this->email) . '?d=mm';
        } elseif ($this->avatarType === 'image' && !empty($this->avatar->path)) {
            $image = $this->avatar->path;
        }

        return $image;
    }

    /**
     * Returns user settings
     *
     * @return \stdClass;
     */
    public function getSettingsAttribute()
    {
        return $this->userSettings->data ?? new \stdClass;
    }

    /**
     * Searches a settings by key and returns the current value
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return \stdClass|null;
     */
    public function getSettings($key, $defaultValue = null)
    {
        return $this->userSettings->data->{$key} ?? $defaultValue;
    }

    /**
     * Returns user's roles on a domain
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param bool $withAncestors
     * @return \Illuminate\Support\Collection
     */
    public function rolesOnDomain($domain, $withAncestors = true): Collection
    {
        // return Cache::remember('user_'.$this->id.'_domain_'.$domain->slug.'_roles', 600, function () use($domain) {
        $roles = collect();

        // Display all user's roles on ancestor domains
        if ($withAncestors && config('uccello.roles.display_ancestors_roles')) {
            $treeDomainsIds = $domain->findAncestors()->pluck('id');
        } else {
            $treeDomainsIds = collect([$domain->id]);
        }

        foreach ($treeDomainsIds as $treeDomainId) {
            $_domain = Domain::find($treeDomainId);
            foreach ($this->privileges->where('domain_id', $_domain->id) as $privilege) {
                if (!$roles->contains($privilege->role)) {
                    $roles[] = $privilege->role;
                }
            }
        }

        return $roles;
        // });
    }

    /**
     * Returns user's privileges on a domain
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @param bool $withAncestors
     * @return \Illuminate\Support\Collection
     */
    public function privilegesOnDomain($domain, $withAncestors = true): Collection
    {
        $privileges = collect();

        // Display all user's roles on ancestor domains
        if ($withAncestors) {
            $treeDomainsIds = $domain->findAncestors()->pluck('id');
        } else {
            $treeDomainsIds = collect([$domain->id]);
        }

        foreach ($treeDomainsIds as $treeDomainId) {
            $_domain = Domain::find($treeDomainId);

            $_privileges = $this->privileges()
                ->where('domain_id', $_domain->id)
                ->with('role')
                ->with('domain')
                ->get();

            foreach ($_privileges as $privilege) {
                if (!$privileges->contains($privilege)) {
                    $privileges[] = $privilege;
                }
            }
        }

        return $privileges;
    }

    /**
     * Returns ids of user's roles on a domain
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return \Illuminate\Support\Collection
     */
    public function subordonateRolesIdsOnDomain($domain): Collection
    {
        $roles = $this->rolesOnDomain($domain);

        $subordonateRoles = collect();
        foreach ($roles as $role) {
            $subordonateRoles = $subordonateRoles->merge($role->findDescendants()->pluck('id'));
        }

        return $subordonateRoles;
    }

    /**
     * Check if the user has at least a role on a domain
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return boolean
     */
    public function hasRoleOnDomain($domain): bool
    {
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
    public function hasRoleOnDescendantDomain(Domain $domain): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $hasRole = false;

        $descendants = Cache::remember('domain_' . $domain->slug . '_descendants', 600, function () use ($domain) {
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
    public function capabilitiesOnModule(Domain $domain, Module $module): Collection
    {
        $keyName = 'user_' . $this->id . '_' . $domain->slug . '_' . $module->name . '_capabilities';

        return Cache::remember($keyName, 600, function () use ($domain, $module) {
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
    public function hasCapabilityOnModule(string $capabilityName, Domain $domain, Module $module): bool
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
    public function canAccessToSettingsPanel(?Domain $domain): bool
    {
        if (empty($domain)) {
            $domain = Domain::first();
        }

        $keyName = 'user_' . $this->id . '_' . $domain->slug . '_can_access_to_settings_panel';

        return Cache::remember($keyName, 600, function () use ($domain) {

            $hasCapability = false;

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
    public function canAdmin(Domain $domain, Module $module): bool
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
    public function canCreate(Domain $domain, Module $module): bool
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
    public function canRetrieve(Domain $domain, Module $module): bool
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
    public function canUpdate(Domain $domain, Module $module): bool
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
    public function canDelete(Domain $domain, Module $module): bool
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
    public function canCreateByApi(Domain $domain, Module $module): bool
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
    public function canRetrieveByApi(Domain $domain, Module $module): bool
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
    public function canUpdateByApi(Domain $domain, Module $module): bool
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
    public function canDeleteByApi(Domain $domain, Module $module): bool
    {
        return $this->hasCapabilityOnModule('api-delete', $domain, $module) || ($module->isAdminModule() && $this->canAdmin($domain, $module));
    }

    /**
     * Checks if the user has almost a role allowing to view data transversally
     *
     * @param \Uccello\Core\Models\Domain $domain
     * @return boolean
     */
    public function canSeeDescendantsRecords(Domain $domain): bool
    {
        $allowed = false;

        if ($this->is_admin) {
            $allowed = true;
        } else {
            $roles = $this->rolesOnDomain($domain);
            foreach ($roles as $role) {
                if ($role->see_descendants_records) {
                    $allowed = true;
                    break;
                }
            }
        }

        return $allowed;
    }

    public function getAllowedGroupUuids()
    {
        // Use cache
        $allowedGroups = Cache::rememberForever(
            'allowed_groups_for_' . ($this->is_admin ? 'admin' : $this->getKey()),
            function () {
                return $this->getAllowedGroupUuidsProcess();
            }
        );

        return $allowedGroups;
    }

    public function getAllowedGroupsAndUsers(Domain $domain, $addUsers = true)
    {
        // Use cache
        $allowedGroupsAndUsers = Cache::rememberForever(
            'allowed_group_users_for_' .
                'd_' . $domain->getKey() . '_' .
                ($addUsers ? 'u_' : '') .
                ($this->is_admin ? 'admin' : $this->getKey()),
            function () use ($domain, $addUsers) {
                return $this->getAllowedGroupsAndUsersProcess($domain, $addUsers);
            }
        );

        return $allowedGroupsAndUsers;
    }

    protected function getAllowedGroupUuidsProcess()
    {
        $allowedUserUuids = collect([$this->uuid]);

        if ($this->is_admin) {
            $groups = Group::all();
        } else {
            $groups = [];
            $users = [];

            foreach ($this->groups as $group) {
                $groups[$group->uuid] = $group;
            };

            $this->addRecursiveChildrenGroups($groups, $users, $groups, false);

            $groups = collect($groups);
        }

        foreach ($groups as $uuid => $group) {
            $allowedUserUuids[] = $uuid;
        }

        return $allowedUserUuids;
    }

    protected function addRecursiveChildrenGroups(&$groups, &$users, $searchGroups, $addUsers = false)
    {
        foreach ($searchGroups as $uuid => $searchGroup) {
            $searchChildrenGroups = [];

            foreach ($searchGroup->childrenGroups as $childrenGroup) {
                if (empty($groups[$childrenGroup->uuid])) {
                    $groups[$childrenGroup->uuid] = $childrenGroup;
                    $searchChildrenGroups[$childrenGroup->uuid] = $childrenGroup;
                }

                if ($addUsers) {
                    foreach ($childrenGroup->users as $user) {
                        if (empty($users[$user->uuid])) {
                            $users[$user->uuid] = $user;
                        }
                    }
                }
            }

            $this->addRecursiveChildrenGroups($groups, $users, $searchChildrenGroups, $addUsers);
        }
    }

    protected function getAllowedGroupsAndUsersProcess(Domain $domain, $addUsers = true)
    {
        $allowedUserUuids = collect([[
            'uuid' => $this->uuid,
            'recordLabel' => uctrans('me', $this->module)
        ]]);

        if (!config('uccello.users.assigned_user_filter_users_groups') || $this->is_admin) {
            $groups = Group::inDomain($domain)->orderBy('name')->get();
            $users  = \App\User::inDomain($domain)->orderBy('name')->get();
        } else {
            $groups = [];
            $users = [];

            foreach ($this->groups as $group) {
                $groups[$group->uuid] = $group;

                if ($addUsers) {
                    foreach ($group->users as $user) {
                        if (empty($users[$user->uuid])) {
                            $users[$user->uuid] = $user;
                        }
                    }
                }
            };

            $this->addRecursiveChildrenGroups($groups, $users, $groups, $addUsers);

            $groups = collect($groups)->sortBy('name');
            $users  = collect($users)->sortBy('name');
        }

        foreach ($groups as $uuid => $group) {
            $allowedUserUuids[] = [
                'uuid' => $group->uuid,
                'recordLabel' => $group->recordLabel
            ];
        }

        foreach ($users as $uuid => $user) {
            if ($user->getKey() != $this->getKey()) {
                $allowedUserUuids[] = [
                    'uuid' => $user->uuid,
                    'recordLabel' => $user->recordLabel
                ];
            }
        }

        return $allowedUserUuids;
    }
}
