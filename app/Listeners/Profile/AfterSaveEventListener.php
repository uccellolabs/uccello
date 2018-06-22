<?php

namespace Sardoj\Uccello\Listeners\Profile;

use Sardoj\Uccello\Events\BeforeSaveEvent;
use Sardoj\Uccello\Events\AfterSaveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Sardoj\Uccello\Models\Permission;
use Sardoj\Uccello\Models\Module;
use Uccello;

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
     * @param  AfterSaveEvent  $event
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
        $newPermissions = [];

        $permissions = (array) $request->input('permissions');

        foreach ($permissions as $moduleName => $capabilities) {
            // Retrieve module from name
            $module = Module::where('name', $moduleName)->with('domains')->first();

            // Check if the module is active on the domain and if the user can admin it
            if ($module && !$module->isActiveOnDomain($domain) || !$user->canAdmin($domain, $module)) {
                continue;
            }

            // Create new permissions from capabilities list
            foreach ($capabilities as $capability => $value) {
                // Check if the capability really exists
                if (!$this->capabilityExists($capability)) {
                    continue;
                }

                // Create a new permisison (ignore duplicates with "try")
                try {
                    $permission = new Permission();
                    $permission->profile_id = $profile->id;
                    $permission->module_id = $module->id;
                    $permission->capability = $capability;

                    // Add new permission
                    $newPermissions[] = $permission;

                    $permission->save();

                } catch (\Exception $e) {
                }
            }
        }

        // Delete obsolete permissions
        $this->deleteObsoletePermissions($oldPermissions, $newPermissions);
    }

    /**
     * Return all permissions for a profile on a domain.
     *
     * @param AfterSaveEvent $event
     * @return array
     */
    protected function getProfilePermissions(AfterSaveEvent $event) : array
    {
        $permissions = [];

        $domain = $event->domain;
        $profile = $event->record;
        $user = $this->auth->user();

        $profilePermissions = Permission::where('profile_id', $profile->id)->get();

        foreach ($profilePermissions as $permission) {
            if ($user->canAdmin($domain, $permission->module)) {
                $permissions[] = $permission;
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
                if ($oldPermission->capability === $newPermission->capability
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

    /**
     * Checks if a capability exists.
     * Note: A capability exists if it is defined in Sardoj\Uccello\Models\Permission
     *
     * @param string $capability
     * @return boolean
     *
     * @see Sardoj\Uccello\Helpers\Uccello
     */
    protected function capabilityExists(string $capability) : bool
    {
        return in_array($capability, Uccello::getCapabilities());
    }
}
