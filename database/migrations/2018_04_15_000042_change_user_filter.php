<?php

use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Module;

class ChangeUserFilter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = Module::where('name', 'user')->first();

        $filter = $module->filters->where('name', 'filter.all')->first();
        $filter->columns = [ 'username', 'name', 'email', 'is_admin' ];
        $filter->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module = Module::where('name', 'user')->first();

        $filter = $module->filters->where('name', 'filter.all')->first();
        $filter->columns = [ 'username', 'name', 'email' ];
        $filter->save();
    }
}
