<?php

namespace Uccello\Core\Listeners\User;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Role;
use Uccello\Core\Models\Privilege;
use Uccello\Core\Support\Traits\WithPrivileges;

class AfterSaveEventListener
{
    use WithPrivileges;

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
     * Update user roles list.
     *
     * @param  AfterSaveEvent  $event
     * @return void
     */
    public function handle(AfterSaveEvent $event)
    {
        if ($event->module->name !== 'user') {
            return;
        }

        $domain = $event->domain;
        $user = $event->record;

        $oldPrivileges = $this->getUserPrivileges($event);
        $newPrivileges = [ ];

        $roleIds = (array)$event->request->input('roles');

        $newPrivileges = $this->createPrivilegesForUser($domain, $user, $roleIds);

        // Delete obsolete privileges
        $this->deleteObsoletePrivileges($oldPrivileges, $newPrivileges);
    }

    /**
     * Return all privilege for an on a domain.
     *
     * @param AfterSaveEvent $event
     * @return array
     */
    protected function getUserPrivileges(AfterSaveEvent $event) : array
    {
        $privileges = [ ];

        $domain = $event->domain;
        $user = $event->record;

        foreach ($user->privileges->where('domain_id', $domain->id) as $privilege) {
            $privileges[ ] = $privilege;
        }

        return $privileges;
    }

    /**
     * Delete privileges not still related to the user.
     *
     * @param array $oldPrivileges
     * @param array $newPrivileges
     * @return void
     */
    protected function deleteObsoletePrivileges(array $oldPrivileges, array $newPrivileges)
    {
        foreach ($oldPrivileges as $oldPrivilege) {
            $isObsolete = true;

            foreach ($newPrivileges as $newPrivilege) {
                if ($oldPrivilege->domain_id === $newPrivilege->domain_id
                    && $oldPrivilege->role_id === $newPrivilege->role_id
                    && $oldPrivilege->user_id === $newPrivilege->user_id) {
                    $isObsolete = false;
                    break;
                }
            }

            if ($isObsolete) {
                $oldPrivilege->delete();
            }
        }
    }
}
