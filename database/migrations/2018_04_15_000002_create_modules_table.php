<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('model_class')->nullable();
            $table->boolean('is_for_admin')->default(false);
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
        Schema::dropIfExists($this->tablePrefix . 'modules');
    }
}
