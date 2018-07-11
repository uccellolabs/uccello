<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('module_id')->comment('Decrease queries');
            $table->unsignedInteger('tab_id');
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('sequence');
            $table->timestamps();

            // Foreign keys
            $table->foreign('module_id')
                ->references('id')->on($this->tablePrefix . 'modules')
                ->onDelete('cascade');

            $table->foreign('tab_id')
                    ->references('id')->on($this->tablePrefix . 'tabs')
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
        Schema::dropIfExists($this->tablePrefix . 'blocks');
    }
}
