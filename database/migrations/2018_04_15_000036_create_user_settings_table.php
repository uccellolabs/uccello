<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;

class CreateUserSettingsTable extends Migration
{
    use TablePrefixTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'user_settings', function (Blueprint $table) {
            // Compatibility with Laravel < 5.8
            if (DB::getSchemaBuilder()->getColumnType('users', 'id') === 'bigint') { // Laravel >= 5.8
                $table->unsignedBigInteger('user_id')->primary();
            } else { // Laravel < 5.8
                $table->unsignedInteger('user_id')->primary();
            }

            $table->longText('data');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users') ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix . 'user_settings');
    }
}
