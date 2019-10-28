<?php

use Illuminate\Support\Facades\Schema;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Relatedlist;

class AddUsersRolesRl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userModule = Module::where('name', 'user')->first();
        $groupModule = Module::where('name', 'group')->first();

        Relatedlist::create([
            'module_id' => $userModule->id,
            'related_module_id' => $groupModule->id,
            'tab_id' => null,
            'label' => 'relatedlist.groups',
            'type' => 'n-n',
            'method' => 'getRelatedList',
            'sequence' => 1,
            'data' => [ 'actions' => [ 'add', 'select' ] ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $userModule = Module::where('name', 'user')->first();
        $groupModule = Module::where('name', 'group')->first();

        Relatedlist::where('module_id', $userModule->id)
            ->where('related_module_id', $groupModule->id)
            ->where('label', 'relatedlist.groups')
            ->delete();
    }
}
