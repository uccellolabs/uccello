<?php

use Illuminate\Support\Facades\Schema;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;

class CreateSettingsModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = $this->createModule();
        $this->activateModuleOnDomains($module);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop table
        Schema::dropIfExists($this->tablePrefix . 'settings');

        // Delete module
        Module::where('name', 'settings')->forceDelete();
    }

    protected function createModule()
    {
        $module = new Module([
            'name' => 'settings',
            'icon' => 'settings',
            'model_class' => null,
            'data' => ['package' => 'uccello/uccello', 'admin' => true, 'route' => 'uccello.index', 'mandatory' => true]
        ]);
        $module->save();
        return $module;
    }

    protected function activateModuleOnDomains($module)
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $domain->modules()->attach($module);
        }
    }
}