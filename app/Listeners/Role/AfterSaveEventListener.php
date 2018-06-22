<?php

namespace Sardoj\Uccello\Listeners\Role;

use Sardoj\Uccello\Events\BeforeSaveEvent;
use Sardoj\Uccello\Events\AfterSaveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Sardoj\Uccello\Models\Permission;
use Sardoj\Uccello\Models\Module;
use Uccello;
use Sardoj\Uccello\Models\ProfileRole;
use Sardoj\Uccello\Models\Profile;
use Sardoj\Uccello\Models\Role;

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
     * Update role profiles list.
     *
     * @param  AfterSaveEvent  $event
     * @return void
     */
    public function handle(AfterSaveEvent $event)
    {
        if ($event->module->name !== 'role') {
            return;
        }

        $request = $event->request;
        $role = $event->record;

        $oldProfileIds = $this->getRoleProfileIds($event);
        $newProfileIds = (array) $request->input('profiles');

        try {
            $role->profiles()->attach($newProfileIds);
        } catch (\Exception $e) {
        }

        // Delete obsolete profiles
        $this->deleteObsoleteProfiles($role, $oldProfileIds, $newProfileIds);
    }

    /**
     * Returns all profiles ids for a role.
     *
     * @param AfterSaveEvent $event
     * @return array
     */
    protected function getRoleProfileIds(AfterSaveEvent $event) : array
    {
        $profileIds = [];

        $role = $event->record;

        foreach ($role->profiles as $profile) {
            $profileIds[] = $profile->id;
        }

        return $profileIds;
    }

    /**
     * Delete profiles not still related to the role.
     *
     * @param array $oldProfiles
     * @param array $newProfiles
     * @return void
     */
    protected function deleteObsoleteProfiles(Role $role, array $oldProfileIds, array $newProfileIds)
    {
        $obsoleteProfileIds = [];

        foreach ($oldProfileIds as $oldProfileId) {
            $isObsolete = true;

            foreach ($newProfileIds as $newProfileId) {
                if (in_array($newProfileId, $oldProfileIds)) {
                    $isObsolete = false;
                    break;
                }
            }

            if ($isObsolete) {
                $obsoleteProfileIds[] = $oldProfileId;
            }
        }

        if ($obsoleteProfileIds) {
            $role->profiles()->detach($obsoleteProfileIds);
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
