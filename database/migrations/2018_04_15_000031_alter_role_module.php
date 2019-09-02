<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;
use Uccello\Core\Models\Module;

class AlterRoleModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tablePrefix.'roles', function(Blueprint $table) {
            $table->boolean('see_descendants_records')->after('description')->default(false);
        });

        $roleModule = Module::where('name', 'role')->first();

        $descriptionField = Field::where('module_id', $roleModule->id)->where('name', 'description')->first();
        $descriptionField->data = null;
        $descriptionField->save();

        Field::create([
            'module_id' => $roleModule->id,
            'block_id' => $descriptionField->block_id,
            'name' => 'see_descendants_records',
            'uitype_id' => uitype('boolean')->id,
            'displaytype_id' => displaytype('everywhere')->id,
            'sequence' => 3,
            'data' => [ 'info' => 'field_info.see_descendants_records']
        ]);

        $filter = Filter::where('module_id', $roleModule->id)->where('name', 'filter.all')->first();
        $filter->columns = [ "name", "parent", "description", "see_descendants_records" ];
        $filter->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablePrefix.'roles', function(Blueprint $table) {
            $table->dropColumn('see_descendants_records');
        });

        $roleModule = Module::where('name', 'role')->first();

        $descriptionField = Field::where('module_id', $roleModule->id)->where('name', 'description')->first();
        $descriptionField->data = [ 'large' => true ];
        $descriptionField->save();

        Field::where('module_id', $roleModule->id)
            ->where('name', 'see_descendants_records')
            ->delete();

        $filter = Filter::where('module_id', $roleModule->id)->where('name', 'filter.all')->first();
        $filter->columns = [ "name", "description", "parent" ];
        $filter->save();
    }
}
