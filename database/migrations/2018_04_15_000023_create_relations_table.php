<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'relations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('relatedlist_id');
            $table->unsignedInteger('module_id');
            $table->unsignedInteger('record_id');
            $table->unsignedInteger('related_module_id');
            $table->unsignedInteger('related_record_id');
            $table->text('data')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('module_id')
                    ->references('id')->on($this->tablePrefix . 'modules')
                    ->onDelete('cascade');

            $table->foreign('related_module_id')
                    ->references('id')->on($this->tablePrefix . 'modules')
                    ->onDelete('cascade');

            $table->foreign('relatedlist_id')
                    ->references('id')->on($this->tablePrefix . 'relatedlists')
                    ->onDelete('cascade');

            // Unique keys
            $table->unique(['module_id', 'record_id', 'related_record_id', 'related_module_id', 'relatedlist_id'], 'relations_unique_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix . 'relations');
    }
}
