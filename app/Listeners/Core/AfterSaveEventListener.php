<?php

namespace Uccello\Core\Listeners\Core;

use Illuminate\Support\Facades\Artisan;
use Uccello\Core\Events\AfterSaveEvent;

class AfterSaveEventListener
{
    /**
     * Activate mandatory modules on domain.
     *
     * @param  AfterSaveEvent  $event
     * @return void
     */
    public function handle(AfterSaveEvent $event)
    {
        // Clear cache
        if (in_array($event->module->name, [ 'domain', 'module', 'user', 'role', 'profile', 'group' ])) {
            Artisan::call('cache:clear');
        }
    }
}
