<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Role;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Profile;
use App\User;
use Uccello\Core\Facades\Uccello;
use Uccello\Core\Models\Permission;

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
        Domain::where('name', 'Default')->forceDelete();
        Role::where('name', 'Administrator')->forceDelete();
    }

    protected function addDefaultDomain()
    {
        Domain::create([
            'name' => 'Uccello',
            'description' => null,
            'parent_id' => null
        ]);
    }

    protected function addDefaultProfile()
    {
        $profile = Profile::create([
            'name' => 'Administration',
            'description' => null,
            'domain_id' => Domain::first()->id
        ]);

        foreach (Module::all() as $module) {
            foreach (Uccello::getCapabilities() as $capability) {
                Permission::firstOrCreate([
                    'profile_id' => $profile->id,
                    'module_id' => $module->id,
                    'capability_id' => $capability->id
                ]);
            }
        }

        return $profile;
    }

    protected function addDefaultRole($profile)
    {
        $role = Role::create([
            'name' => 'CEO',
            'description' => null,
            'parent_id' => null,
            'domain_id' => Domain::first()->id
        ]);

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
