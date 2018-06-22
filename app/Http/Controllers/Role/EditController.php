<?php

namespace Sardoj\Uccello\Http\Controllers\Role;

use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use Sardoj\Uccello\Http\Controllers\Core\EditController as CoreEditController;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Module;
use Sardoj\Uccello\Models\Profile;

class EditController extends CoreEditController
{
    /**
     * {@inheritdoc}
     */
    public function process(Domain $domain, Module $module, Request $request)
    {
        // Get default view
        $view = parent::process($domain, $module, $request);

        // Get record
        $record = $this->getRecordFromRequest();

        // Get profiles already linked to the role
        $selectedProfileIds = [];
        if ($record) {
            foreach ($record->profiles as $profile) {
                // Check if the profile is still in the domain
                // It is just a security because $profiles (see below) is filtered on the domain
                if ($profile->domain->id === $domain->id) {
                    $selectedProfileIds[] = $profile->id;
                }
            }
        }

        // Get all domain profiles
        $profiles = [];
        foreach ($domain->profiles as $profile) {
            $profiles[$profile->id] = $profile->name;
        }

        // Add data to the view
        $view->profiles = $profiles;
        $view->selectedProfileIds = $selectedProfileIds;

        return $view;
    }
}
