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
            'everywhere'    => \Uccello\Core\Fields\Displaytype\Everywhere::class,
            'create'        => \Uccello\Core\Fields\Displaytype\Create::class,
            'detail'        => \Uccello\Core\Fields\Displaytype\Detail::class,
            'edit_detail'   => \Uccello\Core\Fields\Displaytype\EditDetail::class,
            'hidden'        => \Uccello\Core\Fields\Displaytype\Hidden::class,
            'create_edit'   => \Uccello\Core\Fields\Displaytype\CreateEdit::class,
            'create_detail' => \Uccello\Core\Fields\Displaytype\CreateDetail::class,
            'list_only'     => \Uccello\Core\Fields\Displaytype\ListOnly::class,
        ];

        foreach ($displaytypes as $name => $class) {
            $displaytype = new Displaytype();
            $displaytype->name = $name;
            $displaytype->class = $class;
            $displaytype->save();
        }
    }
}
