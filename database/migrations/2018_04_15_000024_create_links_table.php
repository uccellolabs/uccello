<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;

class CreateLinksTable extends Migration
{
    use TablePrefixTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'links', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('module_id');
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('type');
            $table->string('url')->nullable();
            $table->unsignedInteger('sequence');
            $table->text('data')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('module_id')
                    ->references('id')->on($this->tablePrefix.'modules')
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
        Schema::dropIfExists($this->tablePrefix.'links');
    }
}
