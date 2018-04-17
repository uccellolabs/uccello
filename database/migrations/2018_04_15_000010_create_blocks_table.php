<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Sardoj\Uccello\Database\Migrations\Migration;

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
