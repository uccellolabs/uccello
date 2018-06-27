<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Models\Module;

class CreateHomeStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = $this->createModule();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Module::where('name', 'home')->forceDelete();
    }

    protected function createModule()
    {
        $module = new  Module();
        $module->name = 'home';
        $module->icon = 'home';
        $module->entity_class = null;
        $module->is_for_admin = false;
        $module->save();

        return $module;
    }
}
