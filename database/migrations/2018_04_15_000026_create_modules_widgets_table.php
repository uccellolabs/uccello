<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;

class CreateModulesWidgetsTable extends Migration
{
    use TablePrefixTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'modules_widgets', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('domain_id')->nullable();
            $table->unsignedInteger('module_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('widget_id');
            $table->unsignedInteger('sequence');
            $table->text('data')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('domain_id')
                    ->references('id')->on($this->tablePrefix.'domains')
                    ->onDelete('cascade');

            $table->foreign('module_id')
                    ->references('id')->on($this->tablePrefix.'modules')
                    ->onDelete('cascade');

            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');

            $table->foreign('widget_id')
                    ->references('id')->on($this->tablePrefix.'widgets')
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
        Schema::dropIfExists($this->tablePrefix.'modules_widgets');
    }
}
