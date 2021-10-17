<?php

namespace Uccello\Core\Modules;

use Uccello\Core\Support\Structure\Block;
use Uccello\Core\Support\Structure\Field;
use Uccello\Core\Support\Structure\Filter;
use Uccello\Core\Support\Structure\Module;
use Uccello\Core\Support\Structure\Tab;

class Workspace extends Module
{
    public $icon = 'workspace';
    public $model = \Uccello\Core\Models\Workspace::class;
    public $package = 'uccello/uccello';
    public $admin = true;
    public $required = true;

    public function tabs()
    {
        return [
            // Main
            new Tab([
                'name' => 'general',
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
                                'rules' => [
                                    'regex:/(?!^\d+$)^.+$/',
                                    'unique:'.config('uccello.database.table_prefix').'workspaces,name,%id%'
                                ]
                            ]),
                            new Field([
                                'name' => 'parent',
                                'type' => 'entity',
                                'visible' => true,
                                'options' => [
                                    'module' => 'workspace',
                                ]
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
                ]
            ])
        ];
    }

    public function filters()
    {
        return [
            new Filter([
                'name' => 'all',
                'type' => 'list',
                'columns' => ['name', 'parent'],
                'default' => true,
                'readonly' => true,
            ])
        ];
    }
}
