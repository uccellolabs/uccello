<?php

namespace Sardoj\Uccello\Listeners\Role;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Sardoj\Uccello\Events\AfterSaveEvent;
use Sardoj\Uccello\Models\Profile;
use Sardoj\Uccello\Models\Role;
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

        $role = $event->record;

        $oldProfileIds = $role->profiles->pluck('id')->toArray();
        $newProfileIds = (array) $event->request->input('profiles');

        try {
            $role->profiles()->attach($newProfileIds);
        } catch (\Exception $e) {
            // Profile no longer exists
        }

        // Delete obsolete profiles
        $this->deleteObsoleteProfiles($role, $oldProfileIds, $newProfileIds);
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
        $obsoleteProfileIds = array_diff($oldProfileIds, $newProfileIds);

        if ($obsoleteProfileIds) {
            $role->profiles()->detach($obsoleteProfileIds);
        }
    }
}
