<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'roles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('see_descendants_records')->default(false);
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('path')->nullable();
            $table->integer('level')->default(0);
            $table->unsignedInteger('domain_id');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('parent_id')
                    ->references('id')->on($this->tablePrefix.'roles');

            $table->foreign('domain_id')
                    ->references('id')->on($this->tablePrefix.'domains')
                    ->onDelete('cascade');

            // Index
            $table->index(array('path', 'parent_id', 'level'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'roles');
    }
}
