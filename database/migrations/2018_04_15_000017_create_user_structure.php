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
        $module->model_class = 'App\User';
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
        $tab->sequence = $module->tabs()->count();
        $tab->module_id = $module->id;
        $tab->save();

        // Auth block
        $block = new Block();
        $block->label = 'block.general';
        $block->icon = 'lock';
        $block->data = null;
        $block->sequence = $module->blocks()->count();
        $block->tab_id = $tab->id;
        $block->module_id = $module->id;
        $block->save();

        // Username
        $field = new Field();
        $field->name = 'username';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = [ 'rules' => 'required|regex:/^(?:[A-Z\d][A-Z\d_-]{3,}|[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})$/i|unique:users,username,%id%', 'icon' => 'vpn_key' ];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Is Admin
        $field = new Field();
        $field->name = 'is_admin';
        $field->uitype_id = uitype('boolean')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = [ 'module' => 'domain', 'field' => 'name' ];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Name
        $field = new Field();
        $field->name = 'name';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = [ 'rules' => 'required', 'icon' => 'person' ];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Email
        $field = new Field();
        $field->name = 'email';
        $field->uitype_id = uitype('email')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = [ 'rules' => 'required|email|unique:users,email,%id%' ];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Password
        $field = new Field();
        $field->name = 'password';
        $field->uitype_id = uitype('password')->id;
        $field->displaytype_id = displaytype('create')->id;
        $field->data = [ 'rules' => 'required|min:6', 'repeated' => true ];
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // System block
        $block = new Block();
        $block->label = 'block.system';
        $block->icon = 'settings';
        $block->data = null;
        $block->sequence = $module->blocks()->count();
        $block->tab_id = $tab->id;
        $block->module_id = $module->id;
        $block->save();

        // Created at
        $field = new Field();
        $field->name = 'created_at';
        $field->uitype_id = uitype('datetime')->id;
        $field->displaytype_id = displaytype('detail')->id;
        $field->data = null;
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Updated at
        $field = new Field();
        $field->name = 'updated_at';
        $field->uitype_id = uitype('datetime')->id;
        $field->displaytype_id = displaytype('detail')->id;
        $field->data = null;
        $field->sequence = $module->fields()->count();
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Domain
        $field = new Field();
        $field->name = 'domain';
        $field->uitype_id = uitype('entity')->id;
        $field->displaytype_id = displaytype('detail')->id;
        $field->data = ['module' => 'domain'];
        $field->sequence = $module->fields()->count();
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
        $filter->columns = [ 'username', 'name', 'email', 'is_admin' ];
        $filter->conditions = null;
        $filter->order = null;
        $filter->is_default = true;
        $filter->is_public = false;
        $filter->data = [ 'readonly' => true ];
        $filter->save();
    }
}
