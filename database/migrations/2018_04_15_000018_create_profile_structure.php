<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;

class CreateProfileStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = $this->createModule();
        $this->createTabsBlocksFields($module);
        $this->createFilters($module);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Module::where('name', 'profile')->forceDelete();
    }

    protected function createModule()
    {
        $module = new Module();
        $module->name = 'profile';
        $module->icon = 'lock';
        $module->model_class = 'Uccello\Core\Models\Profile';
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
        $block->icon = 'info';
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
        $field->data = [ 'large' => true ];
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
        $filter->columns = [ 'id', 'name', 'description', 'created_at', 'updated_at' ];
        $filter->conditions = null;
        $filter->order_by = null;
        $filter->is_default = true;
        $filter->is_public = false;
        $filter->save();
    }
}
