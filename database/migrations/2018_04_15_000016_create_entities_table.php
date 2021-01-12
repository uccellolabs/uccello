<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Uccello\Core\Database\Migrations\Migration;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'entities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('module_id');
            $table->unsignedInteger('record_id');

            // Compatibility with Laravel < 5.8
            if (DB::getSchemaBuilder()->getColumnType('users', 'id') === 'bigint') { // Laravel >= 5.8
                $table->unsignedBigInteger('creator_id')->nullable();
            } else { // Laravel < 5.8
                $table->unsignedInteger('creator_id')->nullable();
            }

            $table->timestamps();

            $table->index('module_id', 'record_id');

            // Foreign keys
            $table->foreign('module_id')
                ->references('id')->on($this->tablePrefix.'modules')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')->on('users');

            // Index
            $table->index(array('module_id', 'creator_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'entities');
    }
}
