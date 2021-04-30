<?php

namespace Uccello\Core\Tests\Unit\Support\Structure;

use PHPUnit\Framework\TestCase;
use Uccello\Core\Support\Structure\Module;
use Uccello\Core\Support\Structure\Tab;
use Uccello\Core\Support\Structure\Block;
use Uccello\Core\Support\Structure\Field;
use Uccello\Core\Support\Structure\Filter;
use Uccello\Core\Support\Structure\RelatedList;

class ModuleTest extends TestCase
{
    private function makeModule($params)
    {
        $data = json_decode(json_encode($params));

        return new Module($data);
    }

    public function testIfStructureIsConvertedInCascade()
    {
        $module = $this->makeModule([
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
        $this->assertTrue($module instanceof Module);
        $this->assertTrue($module->tabs[0] instanceof Tab);
        $this->assertTrue($module->tabs[0]->blocks[0] instanceof Block);
        $this->assertTrue($module->tabs[0]->blocks[0]->fields[0] instanceof Field);
        $this->assertTrue($module->filters[0] instanceof Filter);
        $this->assertTrue($module->relatedLists[0] instanceof RelatedList);

    }
}
