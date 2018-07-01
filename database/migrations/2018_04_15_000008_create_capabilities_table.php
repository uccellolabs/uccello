<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Capability;

class CreateCapabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'capabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $this->addCapabilities();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix . 'capabilities');
    }

    protected function addCapabilities()
    {
        $capabilities = [
            'retrieve',
            'create',
            'update',
            'delete',
            'api-retrieve',
            'api-create',
            'api-update',
            'api-delete',
            'admin',
        ];

        foreach ($capabilities as $name) {
            $capability = new Capability();
            $capability->name = $name;
            $capability->save();
        }
    }
}
