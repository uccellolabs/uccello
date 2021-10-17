<?php

namespace Uccello\Core\Modules;

use Uccello\Core\Support\Structure\Block;
use Uccello\Core\Support\Structure\Field;
use Uccello\Core\Support\Structure\Filter;
use Uccello\Core\Support\Structure\Module;
use Uccello\Core\Support\Structure\Tab;


class User extends Module
{
    public $icon = 'person';
    public $model = \App\Models\User::class;
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
                                'name' => 'username',
                                'type' => 'string',
                                'visible' => true,
                                'required' => true,
                                'rules' => [
                                    'regex:/^(?:[A-Z\d][A-Z\d_-]{3,}|[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})$/i',
                                    'unique:users,username,%id%',
                                ],
                            ]),
                            new Field([
                                'name' => 'is_admin',
                                'type' => 'boolean',
                                'visible' => true,
                            ]),
                            new Field([
                                'name' => 'name',
                                'type' => 'string',
                                'visible' => true,
                                'required' => true,
                                'icon' => 'person',
                            ]),
                            new Field([
                                'name' => 'email',
                                'type' => 'email',
                                'visible' => true,
                                'required' => true,
                                'rules' => [
                                    'email',
                                    'unique:users,email,%id%',
                                ],
                            ]),
                            new Field([
                                'name' => 'password',
                                'type' => 'password',
                                'visible' => [
                                    'create' => true,
                                ],
                                'required' => true,
                                'rules' => [
                                    'min:6'
                                ],
                                'options' => [
                                    'confirm' => true,
                                ]
                            ]),
                            new Field([
                                'name' => 'status',
                                'type' => 'select',
                                'visible' => [
                                    'detail' => true,
                                    'list' => true
                                ],
                                'options' => [
                                    'choices' => [
                                        "status.pending",
                                        "status.active",
                                        "status.blocked",
                                    ]
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
                'columns' => ['username', 'name', 'email', 'is_admin'],
                'default' => true,
                'readonly' => true,
            ])
        ];
    }
}
