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
        Schema::create($this->tablePrefix.'displaytypes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('class');
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
        Schema::dropIfExists($this->tablePrefix.'displaytypes');
    }

    protected function addDisplaytypes()
    {
        $displaytypes = [
            'everywhere'    => 'Uccello\Core\Fields\Displaytype\Everywhere',
            'create'        => 'Uccello\Core\Fields\Displaytype\Create',
            'detail'        => 'Uccello\Core\Fields\Displaytype\Detail',
            'edit_detail'   => 'Uccello\Core\Fields\Displaytype\EditDetail',
            'hidden'        => 'Uccello\Core\Fields\Displaytype\Hidden',
        ];

        foreach ($displaytypes as $name => $class) {
            $displaytype = new Displaytype();
            $displaytype->name = $name;
            $displaytype->class = $class;
            $displaytype->save();
        }
    }
}
