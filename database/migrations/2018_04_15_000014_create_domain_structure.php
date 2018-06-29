<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Database\Migrations\Traits\TablePrefixTrait;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;

class CreateDomainStructure extends Migration
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
        Module::where('name', 'domaine')->forceDelete();
    }

    protected function createModule()
    {
        $module = new  Module();
        $module->name = 'domain';
        $module->icon = 'domain';
        $module->model_class = 'Uccello\Core\Models\Domain';
        $module->is_for_admin = true;
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
        $block->save();

        // Name
        $field = new Field();
        $field->name = 'name';
        $field->label = 'field.name';
        $field->uitype = Field::UITYPE_TEXT;
        $field->display_type = Field::DISPLAY_TYPE_EVERYWHERE;
        $field->data = ['rules' => 'required|unique:'.$this->getTablePrefix().'domains,name,%id%'];
        $field->sequence = 0;
        $field->block_id = $block->id;
        $field->save();

        // Parent
        $field = new Field();
        $field->name = 'parent_id';
        $field->label = 'field.parent_id';
        $field->uitype = Field::UITYPE_ENTITY;
        $field->display_type = Field::DISPLAY_TYPE_EVERYWHERE;
        $field->data = ['module' => 'domain', 'field' => 'name'];
        $field->sequence = 1;
        $field->block_id = $block->id;
        $field->save();

        // Description
        $field = new Field();
        $field->name = 'description';
        $field->label = 'field.description';
        $field->uitype = Field::UITYPE_TEXTAREA;
        $field->display_type = Field::DISPLAY_TYPE_EVERYWHERE;
        $field->data = ['large' => true];
        $field->sequence = 2;
        $field->block_id = $block->id;
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
        $filter->columns = ['id', 'name', 'description', 'parent_id', 'created_at', 'updated_at'];
        $filter->conditions = null;
        $filter->order_by = null;
        $filter->is_default = true;
        $filter->is_public = false;
        $filter->save();
    }
}
