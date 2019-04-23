<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Role;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Profile;
use Uccello\Core\Models\Entity;
use App\User;

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
        $domain = Domain::create([
            'name' => 'Uccello',
            'description' => null,
            'parent_id' => null
        ]);

        Entity::create([
            'id' => (string) Str::uuid(),
            'module_id' => ucmodule('domain')->id,
            'record_id' => $domain->getKey(),
        ]);
    }

    protected function addDefaultProfile()
    {
        $profile = Profile::create([
            'name' => 'Administration',
            'description' => null,
            'domain_id' => Domain::first()->id
        ]);

        Entity::create([
            'id' => (string) Str::uuid(),
            'module_id' => ucmodule('profile')->id,
            'record_id' => $profile->getKey(),
        ]);

        return $profile;
    }

    protected function addDefaultRole($profile)
    {
        $role = Role::create([
            'name' => 'Administrator',
            'description' => null,
            'parent_id' => null,
            'domain_id' => Domain::first()->id
        ]);

        $role->profiles()->attach($profile);

        Entity::create([
            'id' => (string) Str::uuid(),
            'module_id' => ucmodule('role')->id,
            'record_id' => $role->getKey(),
        ]);
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
