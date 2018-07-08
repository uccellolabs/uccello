<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('data')->nullable();
            $table->unsignedInteger('uitype_id');
            $table->unsignedInteger('displaytype_id');
            $table->unsignedInteger('block_id');
            $table->unsignedInteger('sequence');
            $table->timestamps();

            // Foreign keys
            $table->foreign('block_id')
                    ->references('id')->on($this->tablePrefix . 'blocks')
                    ->onDelete('cascade');

            $table->foreign('uitype_id')
                    ->references('id')->on($this->tablePrefix . 'uitypes');

            $table->foreign('displaytype_id')
                    ->references('id')->on($this->tablePrefix . 'displaytypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix . 'fields');
    }
}
