<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Uitype;

class AddUitypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $uitypes = [
            'currency'      => \Uccello\Core\Fields\Uitype\Currency::class,
            'percent'       => \Uccello\Core\Fields\Uitype\Percent::class,
            'auto_number'   => \Uccello\Core\Fields\Uitype\AutoNumber::class,
        ];

        foreach ($uitypes as $name => $class) {
            $uitype = new Uitype();
            $uitype->name = $name;
            $uitype->class = $class;
            $uitype->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $uitypes = [
            'currency',
            'percent',
            'auto_number',
        ];

        foreach ($uitypes as $name) {
            Uitype::where('name', $name)->delete();
        }
    }
}
