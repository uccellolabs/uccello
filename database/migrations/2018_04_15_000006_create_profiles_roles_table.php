<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class CreateProfilesRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'profiles_roles', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('role_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('profile_id')
                    ->references('id')->on($this->tablePrefix.'profiles')
                    ->onDelete('cascade');

            $table->foreign('role_id')
                    ->references('id')->on($this->tablePrefix.'roles')
                    ->onDelete('cascade');

            // Unique keys
            $table->unique([ 'profile_id', 'role_id' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'profiles_roles');
    }
}
