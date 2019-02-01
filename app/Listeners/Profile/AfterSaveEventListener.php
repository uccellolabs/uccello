<?php

namespace Uccello\Core\Listeners\Profile;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Permission;
use Uccello\Core\Models\Module;
use Uccello;
use Uccello\Core\Models\Capability;

class AfterSaveEventListener
{
    protected $auth;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Update profile permissions list.
     *
     * @param  \Uccello\Core\Events\AfterSaveEvent  $event
     * @return void
     */
    public function handle(AfterSaveEvent $event)
    {
        if ($event->module->name !== 'profile') {
            return;
        }

        $domain = $event->domain;
        $request = $event->request;
        $profile = $event->record;
        $user = $this->auth->user();

        $oldPermissions = $this->getProfilePermissions($event);
        $newPermissions = [ ];

        $permissions = (array)$request->input('permissions');

        foreach ($permissions as $moduleName => $capabilities) {
            // Retrieve module from name
            $module = Module::where('name', $moduleName)->with('domains')->first();

            // Check if the module is active on the domain and if the user can admin it
            if ($module && !$module->isActiveOnDomain($domain) || !$user->canAdmin($domain, $module)) {
                continue;
            }

            // Create new permissions from capabilities list
            foreach ($capabilities as $capabilityName => $value) {
                $capability = capability($capabilityName);

                // Check if the capability really exists
                if (is_null($capability)) {
                    continue;
                }

                // Create a new permisison and ignore duplicates
                $newPermissions[ ] = Permission::firstOrCreate([
                    'profile_id' => $profile->id,
                    'module_id' => $module->id,
                    'capability_id' => $capability->id
                ]);
            }
        }

        // Delete obsolete permissions
        $this->deleteObsoletePermissions($oldPermissions, $newPermissions);
    }

    /**
     * Return all permissions for a profile on a domain.
     *
     * @param \Uccello\Core\Events\AfterSaveEvent $event
     * @return array
     */
    protected function getProfilePermissions(AfterSaveEvent $event) : array
    {
        $permissions = [ ];

        $domain = $event->domain;
        $profile = $event->record;
        $user = $this->auth->user();

        $profilePermissions = Permission::where('profile_id', $profile->id)->get();

        foreach ($profilePermissions as $permission) {
            if ($user->canAdmin($domain, $permission->module)) {
                $permissions[ ] = $permission;
            }
        }

        return $permissions;
    }

    /**
     * Delete permissions not still defined in the profile.
     *
     * @param array $oldPermissions
     * @param array $newPermissions
     * @return void
     */
    protected function deleteObsoletePermissions(array $oldPermissions, array $newPermissions)
    {
        foreach ($oldPermissions as $oldPermission) {
            $isObsolete = true;

            foreach ($newPermissions as $newPermission) {
                if ($oldPermission->capability_id === $newPermission->capability_id
                    && $oldPermission->module_id === $newPermission->module_id) {
                    $isObsolete = false;
                    break;
                }
            }

            if ($isObsolete) {
                $oldPermission->delete();
            }
        }
    }
}
