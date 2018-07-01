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
        Schema::create($this->tablePrefix . 'uitypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('model_class');
            $table->timestamps();

            // Unique keys
            $table->unique(['name']);
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
        Schema::dropIfExists($this->tablePrefix . 'uitypes');
    }

    /**
     * Add uitypes into database.
     *
     * @return void
     */
    protected function addUitypes()
    {
        $uitypes = [
            'text'      => 'Uccello\Core\Models\Uitypes\Text',
            'textarea'  => 'Uccello\Core\Models\Uitypes\Textarea',
            'hidden'    => 'Uccello\Core\Models\Uitypes\Hidden',
            'time'      => 'Uccello\Core\Models\Uitypes\Time',
            'date'      => 'Uccello\Core\Models\Uitypes\Date',
            'datetime'  => 'Uccello\Core\Models\Uitypes\DateTime',
            'integer'   => 'Uccello\Core\Models\Uitypes\Integer',
            'number'    => 'Uccello\Core\Models\Uitypes\Number',
            'range'     => 'Uccello\Core\Models\Uitypes\Range',
            'entity'    => 'Uccello\Core\Models\Uitypes\Entity',
            'color'     => 'Uccello\Core\Models\Uitypes\Color',
            'phone'     => 'Uccello\Core\Models\Uitypes\Phone',
            'email'     => 'Uccello\Core\Models\Uitypes\Email',
            'search'    => 'Uccello\Core\Models\Uitypes\Search',
            'choice'    => 'Uccello\Core\Models\Uitypes\Choice',
            'select'    => 'Uccello\Core\Models\Uitypes\Select',
            'url'       => 'Uccello\Core\Models\Uitypes\Url',
            'boolean'   => 'Uccello\Core\Models\Uitypes\Boolean',
            'checkbox'  => 'Uccello\Core\Models\Uitypes\Checkbox',
            'password'  => 'Uccello\Core\Models\Uitypes\Password',
            'month'     => 'Uccello\Core\Models\Uitypes\Month',
            'week'      => 'Uccello\Core\Models\Uitypes\Week',
            'file'      => 'Uccello\Core\Models\Uitypes\File',
            'image'     => 'Uccello\Core\Models\Uitypes\Image',
        ];

        foreach ($uitypes as $name => $modelClass) {
            $uitype = new Uitype();
            $uitype->name = $name;
            $uitype->model_class = $modelClass;
            $uitype->save();
        }
    }
}
