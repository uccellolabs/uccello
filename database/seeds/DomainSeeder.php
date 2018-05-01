<?php

namespace Sardoj\Uccello\Seeds;

use Illuminate\Database\Seeder;
use Sardoj\Uccello\Domain;
use Sardoj\Uccello\Module;
use Sardoj\Uccello\Tab;
use Sardoj\Uccello\Block;
use Sardoj\Uccello\Field;
use Sardoj\Uccello\Database\Migrations\Traits\TablePrefixTrait;
use Sardoj\Uccello\Filter;

class DomainSeeder extends Seeder
{
    use TablePrefixTrait;

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

    protected function createData()
    {
        Domain::create([
            'name' => 'Default',
            'description' => null,
            'parent_id' => null
        ]);
    }

    protected function createModule()
    {
        return Module::create([
            'name' => 'domain',
            'icon' => 'domain',
            'entity_class' => 'Sardoj\Uccello\Domain',
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

        // General block
        $block = Block::create([
            'label' => 'block.general',
            'icon' => 'info',
            'sequence' => 0,
            'tab_id' => $tab->id
        ]);

        // Name
        Field::create([
            'name' => 'name',
            'label' => 'field.name',
            'uitype' => Field::UITYPE_TEXT,
            'display_type' => Field::DISPLAY_TYPE_EVERYWHERE,
            'data' => ['rules' => 'required|unique:'.$this->getTablePrefix().'domains,name'],
            'sequence' => 0,
            'block_id' => $block->id
        ]);

        // Parent
        Field::create([
            'name' => 'parent_id',
            'label' => 'field.parent_id',
            'uitype' => Field::UITYPE_ENTITY,
            'display_type' => Field::DISPLAY_TYPE_EVERYWHERE,
            'data' => ['module' => 'domain', 'field' => 'name'],
            'sequence' => 1,
            'block_id' => $block->id
        ]);

        // Description
        Field::create([
            'name' => 'description',
            'label' => 'field.description',
            'uitype' => Field::UITYPE_TEXTAREA,
            'display_type' => Field::DISPLAY_TYPE_EVERYWHERE,
            'data' => ['large' => true],
            'sequence' => 2,
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
            'columns' => ['id', 'name', 'description', 'parent_id', 'created_at', 'updated_at'],
            'conditions' => null,
            'order_by' => null,
            'is_default' => true,
            'is_public' => false
        ]);
    }
}
