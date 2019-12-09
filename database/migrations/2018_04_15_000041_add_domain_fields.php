<?php

use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class AddDomainFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modules = [ 'user', 'group', 'role', 'profile' ];

        foreach ($modules as $moduleName) {
            $module = Module::where('name', $moduleName)->first();
            $block = $module->blocks->where('label', 'block.general')->first();

            // Field domain
            Field::create([
                'module_id' => $module->id,
                'block_id' => $block->id,
                'name' => 'domain',
                'uitype_id' => uitype('entity')->id,
                'displaytype_id' => displaytype('list_only')->id,
                'sequence' => $block->fields->count(),
                'data' => json_decode('{"module":"domain"}')
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $modules = [ 'user', 'group', 'role', 'profile' ];

        foreach ($modules as $moduleName) {
            $module = Module::where('name', $moduleName)->first();

            $module->fields()
                ->where('module_id', $module->id)
                ->where('name', 'domain')
                ->delete();
        }
    }
}
