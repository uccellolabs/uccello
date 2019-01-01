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
        Schema::create($this->tablePrefix.'fields', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('module_id')->comment('Decrease queries');
            $table->unsignedInteger('block_id');
            $table->unsignedInteger('uitype_id');
            $table->unsignedInteger('displaytype_id');
            $table->string('name');
            $table->unsignedInteger('sequence');
            $table->text('data')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('module_id')
                ->references('id')->on($this->tablePrefix.'modules')
                ->onDelete('cascade');

            $table->foreign('block_id')
                    ->references('id')->on($this->tablePrefix.'blocks')
                    ->onDelete('cascade');

            $table->foreign('uitype_id')
                    ->references('id')->on($this->tablePrefix.'uitypes');

            $table->foreign('displaytype_id')
                    ->references('id')->on($this->tablePrefix.'displaytypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'fields');
    }
}
