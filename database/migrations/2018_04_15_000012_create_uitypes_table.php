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
            'text'          => \Uccello\Core\Fields\Uitype\Text::class,
            'textarea'      => \Uccello\Core\Fields\Uitype\Textarea::class,
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
            'search'        => \Uccello\Core\Fields\Uitype\Search::class,
            'choice'        => \Uccello\Core\Fields\Uitype\Choice::class,
            'select'        => \Uccello\Core\Fields\Uitype\Select::class,
            'url'           => \Uccello\Core\Fields\Uitype\Url::class,
            'boolean'       => \Uccello\Core\Fields\Uitype\Boolean::class,
            'checkbox'      => \Uccello\Core\Fields\Uitype\Checkbox::class,
            'password'      => \Uccello\Core\Fields\Uitype\Password::class,
            'month'         => \Uccello\Core\Fields\Uitype\Month::class,
            'week'          => \Uccello\Core\Fields\Uitype\Week::class,
            'file'          => \Uccello\Core\Fields\Uitype\File::class,
            'image'         => \Uccello\Core\Fields\Uitype\Image::class,
            'assigned_user' => \Uccello\Core\Fields\Uitype\AssignedUser::class,
            'module_list'   => \Uccello\Core\Fields\Uitype\ModuleList::class,
        ];

        foreach ($uitypes as $name => $class) {
            $uitype = new Uitype();
            $uitype->name = $name;
            $uitype->class = $class;
            $uitype->save();
        }
    }
}
