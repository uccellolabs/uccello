<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Models\Uitype;

class CreateUitypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'uitypes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('class');
            $table->timestamps();

            // Unique keys
            $table->unique([ 'name' ]);
        });

        $this->addUitypes();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'uitypes');
    }

    /**
     * Add uitypes into database.
     *
     * @return void
     */
    protected function addUitypes()
    {
        $uitypes = [
            'text'      => 'Uccello\Core\Fields\Uitype\Text',
            'textarea'  => 'Uccello\Core\Fields\Uitype\Textarea',
            'hidden'    => 'Uccello\Core\Fields\Uitype\Hidden',
            'time'      => 'Uccello\Core\Fields\Uitype\Time',
            'date'      => 'Uccello\Core\Fields\Uitype\Date',
            'datetime'  => 'Uccello\Core\Fields\Uitype\DateTime',
            'integer'   => 'Uccello\Core\Fields\Uitype\Integer',
            'number'    => 'Uccello\Core\Fields\Uitype\Number',
            'range'     => 'Uccello\Core\Fields\Uitype\Range',
            'entity'    => 'Uccello\Core\Fields\Uitype\Entity',
            'color'     => 'Uccello\Core\Fields\Uitype\Color',
            'phone'     => 'Uccello\Core\Fields\Uitype\Phone',
            'email'     => 'Uccello\Core\Fields\Uitype\Email',
            'search'    => 'Uccello\Core\Fields\Uitype\Search',
            'choice'    => 'Uccello\Core\Fields\Uitype\Choice',
            'select'    => 'Uccello\Core\Fields\Uitype\Select',
            'url'       => 'Uccello\Core\Fields\Uitype\Url',
            'boolean'   => 'Uccello\Core\Fields\Uitype\Boolean',
            'checkbox'  => 'Uccello\Core\Fields\Uitype\Checkbox',
            'password'  => 'Uccello\Core\Fields\Uitype\Password',
            'month'     => 'Uccello\Core\Fields\Uitype\Month',
            'week'      => 'Uccello\Core\Fields\Uitype\Week',
            'file'      => 'Uccello\Core\Fields\Uitype\File',
            'image'     => 'Uccello\Core\Fields\Uitype\Image',
        ];

        foreach ($uitypes as $name => $class) {
            $uitype = new Uitype();
            $uitype->name = $name;
            $uitype->class = $class;
            $uitype->save();
        }
    }
}
