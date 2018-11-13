<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class CreateRelatedlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'relatedlists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('module_id');
            $table->unsignedInteger('related_module_id');
            $table->unsignedInteger('related_field_id')->nullable();
            $table->string('label');
            $table->string('icon')->nullable();
            $table->enum('type', ['n-1', 'n-n']);
            $table->string('method');
            $table->unsignedInteger('sequence');
            $table->text('data')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('module_id')
                    ->references('id')->on($this->tablePrefix . 'modules')
                    ->onDelete('cascade');

            $table->foreign('related_module_id')
                    ->references('id')->on($this->tablePrefix . 'modules')
                    ->onDelete('cascade');

            $table->foreign('related_field_id')
            ->references('id')->on($this->tablePrefix . 'fields')
                    ->onDelete('cascade');

            // Unique keys
            $table->unique(['module_id', 'related_module_id', 'label']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix . 'relatedlists');
    }
}
