<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Models\Widget;

class RenameRelatedlistWidgetClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $widgets = Widget::where('class', 'Uccello\Core\Widgets\Relatedlist')->get();

        foreach ($widgets as $widget) {
            $widget->class = 'Uccello\Core\Widgets\RelatedlistWidget';
            $widget->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $widgets = Widget::where('class', 'Uccello\Core\Widgets\RelatedlistWidget')->get();

        foreach ($widgets as $widget) {
            $widget->class = 'Uccello\Core\Widgets\Relatedlist';
            $widget->save();
        }
    }
}
