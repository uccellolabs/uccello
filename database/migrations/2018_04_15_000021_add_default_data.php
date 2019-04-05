<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Models\User;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Role;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Profile;

class AddDefaultData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addDefaultDomain();
        $profile = $this->addDefaultProfile();
        $this->addDefaultRole($profile);
        $this->addModulesInDomain();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::where('username', 'admin')->forceDelete();
        Domain::where('name', 'Default')->forceDelete();
        Role::where('name', 'Administrator')->forceDelete();
    }

    protected function addDefaultDomain()
    {
        $domain = new Domain();
        $domain->name = 'Uccello';
        $domain->description = null;
        $domain->parent_id = null;
        $domain->save();
    }

    protected function addDefaultProfile()
    {
        $profile = new Profile();
        $profile->name = 'Administration';
        $profile->description = null;
        $profile->domain_id = Domain::first()->id;
        $profile->save();

        return $profile;
    }

    protected function addDefaultRole($profile)
    {
        $role = new Role();
        $role->name = 'Administrator';
        $role->description = null;
        $role->parent_id = null;
        $role->domain_id = Domain::first()->id;
        $role->save();

        $role->profiles()->attach($profile);
    }

    protected function addModulesInDomain()
    {
        $domain = Domain::first();
        $modules = Module::all();

        foreach ($modules as $module) {
            $domain->modules()->save($module);
        }
    }
}
