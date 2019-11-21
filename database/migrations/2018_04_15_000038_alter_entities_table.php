<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class AlterEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tablePrefix.'entities', function(Blueprint $table) {
            $table->unsignedInteger('creator_id')->nullable()->after('record_id');

            // Foreign keys
            $table->foreign('creator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablePrefix.'entities', function(Blueprint $table) {
            $table->dropForeign($this->tablePrefix.'entities_creator_id_foreign');

            $table->dropColumn('creator_id');
        });
    }
}
