<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;

class CreateConnectionsTable extends Migration
{
    use TablePrefixTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'connections', function(Blueprint $table) {
            $table->increments('id');

            // Compatibility with Laravel < 5.8
            if (DB::getSchemaBuilder()->getColumnType('users', 'id') === 'bigint') { // Laravel >= 5.8
                $table->unsignedBigInteger('user_id')->nullable();
            } else { // Laravel < 5.8
                $table->unsignedInteger('user_id')->nullable();
            }

            $table->timestamp('datetime');

            // Foreign keys
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'connections');
    }
}
