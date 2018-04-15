<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Sardoj\Uccello\Database\Migrations\Migration;

class CreateTabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'tabs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sequence');
            $table->unsignedInteger('module_id');
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
        Schema::dropIfExists($this->tablePrefix . 'tabs');
    }
}
