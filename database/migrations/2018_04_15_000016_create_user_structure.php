<?php

use Illuminate\Database\Migrations\Migration;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;

class CreateUserStructure extends Migration
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
        Module::where('name', 'user')->forceDelete();
    }

    protected function createModule()
    {
        $module = new Module();
        $module->name = 'user';
        $module->icon = 'person';
        $module->model_class = 'Uccello\Core\Models\User';
        $module->data = ["package" => "uccello/uccello", "admin" => true];
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

        // Auth block
        $block = new Block();
        $block->label = 'block.auth';
        $block->icon = 'lock';
        $block->data = ['description' => 'block.auth.description'];
        $block->sequence = 0;
        $block->tab_id = $tab->id;
        $block->module_id = $module->id;
        $block->save();

        // Username
        $field = new Field();
        $field->name = 'username';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = ['rules' => 'required|regex:/^[a-zA-Z0-9.-_]+$/|unique:users,username,%id%'];
        $field->sequence = 0;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // First name
        $field = new Field();
        $field->name = 'first_name';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = null;
        $field->sequence = 1;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Last name
        $field = new Field();
        $field->name = 'last_name';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = ['rules' => 'required'];
        $field->sequence = 2;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Is Admin
        $field = new Field();
        $field->name = 'is_admin';
        $field->uitype_id = uitype('boolean')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = ['module' => 'domain', 'field' => 'name'];
        $field->sequence = 3;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Password
        $field = new Field();
        $field->name = 'password';
        $field->uitype_id = uitype('password')->id;
        $field->displaytype_id = displaytype('create')->id;
        $field->data = ['rules' => 'required|min:6', 'repeated' => true];
        $field->sequence = 4;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Contact block
        $block = new Block();
        $block->label = 'block.contact';
        $block->icon = 'person';
        $block->sequence = 1;
        $block->tab_id = $tab->id;
        $block->module_id = $module->id;
        $block->save();

        // Email
        $field = new Field();
        $field->name = 'email';
        $field->uitype_id = uitype('email')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = ['rules' => 'required|email|unique:users,email,%id%'];
        $field->sequence = 0;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Phone
        $field = new Field();
        $field->name = 'phone';
        $field->uitype_id = uitype('phone')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
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
        $filter->columns = ['id', 'username', 'first_name', 'last_name', 'email', 'created_at', 'updated_at'];
        $filter->conditions = null;
        $filter->order_by = null;
        $filter->is_default = true;
        $filter->is_public = false;
        $filter->save();
    }
}
