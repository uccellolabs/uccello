<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Uccello\Core\Database\Migrations\Migration;

class CreatePrivilegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'privileges', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('domain_id');
            $table->unsignedInteger('role_id');

            // Compatibility with Laravel < 5.8
            if (DB::getSchemaBuilder()->getColumnType('users', 'id') === 'bigint') { // Laravel >= 5.8
                $table->unsignedBigInteger('user_id')->nullable();
            } else { // Laravel < 5.8
                $table->unsignedInteger('user_id')->nullable();
            }

            $table->timestamps();

            // Foreign keys
            $table->foreign('domain_id')
                    ->references('id')->on($this->tablePrefix.'domains')
                    ->onDelete('cascade');

            $table->foreign('role_id')
                    ->references('id')->on($this->tablePrefix.'roles')
                    ->onDelete('cascade');

            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');

            // Unique keys
            $table->unique([ 'domain_id', 'role_id', 'user_id' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'privileges');
    }
}
