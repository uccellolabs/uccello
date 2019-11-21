<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class AlterGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tablePrefix.'groups', function(Blueprint $table) {
            $table->unsignedInteger('domain_id')->nullable()->after('description');

            // Foreign keys
            $table->foreign('domain_id')->references('id')->on($this->tablePrefix.'domains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablePrefix.'groups', function(Blueprint $table) {
            $table->dropForeign($this->tablePrefix.'groups_domain_id_foreign');

            $table->dropColumn('domain_id');
        });
    }
}
