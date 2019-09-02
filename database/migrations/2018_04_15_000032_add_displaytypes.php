<?php

use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Displaytype;

class AddDisplaytypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $displaytypes = [
            'create_detail'    => 'Uccello\Core\Fields\Displaytype\CreateDetail',
            'list_only'        => 'Uccello\Core\Fields\Displaytype\ListOnly',
        ];

        foreach ($displaytypes as $name => $class) {
            $displaytype = new Displaytype();
            $displaytype->name = $name;
            $displaytype->class = $class;
            $displaytype->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Displaytype::where('name', 'create_detail')
            ->orWhere('name', 'list_only')
            ->delete();
    }
}
