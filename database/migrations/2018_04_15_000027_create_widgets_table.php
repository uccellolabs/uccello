<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;
use Uccello\Core\Models\Widget;

class CreateWidgetsTable extends Migration
{
    use TablePrefixTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'widgets', function(Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->string('type');
            $table->string('class');
            $table->text('data')->nullable();
            $table->timestamps();
        });

        // Add summary field widget
        Widget::create([
            'label' => 'widget.main_fields',
            'type' => 'summary',
            'class' => 'Uccello\Core\Widgets\SummaryFields',
            'data' => [ 'package' => 'uccello/uccello' ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'widgets');
    }
}
