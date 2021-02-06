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
        Schema::create($this->tablePrefix.'capabilities', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('data')->nullable();
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
        Schema::dropIfExists($this->tablePrefix.'capabilities');
    }

    protected function addCapabilities()
    {
        $capabilities = [
            'retrieve' => false,
            'create' => true, // For CRUD
            'update' => true, // For CRUD
            'delete' => true, // For CRUD
            'admin' => false,
        ];

        foreach ($capabilities as $name => $forCrud) {
            $data = [
                'package' => 'uccello/uccello'
            ];

            if ($forCrud === true) {
                $data["for_crud"] = true;
            }

            Capability::create([
                'name' => $name,
                'data' => $data
            ]);
        }
    }
}
