<?php

namespace Uccello\Core\Tests\Unit\Support\Structure;

use PHPUnit\Framework\TestCase;
use Uccello\Core\Support\Structure\ModuleStructure;
use Uccello\Core\Support\Structure\Tab;
use Uccello\Core\Support\Structure\Block;
use Uccello\Core\Support\Structure\Field;
use Uccello\Core\Support\Structure\Filter;
use Uccello\Core\Support\Structure\RelatedList;

class ModuleStructureTest extends TestCase
{
    public function testIfArrayIsConvertedIntoModuleStructureObject()
    {
        $module = new ModuleStructure([
            'name' => 'user',
        ]);

        $this->assertTrue($module instanceof ModuleStructure);
    }

    public function testIfStdClassIsConvertedIntoModuleStructureObject()
    {
        $moduleObject = new \stdClass;
        $moduleObject->name = 'user';

        $module = new ModuleStructure($moduleObject);

        $this->assertTrue($module instanceof ModuleStructure);
    }

    public function testIfStructureIsConvertedInCascade()
    {
        $module = new ModuleStructure([
            'name' => 'user',
            'icon' => 'person',
            'tabs' => [
                [
                    'name' => 'main',
                    'blocks' => [
                        [
                            'name' => 'general',
                            'icon' => 'info',
                            'fields' => [
                                [
                                    'name' => 'username',
                                    'type' => 'string',
                                    'visible' => true,
                                    'required' => true,
                                ]
                            ]
                        ]
                    ]
                ],
            ],
            'filters' => [
                [
                    'name' => 'all',
                    'type' => 'list',
                    'columns' => ['username'],
                    'default' => true,
                    'readonly' => true,
                ],
            ],
            'relatedLists' => [
                [
                    'name' => 'roles',
                ],
            ]
        ]);

        $this->assertEquals($module->name, 'user');
        $this->assertTrue($module instanceof ModuleStructure);
        $this->assertTrue($module->tabs[0] instanceof Tab);
        $this->assertTrue($module->tabs[0]->blocks[0] instanceof Block);
        $this->assertTrue($module->tabs[0]->blocks[0]->fields[0] instanceof Field);
        $this->assertTrue($module->filters[0] instanceof Filter);
        $this->assertTrue($module->relatedLists[0] instanceof RelatedList);
    }

    public function testIfColletionsAreInitialized()
    {
        $module = new ModuleStructure([
            'name' => 'user'
        ]);

        // Add tab
        $module->addTab([
            'name' => 'detail'
        ]);

        // Add filter
        $module->addFilter([
            'name' => 'all'
        ]);

        // Add related list
        $module->addRelatedList([
            'name' => 'contacts'
        ]);

        $this->assertTrue($module->tabs->count() === 1);
        $this->assertTrue($module->relatedLists->count() === 1);
        $this->assertTrue($module->filters->count() === 1);

    }
}
