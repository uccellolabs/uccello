<?php

namespace Uccello\Core\Modules;

use Uccello\Core\Support\Structure\Block;
use Uccello\Core\Support\Structure\Field;
use Uccello\Core\Support\Structure\Filter;
use Uccello\Core\Support\Structure\Module;
use Uccello\Core\Support\Structure\Tab;

class Role extends Module
{
    public $icon = 'lock';
    public $model = \Uccello\Core\Models\Role::class;
    public $package = 'uccello/uccello';
    public $admin = true;
    public $required = true;

    public function tabs()
    {
        return [
            // Main
            new Tab([
                'name' => 'main',
                'blocks' => [
                    // General
                    new Block([
                        'name' => 'general',
                        'icon' => 'info',
                        'fields' => [
                            new Field([
                                'name' => 'name',
                                'type' => 'string',
                                'visible' => true,
                                'required' => true,
                            ]),
                            new Field([
                                'name' => 'parent',
                                'type' => 'entity',
                                'visible' => true,
                                'config' => [
                                    'module' => 'role'
                                ]
                            ]),
                            new Field([
                                'name' => 'see_descendant_records',
                                'type' => 'boolean',
                                'visible' => true,
                                'info' => 'field_info.see_descendant_records',
                            ]),
                            new Field([
                                'name' => 'description',
                                'type' => 'string',
                                'visible' => true,
                            ]),
                        ]
                    ]),
                    // System
                    new Block([
                        'name' => 'system',
                        'icon' => 'settings',
                        'closed' => true,
                        'fields' => [
                            new Field([
                                'name' => 'workspace',
                                'type' => 'entity',
                                'visible' => [
                                    'detail' => true,
                                    'list' => true
                                ],
                                'options' => [
                                    'module' => 'workspace'
                                ]
                            ]),
                            new Field([
                                'name' => 'created_at',
                                'type' => 'datetime',
                                'visible' => [
                                    'detail' => true,
                                    'list' => true
                                ],
                            ]),
                            new Field([
                                'name' => 'updated_at',
                                'type' => 'datetime',
                                'visible' => [
                                    'detail' => true,
                                    'list' => true
                                ],
                            ])
                        ]
                    ])
                ],
            ])
        ];
    }

    public function filter()
    {
        return [
            new Filter([
                'name' => 'all',
                'type' => 'list',
                'columns' => ['name', 'description'],
                'default' => true,
                'readonly' => true,
            ])
        ];
    }
}
