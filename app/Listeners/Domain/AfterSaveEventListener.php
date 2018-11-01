<?php

namespace Uccello\Core\Listeners\Domain;

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Uccello\Core\Events\AfterSaveEvent;
use Uccello\Core\Models\Module;
use Uccello;
use Uccello\Core\Models\Domain;

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
        if ($event->module->name !== 'domain') {
            return;
        }

        $domain = $event->record;

        if ($event->mode === 'create') {
            $this->activateModulesOnDomain($domain);
        }
    }

    /**
     * Activate home and admin modules on domain
     *
     * @return void
     */
    protected function activateModulesOnDomain($record)
    {
        // Get all modules
        $modules = Module::all();

        // Reload the domain to be able to attach modules
        $domain = Domain::find($record->getKey());

        // Attach admin modules to the domain
        foreach ($modules as $module) {
            // if ((isset($module->data->admin) && $module->data->admin === true)
            //     || (isset($module->data->mandatory) && $module->data->mandatory === true)) {
            //     $domain->modules()->attach($module);
            // }

            // Activate all modules (for the moment)
            $domain->modules()->attach($module);
        }
    }
}
