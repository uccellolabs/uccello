<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Displaytype;

class CreateDisplaytypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix . 'displaytypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('model_class');
            $table->timestamps();
        });

        $this->addDisplaytypes();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix . 'displaytypes');
    }

    protected function addDisplaytypes()
    {
        $displaytypes = [
            'everywhere'    => 'Uccello\Core\Models\Displaytypes\Everywhere',
            'create'        => 'Uccello\Core\Models\Displaytypes\Create',
            'detail'        => 'Uccello\Core\Models\Displaytypes\Detail',
            'edit_detail'   => 'Uccello\Core\Models\Displaytypes\EditDetail',
            'hidden'        => 'Uccello\Core\Models\Displaytypes\Hidden',
        ];

        foreach ($displaytypes as $name => $modelClass) {
            $displaytype = new Displaytype();
            $displaytype->name = $name;
            $displaytype->model_class = $modelClass;
            $displaytype->save();
        }
    }
}
