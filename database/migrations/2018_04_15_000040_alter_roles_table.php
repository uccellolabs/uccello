<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class AlterRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (
            !Schema::hasColumn($this->tablePrefix.'roles', 'path')
            && !Schema::hasColumn($this->tablePrefix.'roles', 'level')
        ) {
            Schema::table($this->tablePrefix.'roles', function(Blueprint $table) {
                $table->string('path')->nullable()->after('parent_id');
                $table->integer('level')->default(0)->after('path');

                // Index
                $table->index(array('path', 'parent_id', 'level'));
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablePrefix.'roles', function(Blueprint $table) {
            $table->dropIndex($this->tablePrefix.'roles_path_parent_id_level_index');

            $table->dropColumn('path');
            $table->dropColumn('level');
        });
    }
}
