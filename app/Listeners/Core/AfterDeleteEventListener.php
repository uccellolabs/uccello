<?php

namespace Uccello\Core\Listeners\Core;

use Illuminate\Support\Facades\Artisan;
use Uccello\Core\Events\AfterDeleteEvent;
use Uccello\Core\Models\Entity;

class AfterDeleteEventListener
{

    /**
     * Activate mandatory modules on domain.
     *
     * @param  AfterSaveEvent  $event
     * @return void
     */
    public function handle(AfterDeleteEvent $event)
    {
        Entity::where('module_id', $event->module->id)
            ->where('record_id', $event->record->getKey())
            ->delete();

        // Clear cache
        if (in_array($event->module->name, [ 'domain', 'module', 'user', 'role', 'profile', 'group' ])) {
            Artisan::call('cache:clear');
        }
    }
}
