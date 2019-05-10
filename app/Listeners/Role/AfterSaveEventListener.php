<?php

namespace Uccello\Core\Listeners\Role;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Role;

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
        $selectedProfileIds = (array)$event->request->input('profiles');

        // Note: All selected profiles - Old profiles = New profiles
        $newProfileIds = array_diff($selectedProfileIds, $oldProfileIds);

        $role->profiles()->attach($newProfileIds);

        // Delete obsolete profiles
        $this->deleteObsoleteProfiles($role, $oldProfileIds, $selectedProfileIds);
    }

    /**
     * Delete profiles not still related to the role.
     *
     * @param array $oldProfiles
     * @param array $newProfiles
     * @return void
     */
    protected function deleteObsoleteProfiles(Role $role, array $oldProfileIds, array $selectedProfileIds)
    {
        // Note: Old selected profiles - All selected profiles  = Obsolete profiles (new profiles are ignored)
        $obsoleteProfileIds = array_diff($oldProfileIds, $selectedProfileIds);

        if ($obsoleteProfileIds) {
            $role->profiles()->detach($obsoleteProfileIds);
        }
    }
}
