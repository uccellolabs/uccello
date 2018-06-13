<?php

use Illuminate\Database\Migrations\Migration;
use Sardoj\Uccello\Models\User;
use Sardoj\Uccello\Models\Domain;
use Sardoj\Uccello\Models\Role;
use Sardoj\Uccello\Models\Module;

class AddDefaultData extends Migration
{   
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addDefaultUser();
        $this->addDefaultDomain();
        $this->addDefaultRole();
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

    protected function addDefaultUser()
    {
        $user = new User();
        $user->username = 'admin';
        $user->first_name = null;
        $user->last_name = 'Admin';
        $user->email = 'admin@uccello.io';
        $user->password = Hash::make( 'admin');
        $user->is_admin = true;
        $user->save();
    }

    protected function addDefaultDomain()
    {
        $domain = new Domain();
        $domain->name = 'Default';
        $domain->description = null;
        $domain->parent_id = null;
        $domain->save();
    }

    protected function addDefaultRole()
    {
        $role = new Role();
        $role->name = 'Administrator';
        $role->description = null;
        $role->parent_id = null;
        $role->domain_id = Domain::first()->id;
        $role->save();
    }
}
