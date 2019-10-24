<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;
use Uccello\Core\Models\Menu;
use Uccello\Core\Models\Relatedlist;
use Uccello\Core\Models\Link;

// TODO...

class CreateGroupModule extends Migration
{
    use TablePrefixTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = $this->createModule();
        $this->createTables();
        $this->activateModuleOnDomains($module);
        $this->updateMenu();
        $this->createTabsBlocksFields($module);
        $this->createFilters($module);
        $this->createRelatedLists($module);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop table
        Schema::dropIfExists($this->tablePrefix . 'uccello_groups');
        Schema::dropIfExists($this->tablePrefix . 'uccello_rl_groups_groups');
        Schema::dropIfExists($this->tablePrefix . 'uccello_rl_groups_users');

        // Delete module
        Module::where('name', 'group')->forceDelete();
    }

    protected function createTables()
    {
        // Module Table
        Schema::create($this->tablePrefix . 'uccello_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('domain_id')->references('id')->on('uccello_domains');
        });

        // Related List Table: Groups-Groups
        Schema::create($this->tablePrefix . 'uccello_rl_groups_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id');
            $table->unsignedInteger('children_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('parent_id')
                ->references('id')->on($this->tablePrefix . 'uccello_groups')
                ->onDelete('cascade');

            $table->foreign('children_id')
                ->references('id')->on($this->tablePrefix . 'uccello_groups')
                ->onDelete('cascade');

            // Unique keys
            $table->unique(['parent_id', 'children_id']);
        });

        // Related List Table: Groups-Users
        Schema::create($this->tablePrefix . 'uccello_rl_groups_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('group_id')
                ->references('id')->on($this->tablePrefix . 'uccello_groups')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on($this->tablePrefix . 'users')
                ->onDelete('cascade');

            // Unique keys
            $table->unique(['group_id', 'user_id']);
        });
    }

    protected function createModule()
    {
        $module = new Module();
        $module->name = 'group';
        $module->icon = 'group';
        $module->model_class = 'Uccello\Core\Models\Group';
        $module->data = [ "package" => "uccello/uccello", "admin" => true, "mandatory" => true ];
        $module->save();

        return $module;
    }

    protected function createTabsBlocksFields($module)
    {
        // Main tab
        $tab = new Tab();
        $tab->label = 'tab.main';
        $tab->icon = null;
        $tab->sequence = 0;
        $tab->module_id = $module->id;
        $tab->save();

        // General block
        $block = new Block();
        $block->label = 'block.general';
        $block->icon = 'group';
        $block->sequence = 0;
        $block->tab_id = $tab->id;
        $block->module_id = $module->id;
        $block->save();

        // Name
        $field = new Field();
        $field->name = 'name';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = [ 'rules' => 'required' ];
        $field->sequence = 0;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Description
        $field = new Field();
        $field->name = 'description';
        $field->uitype_id = uitype('textarea')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = null;
        $field->sequence = 1;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();
    }

    protected function createFilters($module)
    {
        $filter = new Filter();
        $filter->module_id = $module->id;
        $filter->domain_id = null;
        $filter->user_id = null;
        $filter->name = 'filter.all';
        $filter->type = 'list';
        $filter->columns = [ 'name', 'description'];
        $filter->conditions = null;
        $filter->order = null;
        $filter->is_default = true;
        $filter->is_public = false;
        $filter->data = [ 'readonly' => true ];
        $filter->save();
    }

    protected function activateModuleOnDomains($module)
    {
        $domains = Domain::all();
        foreach ($domains as $domain) {
            $domain->modules()->attach($module);
        }
    }

    protected function createRelatedLists($module)
    {
        // Related List groups
        //$relatedModule = Module::where('name', $this->tablePrefix . 'group')->first();

        Relatedlist::create([
            'module_id' => $module->id,
            'related_module_id' => $module->id,
            'tab_id' => null,
            'related_field_id' => null,
            'label' => 'relatedlist.childrenGroups',
            'type' => 'n-n',
            'method' => 'getRelatedList',
            'data' => [
                "relationName" => "childrenGroups",
                "actions" => ["select", "add"]
            ],
            'sequence' => 0
        ]);


        // Related List users
        $relatedModule = Module::where('name', 'user')->first();

        Relatedlist::create([
            'module_id' => $module->id,
            'related_module_id' => $relatedModule->id,
            'tab_id' => null,
            'related_field_id' => null,
            'label' => 'relatedlist.users',
            'type' => 'n-n',
            'method' => 'getRelatedList',
            'data' => ["actions" => ["select", "add"]],
            'sequence' => 1
        ]);
    }

    protected function updateMenu()
    {
        // Add default admin menu
        // TODO: Translate
        $menu = Menu::find(1);
        $menu->data = json_decode('[
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
                        "icon":"group",
                        "translation":"Group",
                        "label":"group",
                        "type":"module",
                        "route":"uccello.list",
                        "module":"group",
                        "id":4
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
                        "id":5
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
                        "id":6
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
                        "id":7
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
        ]');
        $menu->save();
    }

}
