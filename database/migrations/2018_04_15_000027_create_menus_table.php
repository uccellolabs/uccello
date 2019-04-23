<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;
use Uccello\Core\Models\Menu;

class CreateMenusTable extends Migration
{
    use TablePrefixTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablePrefix.'menus', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('domain_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('type');
            $table->text('data');
            $table->timestamps();

            // Foreign keys
            $table->foreign('domain_id')
                ->references('id')->on($this->tablePrefix.'domains')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        // Add default admin menu
        // TODO: Translate
        Menu::create([
            'type' => 'admin',
            'data' => json_decode('[
                {
                    "color":"grey",
                    "nochildren":false,
                    "icon":"settings",
                    "translation":"Dashboard",
                    "label":"menu.dashboard",
                    "type":"module",
                    "route":"uccello.settings.dashboard",
                    "module":"settings",
                    "id":1
                },
                {
                    "color":"green",
                    "nochildren":false,
                    "icon":"lock",
                    "label":"menu.security",
                    "type":"group",
                    "id":2,
                    "children":[
                        {
                            "color":"grey",
                            "nochildren":false,
                            "icon":"person",
                            "translation":"Users",
                            "label":"user",
                            "type":"module",
                            "route":"uccello.list",
                            "module":"user",
                            "id":3
                        },
                        {
                            "color":"grey",
                            "nochildren":false,
                            "icon":"domain",
                            "translation":"Domains",
                            "label":"domain",
                            "type":"module",
                            "route":"uccello.list",
                            "module":"domain",
                            "id":4
                        },
                        {
                            "color":"grey",
                            "nochildren":false,
                            "icon":"lock",
                            "translation":"Roles",
                            "label":"role",
                            "type":"module",
                            "route":"uccello.list",
                            "module":"role",
                            "id":5
                        },
                        {
                            "color":"grey",
                            "nochildren":false,
                            "icon":"lock",
                            "translation":"Profiles",
                            "label":"profile",
                            "type":"module",
                            "route":"uccello.list",
                            "module":"profile",
                            "id":6
                        }
                    ]
                },
                {
                    "color":"green",
                    "nochildren":false,
                    "icon":"settings",
                    "label":"menu.settings",
                    "type":"group",
                    "id":7,
                    "children":[
                        {
                            "color":"grey",
                            "nochildren":false,
                            "icon":"extension",
                            "translation":"Module manager",
                            "label":"module_manager.link",
                            "type":"module",
                            "route":"uccello.settings.module.manager",
                            "module":"settings",
                            "id":8
                        },
                        {
                            "color":"grey",
                            "nochildren":false,
                            "icon":"menu",
                            "translation":"Menu manager",
                            "label":"menu_manager.link",
                            "type":"module",
                            "route":"uccello.settings.menu.manager",
                            "module":"settings",
                            "id":9
                        }
                    ]
                }
             ]')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablePrefix.'menus');
    }
}
