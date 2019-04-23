<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->string('username')->after('id');
            $table->boolean('is_admin')->after('remember_token')->default(false);
            $table->unsignedInteger('domain_id')->after('is_admin');
            $table->unsignedInteger('last_domain_id')->after('domain_id')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->renameColumn('username', 'name');
            $table->removeColumn('first_name');
            $table->removeColumn('last_name');
            $table->removeColumn('is_admin');
            $table->removeColumn('domain_id');
            $table->removeColumn('last_domain_id');
        });
    }
}
