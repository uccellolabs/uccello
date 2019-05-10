<?php

namespace Uccello\Core\Listeners\Core;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Entity;

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
        if ($event->mode === 'create') {
            Entity::create([
                'id' => (string) Str::uuid(),
                'module_id' => $event->module->id,
                'record_id' => $event->record->getKey(),
            ]);
        }

        // Clear cache
        if (in_array($event->module->name, [ 'domain', 'module', 'user', 'role', 'profile' ])) {
            Artisan::call('cache:clear');
        }
    }
}
