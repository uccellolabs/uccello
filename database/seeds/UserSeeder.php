<?php

namespace Sardoj\Uccello\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Sardoj\Uccello\User;
use Sardoj\Uccello\Module;
use Sardoj\Uccello\Tab;
use Sardoj\Uccello\Block;
use Sardoj\Uccello\Field;
use Sardoj\Uccello\Filter;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createData();
        $module = $this->createModule();
        $this->createTabsBlocksFields($module);
        $this->createFilters($module);
    }

    public function createData()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@uccello.io',
            'password' => Hash::make( '123456')
        ]);
    }

    protected function createModule()
    {
        return Module::create([
            'name' => 'user',
            'icon' => 'person',
            'entity_class' => 'Sardoj\Uccello\User',
            'is_for_admin' => true
        ]);
    }

    protected function createTabsBlocksFields($module)
    {
        // Main tab
        $tab = Tab::create([
            'label' => 'tab.main',
            'icon' => null,
            'sequence' => 0,
            'module_id' => $module->id
        ]);

        // Auth block
        $block = Block::create([
            'label' => 'block.auth',
            'icon' => 'lock',
            'description' => 'block.auth.description',
            'sequence' => 0,
            'tab_id' => $tab->id
        ]);

        // Name
        Field::create([
            'name' => 'name',
            'label' => 'field.name',
            'uitype' => Field::UITYPE_TEXT,
            'display_type' => Field::DISPLAY_TYPE_EVERYWHERE,
            'data' => ['rules' => 'required|alpha_dash|unique:users,name'],
            'sequence' => 0,
            'block_id' => $block->id
        ]);

        // Is Admin
        Field::create([
            'name' => 'is_admin',
            'label' => 'field.is_admin',
            'uitype' => Field::UITYPE_CHECKBOX,
            'display_type' => Field::DISPLAY_TYPE_EVERYWHERE,
            'sequence' => 1,
            'block_id' => $block->id
        ]);

        // Password
        Field::create([
            'name' => 'password',
            'label' => 'field.password',
            'uitype' => Field::UITYPE_PASSWORD,
            'display_type' => Field::DISPLAY_TYPE_CREATE_ONLY,
            'data' => ['rules' => 'min:6', 'repeated' => true],
            'sequence' => 2,
            'block_id' => $block->id
        ]);

        // Contact block
        $block = Block::create([
            'label' => 'block.contact',
            'icon' => 'person',
            'sequence' => 1,
            'tab_id' => $tab->id
        ]);

        // Email
        Field::create([
            'name' => 'email',
            'label' => 'field.email',
            'uitype' => Field::UITYPE_EMAIL,
            'display_type' => Field::DISPLAY_TYPE_EVERYWHERE,
            'data' => ['rules' => 'required|email|unique:users,email'],
            'sequence' => 0,
            'block_id' => $block->id
        ]);

        // Phone
        Field::create([
            'name' => 'phone',
            'label' => 'field.phone',
            'uitype' => Field::UITYPE_TEL,
            'display_type' => Field::DISPLAY_TYPE_EVERYWHERE,
            'sequence' => 1,
            'block_id' => $block->id
        ]);
    }

    protected function createFilters($module)
    {
        Filter::create([
            'module_id' => $module->id,
            'domain_id' => null,
            'user_id' => null,
            'name' => 'filter.all',
            'type' => 'list',
            'columns' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            'conditions' => null,
            'order_by' => null,
            'is_default' => true,
            'is_public' => false
        ]);
    }
}
