<?php

use Illuminate\Support\Facades\Schema;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Uitype;

class AddModuleListUitype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $uitype = new Uitype();
        $uitype->name = 'module_list';
        $uitype->class = 'Uccello\Core\Fields\Uitype\ModuleList';
        $uitype->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Uitype::where('name', 'module_list')->delete();
    }
}
