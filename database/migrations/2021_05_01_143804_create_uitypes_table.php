<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        Schema::create(config('uccello.database.table_prefix').'uitypes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('class');
            $table->json('data')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists(config('uccello.database.table_prefix').'uitypes');
    }

    /**
     * Add uitypes into database.
     *
     * @return void
     */
    protected function addUitypes()
    {
        $uitypes = [
            'string'        => \Uccello\Core\Fields\Uitype\TextString::class,
            'text'          => \Uccello\Core\Fields\Uitype\Text::class,
            'hidden'        => \Uccello\Core\Fields\Uitype\Hidden::class,
            'time'          => \Uccello\Core\Fields\Uitype\Time::class,
            'date'          => \Uccello\Core\Fields\Uitype\Date::class,
            'datetime'      => \Uccello\Core\Fields\Uitype\DateTime::class,
            'integer'       => \Uccello\Core\Fields\Uitype\Integer::class,
            'number'        => \Uccello\Core\Fields\Uitype\Number::class,
            'range'         => \Uccello\Core\Fields\Uitype\Range::class,
            'entity'        => \Uccello\Core\Fields\Uitype\Entity::class,
            'color'         => \Uccello\Core\Fields\Uitype\Color::class,
            'phone'         => \Uccello\Core\Fields\Uitype\Phone::class,
            'email'         => \Uccello\Core\Fields\Uitype\Email::class,
            'select'        => \Uccello\Core\Fields\Uitype\Select::class,
            'url'           => \Uccello\Core\Fields\Uitype\Url::class,
            'boolean'       => \Uccello\Core\Fields\Uitype\Boolean::class,
            'checkbox'      => \Uccello\Core\Fields\Uitype\Checkbox::class,
            'password'      => \Uccello\Core\Fields\Uitype\Password::class,
            'month'         => \Uccello\Core\Fields\Uitype\Month::class,
            'week'          => \Uccello\Core\Fields\Uitype\Week::class,
            'file'          => \Uccello\Core\Fields\Uitype\File::class,
            'image'         => \Uccello\Core\Fields\Uitype\Image::class,
            'assigned_to'   => \Uccello\Core\Fields\Uitype\AssignedTo::class,
            'module_list'   => \Uccello\Core\Fields\Uitype\ModuleList::class,
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
}
