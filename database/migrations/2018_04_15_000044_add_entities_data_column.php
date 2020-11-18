<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class AddEntitiesDataColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tablePrefix.'entities', function(Blueprint $table) {
            $table->text('data')->nullable()->after('creator_id');
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
            $table->dropColumn('data');
        });
    }
}
