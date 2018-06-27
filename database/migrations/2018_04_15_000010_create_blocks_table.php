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
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('sequence');
            $table->unsignedInteger('tab_id');
            $table->timestamps();

            // Foreign keys
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
