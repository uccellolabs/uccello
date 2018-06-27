<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;

class CreateTabsTable extends Migration
{
    use TablePrefixTrait;

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

            // Foreign keys
            $table->foreign('module_id')
                    ->references('id')->on($this->tablePrefix . 'modules')
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
        Schema::dropIfExists($this->tablePrefix . 'tabs');
    }
}
