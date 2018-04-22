<?php

namespace Sardoj\Uccello\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Sardoj\Uccello\User;
use Sardoj\Uccello\Module;
use Sardoj\Uccello\Tab;
use Sardoj\Uccello\Block;

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
            'entity_class' => 'User',
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

        // Contact block
        $block = Block::create([
            'label' => 'block.contact',
            'icon' => 'person',
            'sequence' => 1,
            'tab_id' => $tab->id
        ]);
    }
}
